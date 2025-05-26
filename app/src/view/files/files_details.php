<?php

// Importação de classes
use src\controller\main\Main;
use src\model\Files;
use src\model\FilesCategories;
use src\model\Logs;
use src\controller\logs\LogsHandling;

try {

    // Instânciamento de classes
    $Main = new Main();
    $Files = new Files();
    $FilesCategories = new FilesCategories();
    $Files = new Files();
    $Logs = new Logs();
    $LogsHandling = new LogsHandling();

    // Busco um determinado registro
    $FilesGetResult = $Files->Get(filter_input(INPUT_POST, 'file_id', FILTER_SANITIZE_SPECIAL_CHARS));

    // Busco tods os registros de log vinculado a esse arquivo
    $LogsAllByRegisterIdAndRequest = $Logs->AllByRegisterIdAndRequest($FilesGetResult->file_id, '/files/files_'); ?>

    <div class="row g-3">

        <div class="col-md-12">

            <div class="fs-5 fw-bold">

                Tipo:

            </div>

            <div class="fs-6 fw-normal">

                <img src="<?php echo $Main->GetExtensionIcon($Main->getFileExtension($FilesGetResult->name)); ?>" width="20px" class="img-fluid">
                <?php echo $FilesGetResult->extension; ?>

            </div>

        </div>

        <div class="col-md-12">

            <div class="fs-5 fw-bold">

                Tamanho:

            </div>

            <div class="fs-6 fw-normal">

                <?php echo $Main->GetFileSizeFormated($FilesGetResult->path . '/' . $FilesGetResult->name) ?>

            </div>

        </div>

        <div class="col-md-12">

            <div class="fs-5 fw-bold">

                Criado em:

            </div>

            <div class="fs-6 fw-normal">

                <?php

                // Busco o primeiro registro de Log
                $dateRegister = $Logs->GetFirstByTableByRegisterId('files', $FilesGetResult->file_id);

                // Exibo a Data formatada
                echo date('d/m/Y H:i:s', strtotime($dateRegister->date_register));

                ?>

            </div>

        </div>

        <div class="col-md-12">

            <section class="bsb-timeline-1 mt-3 px-2">

                <div class="row justify-content-center">

                    <div class="col-md-12">

                        <?php

                        /** Percorro todos os itens localizados */
                        foreach ($LogsAllByRegisterIdAndRequest as $key => $result) { ?>

                            <ul class="timeline">

                                <li class="timeline-item">

                                    <div class="timeline-body">

                                        <div class="timeline-content">

                                            <div class="card border-0">

                                                <div class="card-body p-0">

                                                    <h6 class="card-subtitle text-secondary mb-1">

                                                        <?php echo date('d/m/Y H:i:s', strtotime($result->date_register)) ?>

                                                    </h6>

                                                    <h5 class="card-title mb-1">

                                                        <?php echo $result->name ?>

                                                    </h5>

                                                    <p class="card-text m-0 fw-normal mb-3">

                                                        <?php echo $LogsHandling->dictionary($result->request); ?>

                                                    </p>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </li>

                            </ul>

                        <?php } ?>

                    </div>

                </div>

            </section>

        </div>

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
                'title' => 'Arquivo / Detalhe',
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
