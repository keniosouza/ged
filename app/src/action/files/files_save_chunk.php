<?php

/** Importação de classes  */

use src\model\Files;
use src\controller\files\FilesValidate;
use src\controller\files\FilesProcedures;

try {

    /** Instânciamento de classes  */
    $Files = new Files();
    $FilesValidate = new FilesValidate();
    $FilesProcedures = new FilesProcedures();

    /** Parâmetros de entrada dos ARQUIVOS */
    $name      = isset($_POST['name'])       ? (string)filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS)       : '';
    $base64    = isset($_POST['base64'])     ? (string)filter_input(INPUT_POST, 'base64', FILTER_UNSAFE_RAW)                 : '';
    $hash      = isset($_POST['hash'])       ? (string)filter_input(INPUT_POST, 'hash', FILTER_UNSAFE_RAW)                   : '';
    $chunkPart = isset($_POST['chunk_part']) ? (string)filter_input(INPUT_POST, 'chunk_part', FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $chunkSize = isset($_POST['chunk_size']) ? (string)filter_input(INPUT_POST, 'chunk_size', FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $extension = isset($_POST['extension'])  ? (string)filter_input(INPUT_POST, 'extension', FILTER_SANITIZE_SPECIAL_CHARS)  : '';

    /** Define o local do arquivo e realiza validações */
    $FilesValidate->setPath('storage/temp/' . $hash);
    $FilesValidate->setBase64($base64);
    $FilesValidate->setName($name);

    /** Verifica a existência de erros durante a validação */
    if (!empty($FilesValidate->getErrors())) {

        /** Caso ocorra algum erro, lança uma exceção com a mensagem de erro. */
        throw new InvalidArgumentException($FilesValidate->getErrors(), 0);
    } else {

        /** Gera o arquivo desejado com base nas informações fornecidas. */
        if ($FilesProcedures->generate(
            $FilesValidate->getPath(),
            $chunkPart . '_part',
            $FilesValidate->getBase64()
        )) {

            // Result
            $result = [

                'code' => 200,
                'title' => 'Atenção',
                'data' => 'Enviando',
                'merge' => $chunkPart === $chunkSize ? true : false,
                'file_id' => $chunkPart === $chunkSize ? $Files->GenerateId(0, $_SESSION['MY_SAAS_USER']->company_id) : 0,

            ];
        } else {

            /** Caso ocorra algum erro, lança uma exceção com a mensagem de erro. */
            throw new InvalidArgumentException('Não foi possível enviar o arquivo', 0);
        }
    }

    /** Envio do resultado em formato JSON */
    echo json_encode($result);

    /** Encerra o procedimento */
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
