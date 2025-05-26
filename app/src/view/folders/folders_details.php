<?php

/** Importação de classes */
use src\controller\main\Main;
use src\model\Files;
use src\model\Folders;
use src\controller\folders\FoldersValidate;

/** Instânciamento de classes */
$Main = new Main();
$Files = new Files();
$Folders = new Folders();
$FoldersValidate = new FoldersValidate;

// Obetnho e trato os dados de entrada
$FoldersValidate->setFolderId(filter_input(INPUT_POST, 'folder_id', FILTER_SANITIZE_SPECIAL_CHARS));

/** Busco todos os usuários */
$FoldersGetResult = $Folders->Get($FoldersValidate->getFolderId());
$FilesAllByFolderIdResult = $Files->AllByFolderId($FoldersValidate->getFolderId());

?>

<div class="d-flex justify-content-between align-items-start mb-2">

  <div class="text-body">

    <h6 class="text-body-secondary fw-medium">

      Pasta /

    </h6>

    <h5 class="fw-bold text-body">

        <?php echo $FoldersGetResult->name ?>

    </h5>

  </div>

  <button class="btn btn-primary btn-sm" onclick='new Request({"request" : {"path" : "view/files/files_index"}})'>

      <i class="bi bi-x"></i>Fechar

  </button>

</div>

<table class="table table-borderless border align-items-center shadow-sm">

<thead>

  <tr>

    <th scope="col" class="text-center">

      #

    </th>

    <th scope="col">

      Nome/Email

    </th>

    <th scope="col" class="text-center">

      Operações

    </th>

  </tr>

</thead>

<tbody>

  <?php

  /** Percorro todos os itens localizados */
  foreach ($FilesAllByFolderIdResult as $key => $result) { ?>

    <tr class="align-middle border-top">

      <th scope="row" class="text-center">

        <?php echo $result->file_id ?>

      </th>

      <td>

        <div class="d-flex align-items-center">

          <div class="flex-shrink-0">

            <img src="<?php echo $Main->GetExtensionIcon($Main->getFileExtension($result->name)); ?>" width="40px" class="img-fluid">

          </div>

          <div class="flex-grow-1 ms-3">

            <div class="fs-6 fw-bold">

              <?php echo $result->name ?>

            </div>

            <div class="fs-6 fw-normal">

              01/01/2021 / <?php echo $Main->GetFileSizeFormated($result->path . '/' . $result->name)?>

            </div>

          </div>

        </div>

      </td>

      <td class="text-center">

        <div class="dropdown">

          <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">

            <i class="bi bi-three-dots"></i>
            
          </button>

          <ul class="dropdown-menu">

            <li>
              
              <a class="dropdown-item" href="#">
                
                Editar
              
              </a>
            
            </li>

            <li>
              
              <a class="dropdown-item" onclick='new OffCanva({"title" : "<?php echo $result->name ?>", "request" : "view/files/files_details", "params" : {"file_id" : <?php echo $result->file_id ?>}})'>
                
                Detalhes
              
              </a>
            
            </li>

            <li>
              
              <a class="dropdown-item" onclick='new Request({"request" : {"path" : "view/files/files_form_folder"}, "params" : {"file_id" : <?php echo $result->file_id ?>}})'>
                
                Pasta
              
              </a>
            
            </li>

          </ul>

        </div>

      </td>

    </tr>

  <?php } ?>

</tbody>

</table>