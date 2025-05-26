<?php

/** Importação de classes */

use src\model\FilesCategories;
use src\controller\files_categories\FilesCategoriesValidate;

try {

    /** Instânciamento de classes */
    $FilesCategories = new FilesCategories();
    $FilesCategoriesValidate = new FilesCategoriesValidate();

    /** Controle de resultados */
    $result = null;

    /** Parametros de entrada */
    $fileTypeId     = isset($_POST['file_type_id'])     ? (int)filter_input(INPUT_POST, 'file_type_id', FILTER_SANITIZE_NUMBER_INT)      : 0;
    $fileCategoryId = isset($_POST['file_category_id']) ? (int)filter_input(INPUT_POST, 'file_category_id', FILTER_SANITIZE_NUMBER_INT)  : 0;
    $description    = isset($_POST['description'])      ? (string)filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $status         = isset($_POST['status'])           ? (string)filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS)      : '';
    $csrfToken      = isset($_POST['csrf_token'])       ? (string)filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS)  : null;

    /** Id do usuário logado */
    $userId = (int)$_SESSION['MY_SAAS_USER']->user_id;

    /** Verifica se o Token CSRF é válido */
    if ($csrfToken === $_SESSION['csrf_token']) {

        /** Validando os campos de entrada */
        $FilesCategoriesValidate->setFileCategoryId($fileCategoryId);
        $FilesCategoriesValidate->setFileTypeId($fileTypeId);
        $FilesCategoriesValidate->setDescription($description);
        $FilesCategoriesValidate->setStatus($status);
        $FilesCategoriesValidate->setUserId($userId);

        /** Verifico a existência de erros */
        if (!empty($FilesCategoriesValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FilesCategoriesValidate->getErrors(), 0);
        } else {

            /** Efetua um novo cadastro ou salva os novos dados */
            if ($FilesCategories->Save(
                $FilesCategoriesValidate->getFileCategoryId(),
                $FilesCategoriesValidate->getFileTypeId(),
                $FilesCategoriesValidate->getDescription(),
                $FilesCategoriesValidate->getstatus(),
                $FilesCategoriesValidate->getUserId()
            )) {

                /** Prepara a mensagem de retorno - sucesso */
                $result = [

                    'code' => 200,
                    'data' => ($FilesCategoriesValidate->getFileTypeId() > 0 ? 'Cadastro atualizado com sucesso' : 'Cadastro efetuado com sucesso'),
                    'toast' => [
                        [
                            'background' => ($FilesCategoriesValidate->getStatus() == 'D' ? 'warning' : 'success'),
                            'data' => ($FilesCategoriesValidate->getStatus() == 'D' ? '<i class="bi bi-trash3 me-1"></i>Cadastro removido com sucesso' : '<i class="bi bi-check me-1"></i>Dados salvos com sucesso')
                        ]
                    ],
                ];

                /** Verifica se existem algum item a ser excluído */
                if ($FilesCategoriesValidate->getStatus() == 'D') {

                    $result["remove"] = [
                        [
                            'id' => $FilesCategoriesValidate->getStatus() == 'D' ? $FilesCategoriesValidate->getFileCategoryId() : null
                        ]
                    ];
                } else {

                    $result["redirect"] = [
                        [
                            'path' => 'view/files_categories/files_categories_index',
                            'type' => 3,
                            'target' => 'app-main'
                        ]
                    ];
                }
            } else {

                /** Retorno mensagem de erro */
                throw new InvalidArgumentException('Não foi possivel salvar o registro', 0);
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
