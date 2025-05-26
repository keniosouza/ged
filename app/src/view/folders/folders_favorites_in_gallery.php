<?php

/** Importação de classes */
use src\model\Folders;
use src\model\UsersFavorites;

/** Instânciamento de classes */
$Folders = new Folders();
$UsersFavorites = new UsersFavorites();

// Verifico se devo buscar os favoritos
if (count($UsersFavorites->AllRegisterIdByTable('folders')) > 0) {

    /** Busco todos os usuários */
    $FoldersAllInFolderId = $Folders->AllInFolderId($UsersFavorites->AllRegisterIdByTable('folders'));

} else {

    // Defino como vazio
    $FoldersAllInFolderId = [];

}?>

<?php

// Verifico se existem arquivos
if (count($FoldersAllInFolderId) > 0) {?>

<div class="row g-2">

    <?php

    /** Percorro todos os itens localizados */
    foreach ($FoldersAllInFolderId as $key => $result) {
        
        // Verifico se o registro esta favoritado
        $UsersFavoritesGetByUserIdTableRegisterIdResult = $UsersFavorites->GetByUserIdTableRegisterId($UserSessionResult->user_id, 'folders', $result->folder_id);?>

        <div class="col-md-2 d-flex">

            <div class="card w-100 card-hover">

                <div class="card-header bg-transparent border-0">

                    <div class="d-flex justify-content-between align-items-start">

                        <div>

                            <span class="cursor-pointer me-1" id="UserFavorite_files<?php echo $result->folder_id?>" onclick='new Request({"request" : {"path" : "action/users_favorites/users_favorites_save_or_remove"}, "params" : {"register_id" : <?php echo $result->folder_id ?>, "table" : "files"}});'>

                                <i class="bi bi-bookmark<?echo (int)$UsersFavoritesGetByUserIdTableRegisterIdResult->user_favorite_id > 0 ? '-fill' : null ?>"></i>

                            </span>

                            <img src="assets/img/default/folder.png" width="25px" class="img-fluid">

                        </div>

                    </div>

                </div>

                <div class="card-body cursor-pointer" onclick='new Request({"request" : {"path" : "view/folders/folders_details"}, "params" : {"folder_id" : <?php echo $result->folder_id?>}});'>

                    <div class="fs-5 fw-normal">

                    <?php echo $result->name; ?>

                    </div>

                </div>

                <div class="card-footer bg-transparent border-0">

                    <div class="dropdown">

                        <button class="btn btn-secondary btn-sm dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>

                        <ul class="dropdown-menu shadow-sm">

                            <li>

                                <a class="dropdown-item" onclick='new Request({"request" : {"path" : "view/folders/folders_form"}, "params" : {"folder_id" : <?php echo $result->folder_id ?>, "view" : "2"}})' type="button">

                                    <i class="bi bi-pencil me-1"></i>Editar

                                </a>

                            </li>

                            <li>

                                <a class="dropdown-item" onclick='new Request({"request" : {"path" : "view/folders/folders_details"}, "params" : {"folder_id" : <?php echo $result->folder_id?>}});'>

                                    <i class="bi bi-eye me-1"></i>Ver

                                </a>

                            </li>

                            <li>

                                <a class="dropdown-item" onclick='new Request({"request" : {"path" : "action/folders/folders_delete"}, "params" : {"log_register_id" : <?php echo $result->folder_id ?>, "folder_id" : <?php echo $result->folder_id ?>}})'>

                                    <i class="bi bi-trash me-1"></i>Remover

                                </a>

                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>

    <?php }?>

</div>

<?php } else {?>

    <div class="alert alert-primary" role="alert">

        Não há pastas favoritas no <b>momento!</b>

    </div>

<?php }?>