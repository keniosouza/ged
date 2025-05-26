<?php

/** Importação de classes */

use src\model\FilesTypes;
use src\controller\files_types\FilesTypesValidate;

try {

    /** Instânciamento de classes */
    $FilesTypes = new FilesTypes();
    $FilesTypesValidate = new FilesTypesValidate();

    /** Controle de resultados */
    $result = null;

    /** Parametros de entrada */
    $fileTypeId = isset($_POST['file_type_id']) ? (int)filter_input(INPUT_POST, 'file_type_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Id do usuário logado */
    $userId = (int)$_SESSION['MY_SAAS_USER']->user_id;

    /** Validando os campos de entrada */
    $FilesTypesValidate->setFileTypeId($fileTypeId);
    $FilesTypesValidate->setUserId($userId);

    /** Verifico a existência de erros */
    if (!empty($FilesTypesValidate->getErrors())) {

        /** Informo */
        throw new InvalidArgumentException($FilesTypesValidate->getErrors(), 0);
    } else {

        /** Efetua um novo cadastro ou salva os novos dados */
        if ($FilesTypes->Delete(
            $FilesTypesValidate->getFileTypeId(),
            $FilesTypesValidate->getUserId()
        )) {

            // Result
            $result = [

                'code' => 200,
                'data' => 'Tipo de arquivo removido com sucesso',
                'toast' => [
                    [
                        'background' => 'warning',
                        'data' => '<i class="bi bi-trash me-1"></i>Tipo de arquivo removido'
                    ]
                ],
                'remove' => [
                    [
                        'id' => $FilesTypesValidate->getFileTypeId(),
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
