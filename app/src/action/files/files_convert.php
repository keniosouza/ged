<?php

/** Importação de classes */

use src\model\Files;
use src\controller\api\ApiHandling;
use src\controller\Files\FilesValidate;

try {

    /** Instânciamento de classes */
    $Files = new Files();
    $ApiHandling = new ApiHandling();
    $FilesValidate = new FilesValidate();

    /** Parametros de entrada */
    $fileId  = isset($_POST['file_id']) ? (int)filter_input(INPUT_POST, 'file_id', FILTER_SANITIZE_NUMBER_INT)       : 0;
    $convert = isset($_POST['convert']) ? (string)filter_input(INPUT_POST, 'convert', FILTER_SANITIZE_SPECIAL_CHARS) : '';

    /** Controle de resultados */
    $result = null;

    /** Validando os campos de entrada */
    $FilesValidate->setFileId($fileId);
    $FilesValidate->setConvert($convert);

    /** Verifico a existência de erros */
    if (!empty($FilesValidate->getErrors())) {

        /** Caso ocorra algum erro, lança uma exceção com a mensagem de erro. */
        throw new InvalidArgumentException($FilesValidate->getErrors(), 0);
    } else {

        // Busco o registro desejado
        $FilesGetResult = $Files->Get($FilesValidate->getFileId());

        // Verifico se o arquivo foi localizado
        if ($FilesGetResult->file_id > 0) {

            /** Verifica se a pasta de download existe, 
             * caso não exista, cria a mesma */
            if (!is_dir('storage/temp/download/')) {

                // A pasta não existe, vamos criar
                if (!mkdir('storage/temp/download/', 0755, true)) {
                    throw new Exception("Erro ao criar o diretório de visualização");
                }
            }


            switch ($FilesValidate->getConvert()) {

                /** Converte pdf para word */
                case 'pdf_to_word':

                    // Converto o arquivo para word
                    $ApiHandlingImageToWordResult = $ApiHandling->PdfToWord(
                        $ApiHandling->GetToken(),
                        base64_encode(file_get_contents($FilesGetResult->path . '/' . $FilesGetResult->name))
                    );

                    /** Gera o arquivo */
                    $fp = @fopen('storage/temp/download/' . $Main->RemoveExtension($FilesGetResult->name) . '.docx', 'w+');

                    // Verifico se o arquivo foi gerado
                    if (!$fp) {
                        throw new Exception('Erro ao gerar o arquivo');
                    }

                    /** Escreve o conteúdo do arquivo */
                    fwrite($fp, base64_decode($ApiHandlingImageToWordResult));
                    fclose($fp);

                    // Result
                    $result = [

                        'code' => 200,
                        'data' => 'Arquivo gerado com sucesso!',
                        'download' => [
                            [
                                'path' => './storage/temp/download/' . $Main->RemoveExtension($FilesGetResult->name) . '.docx',
                                'name' => $Main->RemoveExtension($FilesGetResult->name) . '.docx'
                            ]
                        ]

                    ];

                    break;

                /** Converte pdf para text */
                case 'pdf_to_text':

                    // Converto o arquivo para word
                    $ApiHandlingImageToWordResult = $ApiHandling->PdfToText(
                        $ApiHandling->GetToken(),
                        base64_encode(file_get_contents($FilesGetResult->path . '/' . $FilesGetResult->name))
                    );

                    /** Gera o arquivo */
                    $fp = @fopen('storage/temp/download/' . $Main->RemoveExtension($FilesGetResult->name) . '.txt', 'w+');

                    // Verifico se o arquivo foi gerado
                    if (!$fp) {
                        throw new Exception('Erro ao gerar o arquivo');
                    }

                    /** Escreve o conteúdo do arquivo */
                    fwrite($fp, base64_decode($ApiHandlingImageToWordResult));
                    fclose($fp);

                    // Result
                    $result = array(

                        'code' => 200,
                        'modal' => [
                            [
                                'title' => 'Visualizando Texto do Arquivo',
                                'data' => '<div class="form-floating"><textarea style="height: 400px;" class="form-control">' . file_get_contents('storage/temp/download/' . $Main->RemoveExtension($FilesGetResult->name) . '.txt') . '</textarea><label for="description">Texto Extraído</label></div>',
                                'size' => 'lg',
                                'type' => null,
                                'procedure' => null,
                            ]
                        ],

                    );

                    break;

                /** Converte imagem para word */
                case 'image_to_word':

                    // Converto o arquivo para word
                    $ApiHandlingImageToWordResult = $ApiHandling->ImageToWord(
                        $ApiHandling->GetToken(),
                        base64_encode(file_get_contents($FilesGetResult->path . '/' . $FilesGetResult->name))
                    );

                    /** Gera o arquivo */
                    $fp = @fopen('storage/temp/download/' . $Main->RemoveExtension($FilesGetResult->name) . '.docx', 'w+');

                    // Verifico se o arquivo foi gerado
                    if (!$fp) {
                        throw new Exception('Erro ao gerar o arquivo');
                    }

                    /** Escreve o conteúdo do arquivo */
                    fwrite($fp, base64_decode($ApiHandlingImageToWordResult));
                    fclose($fp);

                    // Result
                    $result = [

                        'code' => 200,
                        'data' => 'Arquivo gerado com sucesso!',
                        'download' => [
                            [
                                'path' => './storage/temp/download/' . $Main->RemoveExtension($FilesGetResult->name) . '.docx',
                                'name' => $Main->RemoveExtension($FilesGetResult->name) . '.docx'
                            ]
                        ]

                    ];

                    break;

                /** Converte pdf para text */
                case 'image_to_text':

                    // Converto o arquivo para word
                    $ApiHandlingImageToWordResult = $ApiHandling->ImageToText(
                        $ApiHandling->GetToken(),
                        base64_encode(file_get_contents($FilesGetResult->path . '/' . $FilesGetResult->name))
                    );

                    /** Gera o arquivo */
                    $fp = @fopen('storage/temp/download/' . $Main->RemoveExtension($FilesGetResult->name) . '.txt', 'w+');

                    // Verifico se o arquivo foi gerado
                    if (!$fp) {
                        throw new Exception('Erro ao gerar o arquivo');
                    }

                    /** Escreve o conteúdo do arquivo */
                    fwrite($fp, base64_decode($ApiHandlingImageToWordResult));
                    fclose($fp);

                    // Result
                    $result = array(

                        'code' => 200,
                        'modal' => [
                            [
                                'title' => 'Visualizando Texto do Arquivo',
                                'data' => '<div class="form-floating"><textarea style="height: 400px;" class="form-control">' . file_get_contents('storage/temp/download/' . $Main->RemoveExtension($FilesGetResult->name) . '.txt') . '</textarea><label for="description">Texto Extraído</label></div>',
                                'size' => 'lg',
                                'type' => null,
                                'procedure' => null,
                            ]
                        ],

                    );

                    break;
            }
        } else {

            // Result
            $result = [

                'code' => 0,
                'data' => 'Arquivo não localizado',

            ];
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
