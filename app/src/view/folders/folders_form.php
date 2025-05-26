<?php

/** Importação de classes */
use src\model\Folders;

/** Instânciamento de classes */
$Folders = new Folders();

// Parametros de entrada
$view = filter_input(INPUT_POST, 'view', FILTER_SANITIZE_SPECIAL_CHARS);

/** Busco o registro desejado */
$FoldersGetResult = $Folders->Get((int) filter_input(INPUT_POST, 'folder_id', FILTER_SANITIZE_NUMBER_INT));?>

<form id="FoldersForm" class="row g-2">

  <div class="col-md-12" id="FoldersFormResponse"></div>

  <div class="col-md-12">

    <div class="form-floating">

      <input type="email" class="form-control" id="name" name="name" value="<?php echo (string) $FoldersGetResult->name ?>" placeholder="Nome">
      <label for="name">Nome</label>

    </div>

  </div>

  <div class="col-md-12">

    <div class="form-floating">

      <input type="text" class="form-control" id="description" name="description" value="<?php echo (string) $FoldersGetResult->description ?>" placeholder="Descrição">
      <label for="description">Descrição</label>

    </div>

  </div>

  <div class="col-md-12 text-end d-flex justify-content-between align-items-start">

    <?php
    
    if ($FoldersGetResult->folder_id > 0) { ?>

      <button class="btn btn-danger btn-sm" onclick='new Request({"request": {"path" : "action/folders/folders_delete"},  "form" : "FoldersForm", "response" : {"target" : "FoldersFormResponse"}})' type="button">

          <i class="bi bi-x-lg me-1"></i>Remover

      </button>

    <?php }?>

    <button class="btn btn-primary btn-sm" onclick='new Request({"request": {"path" : "action/folders/folders_save"},  "form" : "FoldersForm", "response" : {"target" : "FoldersFormResponse"}})' type="button">

        <i class="bi bi-check me-1"></i>Salvar

    </button>

  </div>

  <input type="hidden" name="folder_id" value="<?php echo (int) $FoldersGetResult->folder_id ?>">
  <input type="hidden" name="view" value="<?php echo (int) $view ?>">

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
            'title' => 'Pastas / Visão Geral / Formulário',
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
