<?php

/** Importação de classes */

use src\model\Batchs;
use src\model\Companies;
use src\model\FilesCategories;
use src\controller\batchs\BatchsValidate;

try {

    /** Instânciamento de classes */
    $Batchs = new Batchs();
    $Companies = new Companies();
    $BatchsValidate = new BatchsValidate();
    $FilesCategories = new FilesCategories();

    /** Parametros de entrada */
    $batchId   = isset($_POST['batch_id'])   ? (int) filter_input(INPUT_POST, 'batch_id', FILTER_SANITIZE_NUMBER_INT)   : 0;
    $companyId = isset($_POST['company_id']) ? (int) filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Sanitiza os parametros de entrada */
    $BatchsValidate->setBatchId($batchId);
    $BatchsValidate->setCompanyId($companyId);

    /** Busco o registro desejado */
    $BatchsGetResult = $Batchs->Get($BatchsValidate->getBatchId());

    /** Verifica se o objeto foi carregado,
     * caso não tenha sido, cria o mesmo vazio
     */
    if (!is_object($BatchsGetResult)) {

        $BatchsGetResult = $Batchs->Describe();
    }

?>

    <form id="BatchsForm" class="row g-2" action="javascript:void">

        <div class="col-md-12" id="BatchsFormResponse"></div>

        <?php

        // Verifico se já existe a identificação da empresa, caso sim, ingora a seleção
        if ($companyId === 0) { ?>

            <div class="col-md-12">
                <div class="form-floating">

                    <select class="form-select" id="company_id" name="company_id">

                        <?php

                        /** Percorro todos os itens localizados */
                        foreach ($Companies->All(null, null, null) as $key => $result) { ?>

                            <option value="<?php echo $result->company_id ?>" <?php echo $result->company_id === $BatchsGetResult->company_id ? 'selected' : null ?>>

                                <?php echo $result->name_business ?>

                            </option>

                        <?php } ?>

                    </select>
                    <label for="description">Empresa</label>
                </div>

            </div>

        <?php } ?>

        <div class="col-md-4">

            <div class="form-floating">

                <input type="text" class="form-control" id="description" name="description" value="<?php echo (string) $BatchsGetResult->description ?>">
                <label for="description">Descrição</label>

            </div>

        </div>
        <div class="col-md-4">

            <div class="form-floating">


                <select class="form-select" id="status" name="status">


                    <option value="A" <?php echo $result->status === 'A' ? 'selected' : null ?>>

                        Ativo

                    </option>

                    <option value="I" <?php echo $result->status === 'I' ? 'selected' : null ?>>

                        Inativo

                    </option>

                    <option value="D" <?php echo $result->status === 'D' ? 'selected' : null ?>>

                        Remover

                    </option>

                </select>

                <label for="floatingPosition">Situação</label>

            </div>

        </div>


        <div class="col-md-4">

            <div class="form-floating">

                <select class="form-select" id="file_category_id" name="file_category_id">

                    <option value="0">

                        Selecione

                    </option>

                    <?php

                    /** Percorro todos os itens localizados */
                    foreach ($FilesCategories->All(null, null, null) as $key => $result) { ?>

                        <option value="<?php echo $result->file_category_id ?>" <?php echo $result->file_category_id === $BatchsGetResult->file_category_id ? 'selected' : null ?>>

                            <?php echo $result->description ?>

                        </option>

                    <?php } ?>

                </select>

                <label for="floatingPosition">Categoria</label>

            </div>

        </div>

        <div class="row">

            <div class="col-md-12 text-center p-2">

                <button class="btn btn-primary m-1 float-end" id="btnBatchsSave" onclick='new Request({"request" : {"path" : "action/batchs/batchs_save"}, 
                                                                                            "loader" : {"type" : 1, "target" : "btnBatchsSave"}, 
                                                                                            "response" : {"target" : "BatchsFormResponse"}, 
                                                                                            "form" : "BatchsForm"})' type="button">

                    <i class="bi bi-check me-1"></i>Salvar

                </button>

                <button class="btn btn-danger m-1 float-end" data-bs-dismiss="modal" aria-label="Close" type="button">

                    <i class="bi bi-x me-1"></i>Cancelar

                </button>

            </div>

            <input type="hidden" name="csrf_token" value="<?php echo $Main->getCSRF(); ?>">
            <input type="hidden" name="batch_id" value="<?php echo (int) $BatchsGetResult->batch_id ?>">

        </div>

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
                'title' => $BatchsValidate->getBatchId() > 0 ? 'Editando Cadastro' : 'Cadastrar Lote',
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
