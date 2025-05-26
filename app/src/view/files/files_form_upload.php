<?php

/** Importação de classes */

use src\model\Batchs;
use src\controller\batchs\BatchsValidate;

try {

    /** Instânciamento de classes */
    $Batchs = new Batchs();
    $BatchsValidate = new BatchsValidate();

    /** Parâmetros de paginação **/
    $batchId = isset($_POST['batch_id']) ? (int) filter_input(INPUT_POST, 'batch_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Sanitiza os paramentros de entrada */
    $BatchsValidate->setBatchId($batchId);

    /** Verifica se o lote foi informado */
    if ($BatchsValidate->getBatchId() > 0) {

        /** Busco o registro desejado */
        $BatchsGetResult = $Batchs->Get($BatchsValidate->getBatchId());
    } else {

        /** Crio um objeto vazio */
        $BatchsGetResult = $Batchs->Describe();
    }

?>

    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Arquivos</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="home">Home</a></li>
                <li class="breadcrumb-item">Enviar Arquivo</li>
            </ul>
        </div>

        <div class="page-header-right ms-auto">
            <div class="page-header-right-items">
                <div class="d-flex d-md-none">
                    <a href="javascript:void(0)" class="page-header-right-close-toggle">
                        <i class="feather-arrow-left me-2"></i>
                        <span>Back</span>
                    </a>
                </div>
            </div>
            <div class="d-md-none d-flex align-items-center">
                <a href="javascript:void(0)" class="page-header-right-open-toggle">
                    <i class="feather-align-right fs-20"></i>
                </a>
            </div>
        </div>
    </div>
    <!-- [ page-header ] end -->


    <!-- [ Main Content ] start -->
    <div class="main-content">

        <div class="row">

            <div class="col-lg-12">

                <div class="card stretch stretch-full">

                    <div class="card-header">
                        <h3 class="card-title"><?php echo $BatchsGetResult->batch_id > 0 ? $BatchsGetResult->description : 'Envio de Arquivos'; ?></h3>
                    </div> <!-- /.card-header -->

                    <div class="card-body">

                        <!-- Espaço reservado para construção do formulário de arquivo -->
                        <div id="FilesFormWrapper">

                            <script type="text/javascript">
                                /** Envio de Requisição */
                                new Request({
                                    "request": {
                                        "path": "view/files/files_form_component"
                                    },
                                    "loader": {
                                        "type": 3
                                    },
                                    "response": {
                                        "target": "FilesFormWrapper"
                                    },
                                    "form": null
                                }, null)
                            </script>

                        </div>

                        <form id="FilesFormHeader">

                            <input type="hidden" name="path" value="action/files/files_save_file.php" />
                            <input type="hidden" name="batch_id" value="<?php echo $BatchsGetResult->batch_id; ?>" />

                        </form>

                    </div><!-- /.card-body -->

                    <!-- <div class="card-footer clearfix">

                    </div> -->
                </div>
            </div>
        </div>
    </div>

<?php

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
