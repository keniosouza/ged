<?php

// Importação de classes
use src\controller\main\Main;

// Instânciamento de classe
$Main = new Main();

/** Defino a opções de exibição do form */
$options = new stdClass();
/** Defino para aceitar apenas imagens */
$options->accept = null;
/** Defino para selecionar apenas um arquivo */
$options->multiple = false;
/** defino o tipo de exbição que deve ser feito: 1 - List, 2 - Recorte */
$options->preview = 1;
/** defino o tipo de exbição que deve ser feito: 1 - List, 2 - Recorte */
$options->phrase = 'Solte seus arquivos aqui';

/** Gero um Hash Aleatório para não duplicar declarações */
$MainRandomHashResult = $Main->RandomHash();

?>

<script type="text/javascript">
    /** Instânciamento da classe File */
    var _file = new File();
</script>

<div id="FilesZone<?php echo $MainRandomHashResult ?>">

    <span class="drop-title">

        <div class="row">

            <div class="col-4"></div>

            <div class="col-4">

                <div class="fs-12 fw-normal text-muted text-center d-flex flex-wrap gap-3 mb-4 pb-2">
                    <div class="flex-fill py-3 px-4 rounded-1 d-none d-sm-block border border-dashed border-gray-5">

                        <div class="custom-control custom-checkbox mb-2">
                            <input type="radio" class="custom-control-input" value="folders" checked=true id="folders" name="optionUpload" wfd-id="id5" onclick="enableOrDisable()">
                            <label class="custom-control-label c-pointer text-muted" for="folders" style="font-weight: 400 !important">Selecionar pasta inteira</label>
                        </div>

                    </div>
                    <div class="flex-fill py-3 px-4 rounded-1 d-none d-sm-block border border-dashed border-gray-5">

                        <div class="custom-control custom-checkbox">
                            <input type="radio" class="custom-control-input" value="files" id="files" name="optionUpload" wfd-id="id6" onclick="enableOrDisable()">
                            <label class="custom-control-label c-pointer text-muted" for="files" style="font-weight: 400 !important">Selecionar arquivos</label>
                        </div>

                    </div>
                </div>

            </div>

            <div class="col-4"></div>

        </div>

    </span>

    <label for="file" class="drop-container" id="dropContainer">

        <input type="file" id="file" name="file" onchange="_file.prepare('file')" <?php echo !empty($options->accept) ? 'accept="' . $options->accept . '"' : null ?> <?php echo $options->multiple ? 'multiple' : 'webkitdirectory' ?> data-mysupport-file-preview="<?php echo $options->preview ?>" data-mysupport-file-zone="FilesZone<?php echo $MainRandomHashResult ?>">

    </label>

</div>