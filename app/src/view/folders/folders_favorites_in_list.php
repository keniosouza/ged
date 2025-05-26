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

<table class="table table-borderless border align-items-center shadow-sm">

    <thead>

        <tr>

            <th scope="col" class="text-center">

                #

            </th>

            <th scope="col">

                Descrição

            </th>

            <th scope="col" class="text-center">

                Operações

            </th>

        </tr>

    </thead>

    <tbody>

    <?php

    /** Percorro todos os itens localizados */
    foreach ($FoldersAllInFolderId as $key => $result) {
        
        // Verifico se o registro esta favoritado
        $UsersFavoritesGetByUserIdTableRegisterIdResult = $UsersFavorites->GetByUserIdTableRegisterId($UserSessionResult->user_id, 'folders', $result->folder_id);?>

        <tr class="align-middle border-top">

            <th scope="row" class="text-center">

                <?php echo $result->folder_id ?>

            </th>

            <td>

                <div class="d-flex align-items-center">

                    <div class="flex-shrink-0">

                        <span class="cursor-pointer me-1" id="UserFavorite_files<?php echo $result->folder_id?>" onclick='new Request({"request" : {"path" : "action/users_favorites/users_favorites_save_or_remove"}, "params" : {"register_id" : <?php echo $result->folder_id ?>, "table" : "files"}});'>

                            <i class="bi bi-bookmark<?echo (int)$UsersFavoritesGetByUserIdTableRegisterIdResult->user_favorite_id > 0 ? '-fill' : null ?>"></i>

                        </span>

                    </div>

                    <div class="flex-grow-1 ms-3">

                        <div class="fs-6 fw-bold">

                            <?php echo $result->name ?>

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
                        
                            <a class="dropdown-item" onclick='new Request({"request" : {"path" : "view/folders/folders_form"}, "params" : {"folder_id" : "<?php echo $result->folder_id ?>", "view" : "1"}})'>
                                
                                Editar
                            
                            </a>
                        
                        </li>

                        <li>
                            
                            <a class="dropdown-item" onclick='new OffCanva({"title" : "<?php echo $result->name ?>", "request" : "view/folders/folders_details", "params" : {"folder_id" : <?php echo $result->folder_id ?>}})'>
                                
                                Detalhes
                            
                            </a>
                        
                        </li>

                        <li>

                            <a class="dropdown-item" onclick='new Request({"request" : {"path" : "action/folders/folders_delete"}, "params" : {"log_register_id" : <?php echo $result->folder_id ?>, "folder_id" : <?php echo $result->folder_id ?>}})'>

                                <i class="bi bi-trash me-1"></i>Remover

                            </a>

                        </li>

                    </ul>

                </div>

            </td>

        </tr>

    <?php } ?>

    </tbody>

</table>

<?php } else {?>

    <div class="alert alert-primary" role="alert">

        Não há pastas favoritas no <b>momento!</b>

    </div>

<?php }?>