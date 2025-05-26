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
    $fileCategoryId = isset($_POST['file_category_id']) ? (int)filter_input(INPUT_POST, 'file_category_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Id do usuário logado */
    $userId = (int)$_SESSION['MY_SAAS_USER']->user_id;

    /** Validando os campos de entrada */
    $FilesCategoriesValidate->setFileCategoryId($fileCategoryId);
    $FilesCategoriesValidate->setUserId($userId);

    /** Verifico a existência de erros */
    if (!empty($FilesCategoriesValidate->getErrors())) {

        /** Informo */
        throw new InvalidArgumentException($FilesCategoriesValidate->getErrors(), 0);
    } else {

        /** Efetua um novo cadastro ou salva os novos dados */
        if ($FilesCategories->Delete(
            $FilesCategoriesValidate->getFileCategoryId(),
            $FilesCategoriesValidate->getUserid()
        )) {

            // Result
            $result = [

                'code' => 200,
                'data' => 'Categoria de arquivo removido com sucesso',
                'toast' => [
                    [
                        'background' => 'warning',
                        'data' => '<i class="bi bi-trash me-1"></i>Categoria de arquivo removida'
                    ]
                ],
                'remove' => [
                    [
                        'id' => $FilesCategoriesValidate->getFileCategoryId(),
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
