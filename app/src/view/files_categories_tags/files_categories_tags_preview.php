<?php

/** Importação de classes */

use src\model\Files;
use src\model\FilesCategories;
use src\model\FilesCategoriesTags;
use src\controller\files_categories_tags\FilesCategoriesTagsValidate;
use src\controller\files_categories_tags\FilesCategoriesTagsHandling;

try {

    /** Instânciamento de classes */
    $Files = new Files();
    $FilesCategories = new FilesCategories();
    $FilesCategoriesTags = new FilesCategoriesTags();
    $FilesCategoriesTagsValidate = new FilesCategoriesTagsValidate();
    $FilesCategoriesTagsHandling = new FilesCategoriesTagsHandling();

    /** Parametros de entrada */
    $fileCategoryId = isset($_POST['file_category_id']) ? (int)filter_input(INPUT_POST, 'file_category_id', FILTER_SANITIZE_SPECIAL_CHARS) : 0;
    $fileId         = isset($_POST['file_id'])          ? (int)filter_input(INPUT_POST, 'file_id', FILTER_SANITIZE_SPECIAL_CHARS)          : 0;

    /** Sanitiza os parametros de entrada */
    $FilesCategoriesTagsValidate->setFileCategoryId($fileCategoryId);
    $FilesCategoriesTagsValidate->setFileId($fileId);

    /** Controles */
    $col = null;

    // Busco as marcações de acordo com a categoria
    $FilesCategoriesTagsAllByCategoryId = $FilesCategoriesTags->AllByCategoryId($FilesCategoriesTagsValidate->getFileCategoryId());

    // Busco um determinado registro
    $FilesGetResult = $Files->Get($FilesCategoriesTagsValidate->getFileId());

    /** Converte em objeto as tags */
    $tags = isset($FilesGetResult->tags) ? json_decode($FilesGetResult->tags) : null;

?>

    <form id="FilesTagsForm">

        <div class="row g-2 mt-1">

            <?php

            // Percorro todos os registros
            foreach ($FilesCategoriesTagsAllByCategoryId as $key => $result) {

                // Converto os dados para objeto
                $result->preferences = (object)json_decode($result->preferences, true);

                /** Gerencia o tamanho das colunas */
                if ((int) $result->preferences->quantity <= 90) {

                    $col = 3;
                } else if (((int) $result->preferences->quantity > 90) && ((int) $result->preferences->quantity <= 160)) {

                    $col = 4;
                } else if (((int) $result->preferences->quantity > 160) && ((int) $result->preferences->quantity <= 220)) {

                    $col = 6;
                } else {

                    $col = 8;
                }

            ?>

                <div class="col-md-<?php echo (int) $col; ?>">

                    <div class="form-floating">
                        <input type="hidden" name="required[]" value="<?php echo (int) $result->preferences->required ?>">
                        <input type="text"
                            class="form-control <?php echo $FilesCategoriesTagsHandling->dictionary((int) $result->preferences->format) ?>"
                            maxlength="<?php echo (int) $result->preferences->quantity; ?>"
                            id="tag_<?php echo $result->file_category_id ?>"
                            name="tags[<?php echo $result->preferences->name ?>]"
                            value="<?php echo !empty($tags->{$result->preferences->name}) ? $tags->{$result->preferences->name} : ''; ?>">
                        <label for="tag_<?php echo $result->file_category_id ?>">

                            <?php echo $result->preferences->name . ((int) $result->preferences->required == 1 ? '<span class="text-danger ms-2 fs-6 fst-italic">(Requerido)</span>' : null) ?>

                        </label>

                    </div>

                </div>

            <?php } ?>

            <div class="row">

                <div class="col-md-12 text-center p-2">

                    <button class="btn btn-primary m-1 float-end" id="btnFilesTagsForm" type="button" onclick='new Request({"request" : {"path" : "action/files/files_save_tags"},
                                                                                                          "loader": {"type": 1, "target": "btnFilesTagsForm"}, 
                                                                                                          "form" : "FilesTagsForm"});'>

                        <i class="bi bi-check me-1"></i>Salvar

                    </button>

                    <button class="btn btn-danger m-1 float-end" data-bs-dismiss="modal" aria-label="Close" type="button">

                        <i class="bi bi-x me-1"></i>Cancelar

                    </button>

                </div>

            </div>

            <input type="hidden" name="csrf_token" value="<?php echo $Main->getCSRF(); ?>">
            <input type="hidden" name="file_id" value="<?php echo $fileId; ?>">
            <input type="hidden" name="file_category_id" id="file_category_id" value="<?php echo $fileCategoryId; ?>">

        </div>

    </form>

    <script>
        loadMask();
    </script>

<?php
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
