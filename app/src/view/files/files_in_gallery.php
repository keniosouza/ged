<?php

/** Importação de classes */
use src\model\Files;

/** Instânciamento de classes */
$Files = new Files();

// Busco todos os arquivos
$FilesAllResult = $Files->All();

// Verifico se existem arquivos
if (count($FilesAllResult) > 0) {?>

<div class="row g-2">

    <?php

    /** Percorro todos os itens localizados */
    foreach ($FilesAllResult as $key => $result) { ?>

        <div class="col-md-2 d-flex">

            <div class="card w-100 card-hover">

                <div class="card-body cursor-pointer" onclick='new Request({"request" : {"path" : "view/files/files_preview"}, "params" : {"file_id" : <?php echo $result->file_id ?>}})'>

                    <div class="fs-5 fw-normal text-truncate">

                        <img src="<?php echo $Main->GetExtensionIcon($Main->getFileExtension($result->name)); ?>" width="20px" class="img-fluid">
                        <?php echo $result->name?>

                    </div>

                    <p class="card-text fw-light text-body-secondary">

                        <?php echo $result->extension?> - <?php echo $Main->GetFileSizeFormated($result->path . '/' . $result->name)?>

                    </p>

                </div>

                <div class="card-footer bg-transparent border-0">

                    <div class="dropdown">

                        <button class="btn btn-secondary btn-sm dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false"></button>

                        <ul class="dropdown-menu shadow-sm">

                            <li>

                                <a class="dropdown-item" onclick='new Request({"request" : {"path" : "view/files/files_form"}, "params" : {"file_id" : <?php echo $result->file_id ?>}})'>

                                    <i class="bi bi-pencil me-1"></i>Editar

                                </a>

                            </li>

                            <li>

                                <a class="dropdown-item" onclick='new OffCanva({"title" : "<?php echo $result->name ?>", "request" : "view/files/files_details", "params" : {"file_id" : <?php echo $result->file_id ?>}})'>

                                    <i class="bi bi-search me-1"></i>Detalhes

                                </a>

                            </li>

                            <li>

                                <a class="dropdown-item" onclick='new Request({"request" : {"path" : "view/files/files_form_folder"}, "params" : {"file_id" : <?php echo $result->file_id ?>}})'>

                                    <i class="bi bi-folder me-1"></i>Pasta

                                </a>

                            </li>

                            <li>

                                <a class="dropdown-item" onclick='new Request({"request" : {"path" : "view/files/files_preview"}, "params" : {"file_id" : <?php echo $result->file_id ?>}})'>

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

                </div>

            </div>

        </div>

    <?php }?>

</div>

<?php } else {?>

    <div class="alert alert-primary" role="alert">

        Não há arquivos publicados no <b>momento!</b>

    </div>

<?php }?>