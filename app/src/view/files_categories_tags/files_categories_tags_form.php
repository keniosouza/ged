<?php

/** Importação de classes */

use src\model\FilesTypes;
use src\model\FilesCategories;
use src\model\FilesCategoriesTags;
use src\controller\files_categories_tags\FilesCategoriesTagsValidate;


try {

    /** Instânciamento de classes */
    $FilesCategoriesTagsValidate = new FilesCategoriesTagsValidate();
    $FilesCategoriesTags = new FilesCategoriesTags();
    $FilesCategories = new FilesCategories();
    $FilesTypes = new FilesTypes();

    /** Parametros de entrada */
    $fileCategoryTagId = isset($_POST['file_category_tag_id']) ? (int)filter_input(INPUT_POST, 'file_category_tag_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Validando os campos de entrada */
    $FilesCategoriesTagsValidate->setFileCategoryTagId($fileCategoryTagId);

    /** Busco o registro desejado */
    $FilesCategoriesTagsGetResult = $FilesCategoriesTags->Get($FilesCategoriesTagsValidate->getFileCategoryTagId());

    /** Verifica se o objeto foi carregado,
     * caso não tenha sido, cria o mesmo vazio
     */
    if (!is_object($FilesCategoriesTagsGetResult)) {

        /** Monta o objeto de acordo com a tabela */
        $FilesCategoriesTagsGetResult = $FilesCategoriesTags->Describe();
    }

    /** Verifica se o objeto de preferências esta carregado */
    if (!empty($FilesCategoriesTagsGetResult->preferences)) {

        // Carrega as preferência em um objeto
        $FilesCategoriesTagsGetResult->preferences = (object) json_decode($FilesCategoriesTagsGetResult->preferences);

        /** Caso o objeto de preferências não 
         * esteja carregado, gero o mesmo com os 
         * itens vazios */
    } else {

        $FilesCategoriesTagsGetResult->preferences = new stdClass();
        $FilesCategoriesTagsGetResult->preferences->name = null;
        $FilesCategoriesTagsGetResult->preferences->quantity = null;
        $FilesCategoriesTagsGetResult->preferences->format = null;
        $FilesCategoriesTagsGetResult->preferences->required = null;
    }


?>

    <form id="FilesCategoriesTagsForm" class="row g-2">

        <div class="col-md-12" id="FilesCategoriesTagsFormResponse"></div>

        <div class="col-md-3">

            <div class="form-floating">


                <select class="form-select" id="file_type_id" name="file_type_id">

                    <option>

                        Selecione

                    </option>

                    <?php

                    /** Percorro todos os itens localizados */
                    foreach ($FilesTypes->All(null, null, null) as $key => $result) { ?>

                        <option value="<?php echo $result->file_type_id ?>">

                            <?php echo $result->description ?>

                        </option>

                    <?php } ?>

                </select>

                <label for="floatingPosition">Tipo Arquivo</label>

            </div>

        </div>

        <div class="col-md-6">

            <div class="form-floating" id="targetCategoryId">

                <select class="form-select" id="file_category_id" name="file_category_id">


                    <option>

                        Selecione o tipo de arquivo

                    </option>

                </select>

                <label for="file_category_id">

                    Categoria

                </label>

            </div>

        </div>

        <div class="col-md-3">

            <div class="form-floating">


                <select class="form-select" id="status" name="status">


                    <option value="A" <?php echo $FilesCategoriesTagsGetResult->status === 'A' ? 'selected' : null ?>>

                        Ativo

                    </option>

                    <option value="I" <?php echo $FilesCategoriesTagsGetResult->status === 'I' ? 'selected' : null ?>>

                        Inativo

                    </option>

                    <option value="D" <?php echo $FilesCategoriesTagsGetResult->status === 'D' ? 'selected' : null ?>>

                        Remover

                    </option>

                </select>

                <label for="floatingPosition">Situação</label>

            </div>

        </div>

        <div class="col-md-12">

            <div class="form-floating">

                <input type="text" class="form-control" id="description" name="description" value="<?php echo (string) $FilesCategoriesTagsGetResult->description ?>">
                <label for="description">Descrição</label>

            </div>

        </div>

        <div class="col-md-3">

            <div class="form-floating">

                <input type="text" class="form-control" id="name" name="preferences[name]" value="<?php echo (string) $FilesCategoriesTagsGetResult->preferences->name ?>">
                <label for="name">Nome</label>

            </div>

        </div>

        <div class="col-md-3">

            <div class="form-floating">

                <input type="number" class="form-control" id="quantity" name="preferences[quantity]" value="<?php echo (string) $FilesCategoriesTagsGetResult->preferences->quantity ?>">
                <label for="quantity">Qtd. Letras/Numero</label>

            </div>

        </div>

        <div class="col-md-3">

            <div class="form-floating">

                <select class="form-select" id="format" name="preferences[format]">

                    <option value="1" <?php echo (int) $FilesCategoriesTagsGetResult->preferences->format === 1 ? 'selected' : null; ?>>

                        Texto

                    </option>

                    <option value="2" <?php echo (int) $FilesCategoriesTagsGetResult->preferences->format === 2 ? 'selected' : null; ?>>

                        Numero

                    </option>

                    <option value="3" <?php echo (int) $FilesCategoriesTagsGetResult->preferences->format === 3 ? 'selected' : null; ?>>

                        Data

                    </option>

                    <option value="4" <?php echo (int) $FilesCategoriesTagsGetResult->preferences->format === 4 ? 'selected' : null; ?>>

                        Monetario

                    </option>

                    <option value="5" <?php echo (int) $FilesCategoriesTagsGetResult->preferences->format === 5 ? 'selected' : null; ?>>

                        CPF

                    </option>

                    <option value="6" <?php echo (int) $FilesCategoriesTagsGetResult->preferences->format === 6 ? 'selected' : null; ?>>

                        CNPJ

                    </option>

                    <option value="7" <?php echo (int) $FilesCategoriesTagsGetResult->preferences->format === 7 ? 'selected' : null; ?>>

                        CEP

                    </option>

                    <option value="8" <?php echo (int) $FilesCategoriesTagsGetResult->preferences->format === 8 ? 'selected' : null; ?>>

                        Celular

                    </option>

                    <option value="9" <?php echo (int) $FilesCategoriesTagsGetResult->preferences->format === 9 ? 'selected' : null; ?>>

                        Email

                    </option>

                    <option value="10" <?php echo (int) $FilesCategoriesTagsGetResult->preferences->format === 10 ? 'selected' : null; ?>>

                        RG

                    </option>

                </select>

                <label for="format">

                    Formato

                </label>

            </div>

        </div>

        <div class="col-md-3">

            <div class="form-floating">

                <select class="form-select" id="required" name="preferences[required]">

                    <option value="1" <?php echo $FilesCategoriesTagsGetResult->preferences->required === 1 ? 'selected' : null; ?>>

                        Sim

                    </option>

                    <option value="2" <?php echo $FilesCategoriesTagsGetResult->preferences->required === 2 ? 'selected' : null; ?>>

                        Não

                    </option>

                </select>

                <label for="required">

                    Obrigatório

                </label>

            </div>

        </div>

        <div class="row">

            <div class="col-md-12 text-center p-2">

                <button class="btn btn-primary m-1 float-end" id="btnFilesCategoriesTags" onclick='new Request({"request": {"path" : "action/files_categories_tags/files_categories_tags_save"},
                                                                         "loader" : {"type" : 1, "target" : "btnFilesCategoriesTags"},  
                                                                         "form" : "FilesCategoriesTagsForm", 
                                                                         "response" : {"target" : "FilesCategoriesTagsFormResponse"}})' type="button">

                    <i class="bi bi-check me-1"></i>Salvar

                </button>

                <button class="btn btn-danger m-1 float-end" data-bs-dismiss="modal" aria-label="Close" type="button">

                    <i class="bi bi-x me-1"></i>Cancelar

                </button>

            </div>

            <input type="hidden" name="csrf_token" value="<?php echo $Main->getCSRF(); ?>">
            <input type="hidden" name="file_category_tag_id" value="<?php echo (int)$FilesCategoriesTagsGetResult->file_category_tag_id ?>">

        </div>

    </form>

    <script type="text/javascript">
        /** Operações ao carregar a página */
        $(document).ready(function(e) {

            /** Efetua a consulta ao selecionar o type */
            $('select[name="file_type_id"]').change(function() {

                if ($('select[name="file_type_id"]').val() > 0) {

                    $(this).prop("selected", true);

                    new Request({
                        "request": {
                            "path": "view/files_categories/files_categories_select"
                        },
                        "loader": {
                            "type": 1,
                            "target": "targetCategoryId"
                        },
                        "response": {
                            "target": "targetCategoryId"
                        },
                        "params": {
                            "file_type_id": $('select[name="file_type_id"]').val()
                        }
                    });
                }
            });

        });
    </script>

<?php

    /** Prego a estrutura do arquivo */
    $data = ob_get_contents();

    /** Removo o arquivo incluido */
    ob_clean();

    // Result
    $result = array(

        'code' => 200,
        'modal' => [[
            'title' => $FilesCategoriesTagsValidate->getFileCategoryTagId() > 0 ? 'Editando Cadastro' : 'Cadastrar Marcação',
            'data' => $data,
            'size' => 'lg',
            'type' => null,
            'procedure' => null
        ]]

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
