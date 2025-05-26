<?php

/** Importação de classes  */
use src\controller\main\Main;
use src\model\Files;
use src\controller\files\FilesValidate;
use src\controller\files\FilesProcedures;

/** Instânciamento de classes  */
$Main = new Main();
$Files = new Files();
$FilesValidate = new FilesValidate();

/** Define o local do arquivo e realiza validações */
$FilesValidate->setFileId((int)filter_input(INPUT_POST, 'file_id', FILTER_SANITIZE_SPECIAL_CHARS));
$FilesValidate->setFolderId((int)filter_input(INPUT_POST, 'folder_id', FILTER_SANITIZE_SPECIAL_CHARS));

/** Verifica a existência de erros durante a validação */
if (!empty($FilesValidate->getErrors())) {

    /** Caso ocorra algum erro, lança uma exceção com a mensagem de erro. */
    throw new InvalidArgumentException($FilesValidate->getErrors(), 0);

} else {

    /** Salvo o arquivo desejado */
    if ($Files->SaveFolder($FilesValidate->getFileId(), $FilesValidate->getFolderId())) {

        // Result
        $result = [

            'code' => 200,
            'data' => 'Perfil Atualizado',
            'toast' => [
                [
                    'background' => 'primary',
                    'data' => 'Usuário salvo!'
                ]
            ],

        ];

    }

}

/** Envio do resultado em formato JSON */
echo json_encode($result);

/** Encerra o procedimento */
exit;
