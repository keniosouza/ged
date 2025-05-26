<?php

/** Importação de classes */

use src\model\Companies;
use src\controller\companies\CompaniesValidate;

try {

  /** Instânciamento de classes */
  $Companies = new Companies();
  $CompaniesValidate = new CompaniesValidate();

  /** Parametros de entrada */
  $companyId = isset($_POST['company_id']) ? (int)filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_NUMBER_INT) : 0;

  /** Sanitiza os parametros de entrada */
  $CompaniesValidate->setCompanyId($companyId);

  /** Busco o registro desejado */
  $CompaniesGetResult = $Companies->Get($CompaniesValidate->getCompanyId());

  /** Verifica se o objeto foi carregado,
   * caso não tenha sido, cria o mesmo vazio
   */
  if (!is_object($CompaniesGetResult)) {

    $CompaniesGetResult = $Companies->Describe();
  }

?>

  <form id="CompaniesForm" class="row g-2">

    <div class="col-md-12" id="CompaniesFormResponse"></div>

    <div class="col-md-12">

      <div class="form-floating">

        <input type="email" class="form-control" id="name_business" name="name_business" value="<?php echo (string) $CompaniesGetResult->name_business ?>">
        <label for="name_business">Nome Empresarial</label>

      </div>

    </div>

    <div class="col-md-12">

      <div class="form-floating">

        <input type="text" class="form-control" id="name_fantasy" name="name_fantasy" value="<?php echo (string) $CompaniesGetResult->name_fantasy ?>">
        <label for="name_fantasy">Nome Fantasia</label>

      </div>

    </div>

    <div class="col-md-3">

      <div class="form-floating">


        <select class="form-select" id="status" name="status">


          <option value="A" <?php echo $CompaniesGetResult->status === 'A' ? 'selected' : null ?>>

            Ativo

          </option>

          <option value="I" <?php echo $CompaniesGetResult->status === 'I' ? 'selected' : null ?>>

            Inativo

          </option>

          <option value="D" <?php echo $CompaniesGetResult->status === 'D' ? 'selected' : null ?>>

            Remover

          </option>

        </select>

        <label for="floatingPosition">Situação</label>

      </div>

    </div>

    <div class="row">

      <div class="col-md-12 text-center p-2">

        <button class="btn btn-primary m-1 float-end" id="btnCompaniesSave" onclick='new Request({"request" : {"path" : "action/companies/companies_save"}, 
                                                                                         "loader" : {"type" : 1, "target" : "btnCompaniesSave"}, 
                                                                                         "response" : {"target" : "CompaniesFormResponse"}, 
                                                                                         "form" : "CompaniesForm"})' type="button">

          <i class="bi bi-check me-1"></i>Salvar

        </button>

        <button class="btn btn-danger m-1 float-end" data-bs-dismiss="modal" aria-label="Close" type="button">

          <i class="bi bi-x me-1"></i>Cancelar

        </button>



      </div>

      <input type="hidden" name="csrf_token" value="<?php echo $Main->getCSRF(); ?>">
      <input type="hidden" name="company_id" value="<?php echo (int) $CompaniesGetResult->company_id ?>">

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
        'title' => $CompaniesValidate->getCompanyId() > 0 ? 'Editando Cadastro' : 'Cadastrar Empresa',
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
