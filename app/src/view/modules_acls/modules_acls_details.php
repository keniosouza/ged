<?php

/** Importação de classes */

use src\model\Modules;

try {

  /** Instânciamento de classes */
  $Modules = new Modules();

  /** Parametros de entrada */
  $moduleId = isset($_POST['module_id']) ? (int)filter_input(INPUT_POST, 'module_id', FILTER_SANITIZE_NUMBER_INT) : 0;

  /** Busco o registro desejado */
  $ModulesGetResult = $Modules->Get($moduleId);

?>

  <div id="ModulesDetailsWrapper">

    <button class="btn btn-primary btn-sm w-100" data-route='{"request" : "view/modules_acls/modules_acls_form"}'>

      + Niveis de Acesso

    </button>

  </div>

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
        'title' => 'Módulos / Visão Geral / Formulário',
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
