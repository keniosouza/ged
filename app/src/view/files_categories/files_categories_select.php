<?php

/** Importação de classes */

use src\model\FilesCategories;
use src\controller\files_categories\FilesCategoriesValidate;


try {

    /** Instânciamento de classes */
    $FilesCategoriesValidate = new FilesCategoriesValidate();
    $FilesCategories = new FilesCategories();

    /** Parametros de entrada */
    $fileTypeId = isset($_POST['file_type_id']) ? (int)filter_input(INPUT_POST, 'file_type_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Sanitiza os itens enviados */
    $FilesCategoriesValidate->setFileTypeId($fileTypeId);

    /** Verifica se existem erros */
    if (!empty($FilesCategoriesValidate->getErrors())) {

        /** Informo */
        throw new InvalidArgumentException($FilesCategoriesValidate->getErrors(), 0);
    } else {


?>
        <select class="form-select" id="file_category_id" name="file_category_id">

            <?php

            /** Percorro todos os itens localizados */
            foreach ($FilesCategories->GetType($FilesCategoriesValidate->getFileTypeId()) as $key => $result) { ?>

                <option value="<?php echo $result->file_category_id ?>">

                    <?php echo $result->description ?>

                </option>

            <?php } ?>

        </select>

        <label for="file_category_id">

            Categoria

        </label>

<?php

    }

    /** Prego a estrutura do arquivo */
    $data = ob_get_contents();

    /** Removo o arquivo incluido */
    ob_clean();

    // Result
    $result = array(

        'code' => 200,
        'data' => $data
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
