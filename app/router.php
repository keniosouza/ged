<?php

global $UserSessionResult;

// Cabeçalhos de segurança
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Content-Security-Policy: default-src \'self\';');

/** Ativo a exibição de erros */
error_reporting(E_ALL);
ini_set('display_errors', 'On');

/** Inicializo a sessão */
session_start();

/** Importação do Autoload lado servidor */
require_once('./autoload.php');

/** Importação de classes */

use src\model\Logs;
use src\controller\main\Main;
use src\controller\logs\LogsValidate;
use src\controller\routers\RouterAuth;
use src\controller\routers\RouterValidate;

sleep(1);

try {

    /** Instânciamento de classes */
    $Main = new Main();
    $Logs = new Logs();
    $RouterAuth = new RouterAuth();
    $LogsValidate = new LogsValidate();
    $RouterValidate = new RouterValidate();

    /** Obtenho os dados do usuário */
    $userId    = isset($_SESSION['MY_SAAS_USER']) ? (int)$_SESSION['MY_SAAS_USER']->user_id : 0;
    $companyId = isset($_SESSION['MY_SAAS_USER']) ? (int)$_SESSION['MY_SAAS_USER']->company_id : 0;
    $result    = null;

    //Converto todas as chaves da array para minusculo
    $_POST = array_change_key_case($_POST, CASE_LOWER);

    //Dados do ‘log’
    $LogsValidate->setLogId(0);
    $LogsValidate->setUserId($userId);
    $LogsValidate->setCompanyId($companyId);
    $LogsValidate->setParentId((int) filter_input(INPUT_POST, 'log_parent_id', FILTER_SANITIZE_SPECIAL_CHARS));
    $LogsValidate->setRegisterId((int) filter_input(INPUT_POST, 'log_register_id', FILTER_SANITIZE_SPECIAL_CHARS));
    $LogsValidate->setRequest((string)filter_input(INPUT_POST, 'path', FILTER_SANITIZE_SPECIAL_CHARS));
    $LogsValidate->setDateRegister(date('Y/m/d H:i:s'));

    /** Parâmetros de Entrada */
    $RouterValidate->setPath((string)filter_input(INPUT_POST, 'path', FILTER_SANITIZE_SPECIAL_CHARS));

    /** Verifico a existência de erros */
    if (!empty($RouterValidate->getErrors())) {

        /** Mensagem de erro */
        throw new Exception($RouterValidate->getErrors());
    } else {

        /** Verifico se o arquivo de ação existe */
        if (is_file($RouterValidate->getFullPath())) {

            /** Verifico se a sessão da pessoa está ativa para concluir a requisição */
            if ($RouterAuth->checkAccess($RouterValidate->getPath())) {

                /** Verifico se o usuário está logado para realizar o log */
                if ($userId > 0) {

                    /** Defino os novos dados de log */
                    $LogsValidate->setLogTypeId(1);
                    $LogsValidate->setData(json_encode($_POST, JSON_PRETTY_PRINT));

                    /** Log de requisições */
                    $Logs->Save(
                        $LogsValidate->getLogId(),
                        $LogsValidate->getLogTypeId(),
                        $LogsValidate->getCompanyId(),
                        $LogsValidate->getParentId(),
                        $LogsValidate->getRegisterId(),
                        $LogsValidate->getUserId(),
                        $LogsValidate->getRequest(),
                        $LogsValidate->getData(),
                        $LogsValidate->getDateRegister()
                    );
                }

                /** Inicio a coleta de dados */
                ob_start();

                /** Inclusão do arquivo desejado */
                include_once $RouterValidate->getFullPath();

                /** Prego a estrutura do arquivo */
                $data = ob_get_contents();

                /** Removo o arquivo incluido */
                ob_end_clean();

                // Result
                $result = [

                    'code' => 100,
                    'data' => $data

                ];

                /** Envio **/
                echo json_encode($result);

                /** Paro o procedimento **/
                exit;
            } else {

                /** Mensagem de erro */
                throw new Exception('Erro :: Para acesso a esta solicitação, é preciso estar logado .');
            }
        } else {

            /** Mensagem de erro */
            throw new Exception('Erro :: Não há arquivo para ação informada.');
        }
    }
} catch (Exception $exception) {

    /** Tratamento da mensagem de erro */
    //$resultException = '<b>Arquivo:</b> ' . $exception->getFile() . '; <b>Linha:</b> ' . $exception->getLine() . '; <b>Código:</b> ' . $exception->getCode() . '; <b>Mensagem:</b> ' . $exception->getMessage();
    $resultException = $exception->getMessage();

    /** Verifico se devo realizar o log */
    if (@$userId > 0) {

        /** Escrevo a mensagem de requisição */
        $_POST['exception'] = $resultException;

        /** Defino os novos dados de log */
        $LogsValidate->setLogTypeId(logTypeId: 2);
        $LogsValidate->setData(json_encode($_POST, JSON_PRETTY_PRINT));

        /** Log de requisições */
        $Logs->Save($LogsValidate->getLogId(), $LogsValidate->getLogTypeId(), $LogsValidate->getCompanyId(), $LogsValidate->getParentId(), $LogsValidate->getRegisterId(), $LogsValidate->getUserId(), $LogsValidate->getRequest(), $LogsValidate->getData(), $LogsValidate->getDateRegister());
    }

    /** Preparo o formulario para retorno **/
    $result = [

        'code' => 0,
        'modal' => [[
            'title' => 'Atenção',
            'data' => $resultException,
            'size' => 'md',
            'type' => null,
            'procedure' => null,
        ]],

    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}
