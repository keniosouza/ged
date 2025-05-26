<?php

/** Importação de classes */
use src\model\Files;
use src\model\UsersFavorites;

/** Instânciamento de classes */
$Files = new Files();
$UsersFavorites = new UsersFavorites();

// Verifico se devo buscar os favoritos
if (count($UsersFavorites->AllRegisterIdByTable('files')) > 0) {

    /** Busco todos os usuários */
    $FilesAllInFileId = $Files->AllInFileId($UsersFavorites->AllRegisterIdByTable('files'));

} else {

    // Defino como vazio
    $FilesAllInFileId = [];

}?>

<?php

// Verifico se existem arquivos
if (count($FilesAllInFileId) > 0) {?>

<table class="table table-borderless border align-items-center shadow-sm">

    <thead>

        <tr>

            <th scope="col" class="text-center">

            #

            </th>

            <th scope="col">

            Nome

            </th>

            <th scope="col" class="text-center">

            Tamanho

            </th>

            <th scope="col" class="text-center">

            Cadastro

            </th>

            <th scope="col" class="text-center">

            Operações

            </th>

        </tr>

    </thead>

    <tbody>

    <?php

    /** Percorro todos os itens localizados */
    foreach ($FilesAllInFileId as $key => $result) {

        // Verifico se o registro esta favoritado
        $UsersFavoritesGetByUserIdTableRegisterIdResult = $UsersFavorites->GetByUserIdTableRegisterId($UserSessionResult->user_id, 'files', $result->file_id);?>

        <tr class="align-middle border-top">

            <th scope="row" class="text-center">

                <?php echo $result->file_id ?>

            </th>

            <td>

                <div class="d-flex align-items-center">

                    <div class="flex-shrink-0">

                        <span class="cursor-pointer me-1" id="UserFavorite_files<?php echo $result->file_id?>" onclick='new Request({"request" : {"path" : "action/users_favorites/users_favorites_save_or_remove"}, "params" : {"register_id" : <?php echo $result->file_id ?>, "table" : "files"}});'>

                            <i class="bi bi-bookmark<?echo (int)$UsersFavoritesGetByUserIdTableRegisterIdResult->user_favorite_id > 0 ? '-fill' : null ?>"></i>

                        </span>

                    </div>

                    <div class="flex-shrink-0 ms-2">

                        <img src="<?php echo $Main->GetExtensionIcon($Main->getFileExtension($result->name)); ?>" width="40px" class="img-fluid">

                    </div>

                    <div class="flex-grow-1 ms-3">

                        <div class="fs-6 fw-bold">

                            <?php echo $result->name ?>

                        </div>

                        <div class="fs-6 fw-bold">

                            <?php echo $result->description ?>

                        </div>

                    </div>

                </div>

            </td>

            <td class="text-center">

                <div class="fs-6 fw-normal">

                    <?php echo $Main->GetFileSizeFormated($result->path . '/' . $result->name)?>

                </div>

            </td>

            <td class="text-center">

                <div class="fs-6 fw-normal">

                01/01/2021

                </div>

            </td>

            <td class="text-center">

                <div class="dropdown">

                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                        <i class="bi bi-three-dots"></i>

                    </button>

                    <ul class="dropdown-menu shadow-sm">

                        <li>

                            <a class="dropdown-item" onclick='new Request({"request" : {"path" : "view/files/files_form"}, "params" : {"log_register_id" : <?php echo $result->file_id ?>, "file_id" : <?php echo $result->file_id ?>}})'>

                                <i class="bi bi-pencil me-1"></i>Editar

                            </a>

                        </li>

                        <li>

                            <a class="dropdown-item" onclick='new OffCanva({"title" : "<?php echo $result->name ?>", "request" : "view/files/files_details", "params" : {"log_register_id" : <?php echo $result->file_id ?>, "file_id" : <?php echo $result->file_id ?>}})'>

                                <i class="bi bi-search me-1"></i>Detalhes

                            </a>

                        </li>

                        <li>

                            <a class="dropdown-item" onclick='new Request({"request" : {"path" : "view/files/files_form_folder"}, "params" : {"log_register_id" : <?php echo $result->file_id ?>, "file_id" : <?php echo $result->file_id ?>}})'>

                                <i class="bi bi-folder me-1"></i>Pasta

                            </a>

                        </li>

                        <li>

                            <a class="dropdown-item" onclick='new Request({"request" : {"path" : "view/files/files_preview"}, "params" : {"log_register_id" : <?php echo $result->file_id ?>, "file_id" : <?php echo $result->file_id ?>}})'>

                                <i class="bi bi-eye me-1"></i>Ver

                            </a>

                        </li>

                        <li>

                            <a class="dropdown-item" onclick='new Request({"request" : {"path" : "action/files/files_delete"}, "params" : {"log_register_id" : <?php echo $result->file_id ?>, "file_id" : <?php echo $result->file_id ?>}})'>

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

        Não há arquivos favoritos no <b>momento!</b>

    </div>

<?php }?>