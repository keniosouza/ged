<?php

// Importação de classes
use src\model\Files;

// Instânciamento de classe
$Files = new Files();

/** Busco o registro desejado */
$FilesGetResult = $Files->Get((int)filter_input(INPUT_POST, 'file_id', FILTER_SANITIZE_NUMBER_INT)); ?>

    <form id="FilesFormFolder" class="row g-2">

        <div class="col-md-12" id="FilesFormFolderResponse"></div>

        <div class="col-md-12">

            <div class="form-floating">

                <input type="text" class="form-control" id="name" name="name" value="<?php echo (string) $FilesGetResult->name ?>">
                <label for="name">Nome</label>

            </div>

        </div>

        <div class="col-md-12">

            <div class="form-floating">

                <input type="text" class="form-control" id="description" name="description" value="<?php echo (string) $FilesGetResult->description ?>">
                <label for="description">Descrição</label>

            </div>

        </div>

        <div class="col-md-12 text-end">

            <button class="btn btn-primary btn-sm" onclick='new Request({"request" : {"path" : "action/files/files_save"}, "response" : {"target" : "FilesFormFolderResponse"}, "form" : "FilesFormFolder"})' type="button">

                <i class="bi bi-check-lg me-1"></i>Salvar

            </button>

        </div>

        <input type="hidden" name="file_id" value="<?php echo (int)$FilesGetResult->file_id ?>">

    </form>

<?php

/** Prego a estrutura do arquivo */
$data = ob_get_contents();

/** Removo o arquivo incluido */
ob_clean();

// Result
$result = array(

    'code' => 200,
    'modal' => [
        [
            'title' => 'Arquivos / Visão Geral / Pasta',
            'data' => $data,
            'size' => 'md',
            'type' => null,
            'procedure' => null,
        ]
    ],

);

/** Envio **/
echo json_encode($result);

/** Paro o procedimento **/
exit;
