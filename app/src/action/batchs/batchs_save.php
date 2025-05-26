<?php

/** Importação de classes */

use src\model\Batchs;
use src\controller\Batchs\BatchsValidate;

try {

    /** Instânciamento de classes */
    $Batchs = new Batchs();
    $BatchsValidate = new BatchsValidate();

    /** Controle de resultados */
    $result = null;

    /** Parametros de entrada */
    $batchId        = isset($_POST['batch_id'])         ? (int)filter_input(INPUT_POST, 'batch_id', FILTER_SANITIZE_NUMBER_INT)          : 0;
    $companyId      = isset($_POST['company_id'])       ? (int)filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_NUMBER_INT)        : 0;
    $fileCategoryId = isset($_POST['file_category_id']) ? (int)filter_input(INPUT_POST, 'file_category_id', FILTER_SANITIZE_NUMBER_INT)  : 0;
    $description    = isset($_POST['description'])      ? (string)filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $status         = isset($_POST['status'])           ? (string)filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS)      : '';
    $csrfToken      = isset($_POST['csrf_token'])       ? (string)filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS)  : null;

    /** Id do usuário logado */
    $userId = $_SESSION['MY_SAAS_USER']->user_id;

    /** Verifica se o Token CSRF é válido */
    if ($csrfToken === $_SESSION['csrf_token']) {

        /** Validando os campos de entrada */
        $BatchsValidate->setBatchId($batchId);
        $BatchsValidate->setUserId($userId);
        $BatchsValidate->setCompanyId($companyId);
        $BatchsValidate->setFileCategoryId($fileCategoryId);
        $BatchsValidate->setDescription($description);
        $BatchsValidate->setStatus($status);

        /** Verifico a existência de erros */
        if (!empty($BatchsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($BatchsValidate->getErrors(), 0);
        } else {

            /** Efetua um novo cadastro ou salva os novos dados */
            if ($Batchs->Save(
                $BatchsValidate->getBatchId(),
                $BatchsValidate->getCompanyId(),
                $BatchsValidate->getFileCategoryId(),
                $BatchsValidate->getUserId(),
                $BatchsValidate->getDescription(),
                $BatchsValidate->getStatus()
            )) {

                /** Prepara a mensagem de retorno - sucesso */
                $result = [

                    'code' => 200,
                    'data' => ($BatchsValidate->getBatchId() > 0 ? 'Cadastro atualizado com sucesso' : 'Cadastro efetuado com sucesso'),
                    'toast' => [
                        [
                            'background' => ($BatchsValidate->getStatus() == 'D' ? 'warning' : 'success'),
                            'data' => ($BatchsValidate->getStatus() == 'D' ? '<i class="bi bi-trash3 me-1"></i>Lote removido com sucesso' : '<i class="bi bi-check me-1"></i>Dados salvos com sucesso')
                        ]
                    ]
                ];

                if ($BatchsValidate->getStatus() == 'D') {

                    $result["remove"] = [
                        [
                            'id' => $BatchsValidate->getStatus() == 'D' ? $BatchsValidate->getBatchId() : null
                        ]
                    ];
                } else {

                    $result["redirect"] = [
                        [
                            'path' => 'view/batchs/batchs_index',
                            'type' => 3,
                            'target' => 'app-main'
                        ]
                    ];
                }
            } else {

                /** Retorno mensagem de erro */
                throw new InvalidArgumentException(($BatchsValidate->getBatchId() > 0 ? 'Não foi possível atualizar o cadastro' : 'Não foi possível efetuar o cadastro'), 0);
            }
        }

        /** Envio */
        echo json_encode($result);

        /** Paro o procedimento */
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
