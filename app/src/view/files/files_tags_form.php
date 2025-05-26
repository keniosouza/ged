<?php

// Importação de classes
use src\model\Files;
use src\model\FilesCategories;
use src\controller\main\Main;
use src\controller\files_categories_tags\FilesCategoriesTagsHandling;

try {

    // Instânciamento de classes
    $Main = new Main();
    $Files = new Files();
    $FilesCategories = new FilesCategories();
    $FilesCategoriesTagsHandling = new FilesCategoriesTagsHandling();

    // Busco um determinado registro
    $FilesGetResult = $Files->Get(filter_input(INPUT_POST, 'file_id', FILTER_SANITIZE_SPECIAL_CHARS));

    // Decodifico as preferencias para um objeto
    $FilesGetResult->tags = (object) json_decode($FilesGetResult->tags);

?>

    <form id="FilesCategoriesForm">

        <div class="form-floating">

            <select class="form-select" id="file_category_id" name="file_category_id" onchange=''>

                <option>

                    Selecione

                </option>

                <?php

                /** Percorro todos os itens localizados */
                foreach ($FilesCategories->All() as $key => $result) { ?>

                    <option value="<?php echo $result->file_category_id ?>" <?php echo $result->file_category_id === $UsersGetResult->file_category_id ? 'selected' : null ?>>

                        <?php echo $result->description ?>

                    </option>

                <?php } ?>

            </select>

            <label for="file_category_id">

                Categoria

            </label>

        </div>

        <input type="hidden" name="file_id" value="<?php echo $FilesGetResult->file_id; ?>">

    </form>

    <div id="FilesCategoriesTagsWrapper">

        <form id="FilesTagsForm">

            <?php

            // Percorro todos os registros
            foreach ($FilesGetResult->tags as $key => $result) { ?>

                <div class="col-md-3">

                    <div class="form-floating">

                        <input type="text" class="form-control <?php echo $FilesCategoriesTagsHandling->dictionary((int) $result->preferences->format) ?>" maxlength="<?php echo (int) $result->preferences->quantity; ?>" id="tag_<?php echo $key ?>" name="tags[<?php echo $key ?>]" placeholder="<?php echo $key ?>" value="<?php echo $result ?>">
                        <label for="tag_<?php echo $key ?>">

                            <?php echo $key ?>

                        </label>

                    </div>

                </div>

            <?php } ?>

            <div class="col-md-12 text-end">

                <button class="btn btn-primary" type="button" id="btnFilesTagsForm" onclick='new Request({"request" : {"path" : "action/files/files_save_tags"},
                                                                                                          "loader": {"type": 1, "target": "btnFilesTagsForm"}, 
                                                                                                          "form" : "FilesTagsForm"});'>

                    Salvar

                </button>

            </div>
            
        </form>

    </div>


    <input type="hidden" name="file_id" value="<?php echo $fileId ?>">

    </form>

    <script type="text/javascript">
        $(document).ready(function() {

            $('#file_category_id').change(function() {

                if ($('#file_category_id').val() > 0) {

                    new Request({
                        "request": {
                            "path": "view/files_categories_tags/files_categories_tags_preview"
                        },
                        "form": "FilesCategoriesForm",
                        "loader": {
                            "type": 1,
                            "target": "FilesCategoriesTagsWrapper"
                        },
                        "response": {
                            "target": "FilesCategoriesTagsWrapper"
                        }
                    })
                }
            })
        })
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
