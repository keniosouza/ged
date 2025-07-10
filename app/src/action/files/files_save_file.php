<?php

/** Importação de classes  */

use src\controller\main\Main;
use src\model\Files;
use src\controller\files\FilesValidate;
use src\controller\files\FilesProcedures;

try {

    error_log("files_save_file.php - POST Data: " . print_r($_POST, true));

    /** Instânciamento de classes  */
    $Main = new Main();
    $Files = new Files();
    $FilesValidate = new FilesValidate();
    $FilesProcedures = new FilesProcedures();

    /** Parametros de entrada */
    $fileId  = isset($_POST['file_id'])  ? (int)filter_input(INPUT_POST, 'file_id', FILTER_SANITIZE_SPECIAL_CHARS)  : 0;
    $batchId = isset($_POST['batch_id']) ? (int)filter_input(INPUT_POST, 'batch_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;
    $name    = isset($_POST['name'])     ? (string)filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS)  : '';

    /** Parâmetros de entrada dos ARQUIVOS */
    $hash = isset($_POST['hash']) ? $_POST['hash'] : '';

   $tempPath = 'storage/temp/' . $hash;
   error_log("files_save_file.php - Temp Path: " . $tempPath);
   error_log("files_save_file.php - Temp Path Exists: " . (is_dir($tempPath) ? 'Yes' : 'No'));
   error_log("files_save_file.php - Temp Path Contents: " . (is_dir($tempPath) ? print_r(scandir($tempPath), true) : 'Directory does not exist'));     

    /** Define o local do arquivo e realiza validações */
    $FilesValidate->setFileId($fileId);
    $FilesValidate->setBatchId($batchId);
    $FilesValidate->setCompanyId($_SESSION['MY_SAAS_USER']->company_id);
    $FilesValidate->setTable('files');
    $FilesValidate->setName($name);
    $FilesValidate->setExtension($name);

    /** Verifica a existência de erros durante a validação */
    if (!empty($FilesValidate->getErrors())) {

        /** Caso ocorra algum erro, lança uma exceção com a mensagem de erro. */
        throw new InvalidArgumentException($FilesValidate->getErrors(), 0);
    } else {

        // Verifico se devo criar uma pasta de arquivos
        $path = $Main->GetNextDirectory('storage/files', '100') . '/';

        /** Defino o caminho do arquivo */
        $FilesValidate->setPath($path);

        /** Realizo a junção das partes do arquivo */
        $FilesProcedures->merge(
            'storage/temp/' . $hash,
            $FilesValidate->getName(),
            $FilesValidate->getPath()
        );
        error_log("files_save_file.php - Função merge finalizada.");

        /** Salvo o arquivo desejado */
        if ($Files->Save(
            $FilesValidate->getFileId(),
            $FilesValidate->getCompanyId(),
            $FilesValidate->getBatchId(),
            $FilesValidate->getTable(),
            $FilesValidate->getName(),
            $FilesValidate->getExtension(),
            $FilesValidate->getPath()
        )) {

            // Result
            $result = [

                'code' => 200,
                'data' => 'Arquivo salvo com sucesso',
                'toast' => [
                    [
                        'background' => 'success',
                        'data' => '<i class="bi bi-check me-1"></i>Arquivo salvo com sucesso'
                    ]
                ],
                'end' => true, // Informa a finalização da operação
                'file_id' => $FilesValidate->getFileId()

            ];
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
