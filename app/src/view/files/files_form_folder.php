<?php

// Importação de classes
use src\model\Folders;
use src\model\Files;

// Instânciamento de classe
$Folders = new Folders();
$Files = new Files();

/** Busco o registro desejado */
$FilesGetResult = $Files->Get((int)filter_input(INPUT_POST, 'file_id', FILTER_SANITIZE_NUMBER_INT)); ?>

    <form id="FilesFormFolder" class="row g-2">

        <div class="col-md-12" id="FilesFormFolderResponse"></div>

        <div class="col-md-12">

            <select class="form-select" id="folder_id" name="folder_id">

                <option value="0">

                    Selecione

                </option>

                <?php

                /** Percorro todos os itens localizados */
                foreach ($Folders->All() as $key => $result) { ?>

                    <option value="<?php echo $result->folder_id ?>" <?php echo $result->folder_id === $FilesGetResult->folder_id ? 'selected' : null ?>>

                        <?php echo $result->name ?>

                    </option>

                <?php } ?>

            </select>

        </div>

        <div class="col-md-12 text-end">

            <button class="btn btn-primary btn-sm" onclick='new Request({"request" : {"path" : "action/files/files_save_folder"}, "response" : {"target" : "FilesFormFolderResponse"}, "form" : "FilesFormFolder"})' type="button">

                <i class="bi bi-check me-1"></i>Salvar

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
