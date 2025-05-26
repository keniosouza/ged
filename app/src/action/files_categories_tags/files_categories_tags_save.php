<?php

/** Importação de classes */

use src\model\FilesCategoriesTags;
use src\controller\files_categories_tags\FilesCategoriesTagsValidate;

try {

    /** Instânciamento de classes */
    $FilesCategoriesTags = new FilesCategoriesTags();
    $FilesCategoriesTagsValidate = new FilesCategoriesTagsValidate();

    /** Controle de resultados */
    $result = null;

    /** Filtros para santizar o campo preferences */
    $filter = [
        'name'  => FILTER_SANITIZE_FULL_SPECIAL_CHARS, // Escapa caracteres especiais
        'quantity' => FILTER_SANITIZE_NUMBER_INT,      // Valida número inteiro
        'format' => FILTER_SANITIZE_NUMBER_INT,        // Valida número inteiro
        'required'  => FILTER_SANITIZE_NUMBER_INT      // Valida número inteiro
    ];

    /** Parametros de entrada */
    $fileCategoryTagId = isset($_POST['file_category_tag_id']) ? (int) filter_input(INPUT_POST, 'file_category_tag_id', FILTER_SANITIZE_NUMBER_INT) : 0;
    $fileCategoryId    = isset($_POST['file_category_id'])     ? (int) filter_input(INPUT_POST, 'file_category_id', FILTER_SANITIZE_NUMBER_INT)     : 0;
    $description       = isset($_POST['description'])          ? (string) filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS)    : '';
    $preferences       = isset($_POST['preferences'])          ? (array)filter_var_array($_POST['preferences'], $filter)                            : [];
    $status            = isset($_POST['status'])               ? (string) filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS)         : '';
    $csrfToken         = isset($_POST['csrf_token'])           ? (string)filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS)      : null;

    /** Id do usuário logado */
    $userId = (int)$_SESSION['MY_SAAS_USER']->user_id;

    /** Verifica se o Token CSRF é válido */
    if ($csrfToken === $_SESSION['csrf_token']) {

        /** Validando os campos de entrada */
        $FilesCategoriesTagsValidate->setFileCategoryTagId($fileCategoryTagId);
        $FilesCategoriesTagsValidate->setFileCategoryId($fileCategoryId);
        $FilesCategoriesTagsValidate->setDescription($description);
        $FilesCategoriesTagsValidate->setPreferences($preferences);
        $FilesCategoriesTagsValidate->setStatus($status);
        $FilesCategoriesTagsValidate->setUserId($userId);

        /** Verifico a existência de erros */
        if (!empty($FilesCategoriesTagsValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($FilesCategoriesTagsValidate->getErrors(), 0);
        } else {

            /** Efetua um novo cadastro ou salva os novos dados */
            if ($FilesCategoriesTags->Save(
                $FilesCategoriesTagsValidate->getFileCategoryTagId(),
                $FilesCategoriesTagsValidate->getFileCategoryId(),
                $FilesCategoriesTagsValidate->getDescription(),
                $FilesCategoriesTagsValidate->getPreferences(),
                $FilesCategoriesTagsValidate->getStatus(),
                $FilesCategoriesTagsValidate->getUserId()
            )) {

                /** Prepara a mensagem de retorno - sucesso */
                $result = [

                    'code' => 200,
                    'data' => ($FilesCategoriesTagsValidate->getFileCategoryTagId() > 0 ? 'Cadastro atualizado com sucesso' : 'Cadastro efetuado com sucesso'),
                    'toast' => [
                        [
                            'background' => ($FilesCategoriesTagsValidate->getStatus() == 'D' ? 'warning' : 'success'),
                            'data' => ($FilesCategoriesTagsValidate->getStatus() == 'D' ? '<i class="bi bi-trash3 me-1"></i>Cadastro removido com sucesso' : '<i class="bi bi-check me-1"></i>Dados salvos com sucesso')
                        ]
                    ],
                ];

                /** Verifica se existem algum item a ser excluído */
                if ($FilesCategoriesTagsValidate->getStatus() == 'D') {

                    $result["remove"] = [
                        [
                            'id' => $FilesCategoriesTagsValidate->getStatus() == 'D' ? $FilesCategoriesTagsValidate->getFileCategoryTagId() : null
                        ]
                    ];
                } else {

                    $result["redirect"] = [
                        [
                            'path' => 'view/files_categories_tags/files_categories_tags_index',
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

        /** Retorno mensagem de erro */
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
