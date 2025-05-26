<?php

/** Importação de classes */
use src\model\Files;
use src\controller\Files\FilesValidate;
use src\controller\api\ApiHandling;

/** Instânciamento de classes */
$Files = new Files();
$FilesValidate = new FilesValidate();
$ApiHandling = new ApiHandling();

/** Controle de resultados */
$result = null;

/** Validando os campos de entrada */
$FilesValidate->setFileId((int)filter_input(INPUT_POST, 'file_id', FILTER_SANITIZE_SPECIAL_CHARS));

/** Verifico a existência de erros */
if (!empty($FilesValidate->getErrors())) {

    // Result
    $result = [

        'code' => 0,
        'data' => $FilesValidate->getErrors(),

    ];

} else {

    // Busco o registro desejado
    $FilesGetResult = $Files->Get($FilesValidate->getFileId());

    // Verifico se o arquivo foi localizado
    if ($FilesGetResult->file_id > 0) {

        // Converto o arquivo para word
        $ApiHandlingImageToWordResult = $ApiHandling->PdfToText($ApiHandling->GetToken(), base64_encode(file_get_contents($FilesGetResult->path . '/' . $FilesGetResult->name)));

        // Atualizo o conteúdo do arquivo
        if ($Files->SaveContent($FilesGetResult->file_id, str_replace(' ', ' ', base64_decode($ApiHandlingImageToWordResult)))) {

            // Result
            $result = [

                'code' => 200,
                'data' => 'Arquivo atualizado com sucesso!',

            ];

        } else {

            // Result
            $result = [

                'code' => 0,
                'data' => 'Não foi possivel atualizar o arquivo',

            ];

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
