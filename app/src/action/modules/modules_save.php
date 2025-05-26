<?php

/** Importação de classes */

use src\model\Modules;
use src\controller\modules\ModulesValidate;

try {

    /** Instânciamento de classes */
    $Modules = new Modules();
    $ModulesValidate = new ModulesValidate();

    /** Parametros de entrada  */
    $description = isset($_POST['description']) ? (string)filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $name        = isset($_POST['name'])        ? (string)filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS)        : '';
    $moduleId    = isset($_POST['module_id'])   ? (int)filter_input(INPUT_POST, 'module_id', FILTER_SANITIZE_NUMBER_INT)         : '';
    $companyId   = isset($_POST['company_id'])  ? (int)filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_NUMBER_INT)        : '';
    $status      = isset($_POST['status'])      ? (string)filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS)      : '';
    $csrfToken   = isset($_POST['csrf_token'])  ? (string)filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS)  : null;

    /** Verifica se o Token CSRF é válido */
    if ($csrfToken === $_SESSION['csrf_token']) {

        /** Validando os campos de entrada */
        $ModulesValidate->setDescription($description);
        $ModulesValidate->setName($name);
        $ModulesValidate->setModuleId($moduleId);
        $ModulesValidate->setCompanyId($companyId);
        $ModulesValidate->setStatus($status);


        /** Verifico a existência de erros */
        if (!empty($ModulesValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($ModulesValidate->getErrors(), 0);
        } else {

            /** Efetua um novo cadastro ou salva os novos dados */
            if ($Modules->Save(
                $_SESSION['MY_SAAS_USER']->user_id,
                $ModulesValidate->getModuleId(),
                $ModulesValidate->getCompanyId(),
                $ModulesValidate->getName(),
                $ModulesValidate->getDescription(),
                $ModulesValidate->getStatus()
            )) {

                /** Prepara a mensagem de retorno - sucesso */
                $result = [

                    'code' => 200,
                    'data' => ($ModulesValidate->getModuleId() > 0 ? 'Cadastro atualizado com sucesso' : 'Cadastro efetuado com sucesso'),
                    'toast' => [
                        [
                            'background' => ($ModulesValidate->getStatus() == 'D' ? 'warning' : 'success'),
                            'data' => ($ModulesValidate->getStatus() == 'D' ? '<i class="bi bi-trash3 me-1"></i>Cadastro removido com sucesso' : '<i class="bi bi-check me-1"></i>Dados salvos com sucesso')
                        ]
                    ],
                ];

                /** Verifica se é a remoção de algum item */
                if ($ModulesValidate->getStatus() == 'D') {

                    $result["remove"] = [
                        [
                            'id' => $ModulesValidate->getStatus() == 'D' ? $ModulesValidate->getModuleId() : null
                        ]
                    ];
                } else {

                    $result["redirect"] = [
                        [
                            'path' => 'view/modules/modules_index',
                            'type' => 3,
                            'target' => 'app-main'
                        ]
                    ];
                }
            } else {

                /** Informo */
                throw new InvalidArgumentException(($ModulesValidate->getModuleId() > 0 ? 'Não foi possível atualizar o cadastro' : 'Não foi possível efetuar o cadastro'), 0);
            }
        }

        /** Envio **/
        echo json_encode($result);

        /** Paro o procedimento **/
        exit;
    } else {

        /** Informo */
        throw new InvalidArgumentException('Token CSRF inválido', 0);
    }
} catch (Exception $exception) {

    /** Preparo o formulario para retorno **/
    $result = [

        'code' => 0,
        'data' => $exception->getMessage()
    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}
