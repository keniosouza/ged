<?php

/** Importação de classes */

use src\model\Companies;
use src\model\FilesTypes;
use src\controller\files_types\FilesTypesValidate;


try {

  /** Instânciamento de classes */
  $Companies = new Companies();
  $FilesTypes = new FilesTypes();
  $FilesTypesValidate = new FilesTypesValidate();

  /** Parametros de entrada */
  $filesTypeId = isset($_POST['file_type_id']) ? (int) filter_input(INPUT_POST, 'file_type_id', FILTER_SANITIZE_NUMBER_INT) : 0;
  $companyId   = isset($_POST['company_id'])   ? (int) filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_NUMBER_INT)   : 0;

  /** Sanitiza os parametros de entrada */
  $FilesTypesValidate->setFileTypeId($filesTypeId);
  $FilesTypesValidate->setCompanyId($companyId);

  /** Busco o registro desejado */
  $FilesTypesGetResult = $FilesTypes->Get($FilesTypesValidate->getFileTypeId());

  /** Verifica se o objeto foi carregado,
   * caso não tenha sido, cria o mesmo vazio
   */
  if (!is_object($FilesTypesGetResult)) {

    /** Monta o objeto de acordo com a tabela */
    $FilesTypesGetResult = $FilesTypes->Describe();
  }

?>

  <form id="FilesTypesForm" class="row g-2" action="javascript:void">

    <div class="col-md-12" id="FilesTypesFormResponse"></div>

    <?php

    // Verifico se já existe a identificação da empresa, caso sim, ingora a seleção
    if ($companyId === 0) { ?>

      <div class="col-md-12">

        <select class="form-select" id="company_id" name="company_id">

          <?php

          /** Percorro todos os itens localizados */
          foreach ($Companies->All(null, null, null) as $key => $result) { ?>

            <option value="<?php echo $result->company_id ?>" <?php echo $result->company_id === $FilesTypesGetResult->company_id ? 'selected' : null ?>>

              <?php echo $result->name_business ?>

            </option>

          <?php } ?>

        </select>

      </div>

    <?php } ?>

    <div class="col-md-9">

      <div class="form-floating">

        <input type="text" class="form-control" id="description" name="description" value="<?php echo (string) $FilesTypesGetResult->description ?>">
        <label for="description">Descrição</label>

      </div>

    </div>
    <div class="col-md-3">

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

        <button class="btn btn-primary m-1 float-end" id="btnfiles_typesSave" onclick='new Request({"request" : {"path" : "action/files_types/files_types_save"}, 
                                                                                           "loader" : {"type" : 1, "target" : "btnfiles_typesSave"}, 
                                                                                           "response" : {"target" : "FilesTypesFormResponse"}, 
                                                                                           "form" : "FilesTypesForm"})' type="button">

          <i class="bi bi-check me-1"></i>Salvar

        </button>

        <button class="btn btn-danger m-1 float-end" data-bs-dismiss="modal" aria-label="Close" type="button">

          <i class="bi bi-x me-1"></i>Cancelar

        </button>

      </div>
    </div>

    <input type="hidden" name="csrf_token" value="<?php echo $Main->getCSRF(); ?>">
    <input type="hidden" name="file_type_id" value="<?php echo (int) $FilesTypesGetResult->file_type_id ?>">

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
        'title' => $FilesTypesValidate->getFileTypeId() > 0 ? 'Editando Cadastro' : 'Cadastrar Tipo de Arquivo',
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
