<?php

/** Importação de classes  */

use src\model\Files;
use src\controller\files\FilesValidate;

try {

    /** Instânciamento de classes  */
    $Files = new Files();
    $FilesValidate = new FilesValidate();

    /** Parametros de entrada */
    $fileId         = isset($_POST['file_id'])          ? (int)filter_input(INPUT_POST, 'file_id', FILTER_SANITIZE_NUMBER_INT)          : 0;
    $fileCategoryId = isset($_POST['file_category_id']) ? (int)filter_input(INPUT_POST, 'file_category_id', FILTER_SANITIZE_NUMBER_INT) : 0;
    $tags           = isset($_POST['tags'])             ? (array)array_map('htmlspecialchars', $_POST['tags'])                          : [];
    $required       = isset($_POST['required'])         ? (array)array_map('htmlspecialchars', $_POST['required'])                      : [];
    $csrfToken      = isset($_POST['csrf_token'])       ? (string)filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS) : null;
    $userId         = $_SESSION['MY_SAAS_USER']->user_id;

    /** Verifica se o Token CSRF é válido */
    if ($csrfToken === $_SESSION['csrf_token']) {

        /** Sanitiza os parametros de entrada */
        $FilesValidate->setFileId($fileId);
        $FilesValidate->setFileCategoryId($fileCategoryId);
        $FilesValidate->setUserId($userId);
        $FilesValidate->setTags($tags);
        $FilesValidate->setRequired($required);
        $FilesValidate->checkRequerid();

        /** Verifica a existência de erros durante a validação */
        if (!empty($FilesValidate->getErrors())) {

            /** Caso ocorra algum erro, lança uma exceção com a mensagem de erro. */
            throw new InvalidArgumentException($FilesValidate->getErrors(), 0);
        } else {

            /** Salvo o arquivo desejado */
            if ($Files->SaveTags(
                $FilesValidate->getFileId(),
                $FilesValidate->getFileCategoryId(),
                $FilesValidate->getTags(),
                $FilesValidate->getUserId()
            )) {

                // Result
                $result = [

                    'code' => 200,
                    'data' => 'Indexação adicionada',
                    'toast' => [
                        [
                            'background' => 'success',
                            'data' => 'Indexação adicionada'
                        ]
                    ],

                ];
            }
        }

        /** Envio do resultado em formato JSON */
        echo json_encode($result);

        /** Encerra o procedimento */
        exit;
    } else {

        /** Informo */
        throw new InvalidArgumentException('Token CSRF inválido', 0);
    }
} catch (Exception $exception) {

    /** Preparo o formulario para retorno **/
    $result = [

        'code' => 0,
        'data' => $exception->getMessage(),
        'toast' => [
            [
                'background' => 'warning',
                'data' => $exception->getMessage()
            ]
        ],
    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}
