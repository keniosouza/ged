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

    /** Parametros de entrada */
    $fileCategoryTagId = isset($_POST['file_category_tag_id']) ? (int) filter_input(INPUT_POST, 'file_category_tag_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Id do usuário logado */
    $userId = (int)$_SESSION['MY_SAAS_USER']->user_id;

    /** Validando os campos de entrada */
    $FilesCategoriesTagsValidate->setFileCategoryTagId($fileCategoryTagId);
    $FilesCategoriesTagsValidate->setUserId($userId);

    /** Verifico a existência de erros */
    if (!empty($FilesCategoriesTagsValidate->getErrors())) {

        /** Informo */
        throw new InvalidArgumentException($FilesCategoriesTagsValidate->getErrors(), 0);
    } else {

        /** Efetua um novo cadastro ou salva os novos dados */
        if ($FilesCategoriesTags->Delete(
            $FilesCategoriesTagsValidate->getFileCategoryTagId(),
            $FilesCategoriesTagsValidate->getUserId()
        )) {

            // Result
            $result = [

                'code' => 200,
                'data' => 'Categoria de tag removido com sucesso',
                'toast' => [
                    [
                        'background' => 'warning',
                        'data' => '<i class="bi bi-trash me-1"></i>Categoria de tag de arquivo removido'
                    ]
                ],
                'remove' => [
                    [
                        'id' => $FilesCategoriesTagsValidate->getFileCategoryTagId(),
                    ]
                ]

            ];
        } else {

            /** Retorno mensagem de erro */
            throw new InvalidArgumentException('Não foi possivel remover o registro', 0);
        }
    }

    /** Envio */
    echo json_encode($result);

    /** Paro o procedimento */
    exit;
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
