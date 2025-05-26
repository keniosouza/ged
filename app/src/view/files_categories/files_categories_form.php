<?php

/** Importação de classes */

use src\model\FilesCategories;
use src\model\FilesTypes;
use src\controller\files_categories\FilesCategoriesValidate;

try {

  /** Instânciamento de classes */
  $FilesTypes = new FilesTypes();
  $FilesCategories = new FilesCategories();
  $FilesCategoriesValidate = new FilesCategoriesValidate();

  /** Paametros de entrada */
  $fileCategoryId = isset($_POST['file_category_id']) ? (int) filter_input(INPUT_POST, 'file_category_id', FILTER_SANITIZE_NUMBER_INT) : 0;

  /** Validando os campos de entrada */
  $FilesCategoriesValidate->setFileCategoryId($fileCategoryId);

  /** Busco o registro desejado */
  $FilesCategoriesGetResult = $FilesCategories->Get($FilesCategoriesValidate->getFileCategoryId());

  /** Verifica se o objeto foi carregado,
   * caso não tenha sido, cria o mesmo vazio
   */
  if (!is_object($FilesCategoriesGetResult)) {

    /** Monta o objeto de acordo com a tabela */
    $FilesCategoriesGetResult = $FilesCategories->Describe();
  }

?>

  <form id="FilesCategoriesForm" class="row g-2">

    <div class="col-md-12" id="FilesCategoriesFormResponse"></div>

    <div class="col-md-12">

      <div class="form-floating">

        <input type="text" class="form-control" id="description" name="description" value="<?php echo (string) $FilesCategoriesGetResult->description ?>">
        <label for="description">Descrição</label>

      </div>

    </div>

    <div class="col-md-6">

      <div class="form-floating">

        <select class="form-select" id="file_type_id" name="file_type_id">

          <?php

          /** Percorro todos os itens localizados */
          foreach ($FilesTypes->All(null, null, null) as $key => $result) { ?>

            <option value="<?php echo $result->file_type_id ?>" <?php echo $result->file_type_id === $FilesCategoriesGetResult->file_type_id ? 'selected' : null ?>>

              <?php echo $result->description ?>

            </option>

          <?php } ?>

        </select>

        <label for="file_type_id">Tipo Arquivo</label>

      </div>

    </div>

    <div class="col-md-6">

      <div class="form-floating">


        <select class="form-select" id="status" name="status">


          <option value="A" <?php echo $result->status === 'A' ? 'selected' : null ?>>

            Ativo

          </option>

          <option value="I" <?php echo $result->status === 'I' ? 'selected' : null ?>>

            Inativo

          </option>

          <option value="D" <?php echo $result->status === 'D' ? 'selected' : null ?>>

            Remover

          </option>

        </select>

        <label for="floatingPosition">Situação</label>

      </div>

    </div>

    <div class="row">

      <div class="col-md-12 text-center p-2">

        <button class="btn btn-primary m-1 float-end" id="btnFilesCategories" onclick='new Request({"request": {"path" : "action/files_categories/files_categories_save"}, 
                                                                                           "loader" : {"type" : 1, "target" : "btnFilesCategories"},  
                                                                                           "form" : "FilesCategoriesForm", 
                                                                                           "response" : {"target" : "FilesCategoriesFormResponse"}})' type="button">

          <i class="bi bi-check me-1"></i>Salvar

        </button>

        <button class="btn btn-danger m-1 float-end" data-bs-dismiss="modal" aria-label="Close" type="button">

          <i class="bi bi-x me-1"></i>Cancelar

        </button>

      </div>

      <input type="hidden" name="csrf_token" value="<?php echo $Main->getCSRF(); ?>">
      <input type="hidden" name="file_category_id" value="<?php echo (int) $FilesCategoriesGetResult->file_category_id ?>">

    </div>

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
        'title' => $FilesCategoriesValidate->getFileCategoryId() > 0 ? 'Editando Cadastro' : 'Cadastrar Categoria',
        'data' => $data,
        'size' => 'lg',
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
