<?php

// Importação de classes
use src\model\Files;
use src\controller\main\Main;
use src\model\FilesCategories;
use src\controller\files\FilesValidate;

try {

    // Instânciamento de classes
    $Main = new Main();
    $Files = new Files();
    $FilesValidate = new FilesValidate();
    $FilesCategories = new FilesCategories();

    /** Parametros de entrada */
    $fileId = isset($_POST['file_id']) ? (int)filter_input(INPUT_POST, 'file_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Sanitiza os parametros de entrada */
    $FilesValidate->setFileId($fileId);

    // Busco um determinado registro
    $FilesGetResult = $Files->Get($FilesValidate->getFileId());

    // Verifico o tipo exibição
    if ($FilesGetResult->extension == 'jpg' || $FilesGetResult->extension == 'png') { ?>

        <img src="<?php echo $FilesGetResult->path .  $FilesGetResult->name ?>" class="img-fluid">

    <?php } else { ?>

        <iframe class="w-100 rounded border" style="min-height: 400px;" src="<?php echo $FilesGetResult->path . $FilesGetResult->name ?>" frameborder="0"></iframe>

    <?php } ?>

    <div class="fs-5 fw-bold mb-1">

        Tags de Indexação

    </div>



    <form id="FilesCategoriesForm">

        <div class="form-floating">

            <select class="form-select" id="file_category_id" name="file_category_id" onchange=''>

                <option>

                    Selecione

                </option>

                <?php

                /** Percorro todos os itens localizados */
                foreach ($FilesCategories->Select($FilesGetResult->category_id) as $key => $result) { ?>

                    <option value="<?php echo $result->file_category_id ?>" <?php echo $result->file_category_id === $FilesGetResult->file_category_id ? 'selected' : null ?>>

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

    <div id="FilesCategoriesTagsWrapper" class="text-center p-2"></div>


    <script type="text/javascript">
        $(document).ready(function() {

            /** Adiciona a category_id */
            document.getElementById("file_category_id").value = $('#file_category_id').val();

            /** Envia a solicitação para 
             * carregar os dados da categoria */
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

            /** Dispara a ação ao selecionar a categoria */
            $('#file_category_id').change(function() {

                if ($('#file_category_id').val() > 0) {

                    /** Adiciona a category_id */
                    document.getElementById("file_category_id").value = $('#file_category_id').val();

                    /** Envia a solicitação para 
                     * carregar os dados da categoria */
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

    /** Prego a estrutura do arquivo */
    $data = ob_get_contents();

    /** Removo o arquivo incluido */
    ob_clean();

    // Result
    $result = array(

        'code' => 200,
        'modal' => [
            [
                'title' => 'Arquivo / Visualização',
                'data' => $data,
                'size' => 'xl',
                'type' => null,
                'procedure' => null,
            ]
        ],

    );

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
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
