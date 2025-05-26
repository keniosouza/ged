<?php

/** Importação de classes */

use src\model\Modules;
use src\model\Companies;
use src\controller\modules\ModulesValidate;

try {

    /** Instânciamento de classes */
    $Modules = new Modules();
    $Companies = new Companies();
    $ModulesValidate = new ModulesValidate();

    /** Parametros de entrada */
    $moduleId = isset($_POST['module_id']) ? (int)filter_input(INPUT_POST, 'module_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Sanitiza os parametros de entrada */
    $ModulesValidate->setModuleId($moduleId);

    /** Busco o registro desejado */
    $ModulesGetResult = $Modules->Get($ModulesValidate->getModuleId());

    /** Verifica se o objeto foi carregado,
     * caso não tenha sido, cria o mesmo vazio
     */
    if (!is_object($ModulesGetResult)) {

        $ModulesGetResult = $Modules->Describe();
    }

?>

    <form id="ModulesForm" class="row g-2">

        <div class="col-md-12" id="ModulesFormResponse"></div>

        <div class="col-md-12">

            <div class="form-floating">

                <input type="email" class="form-control" id="description" name="description" value="<?php echo (string)$ModulesGetResult->description ?>">
                <label for="description">Descrição</label>

            </div>

        </div>

        <div class="col-md-6">

            <div class="form-floating">

                <input type="text" class="form-control" id="name" name="name" value="<?php echo (string)$ModulesGetResult->name ?>">
                <label for="name">Nome</label>

            </div>

        </div>

        <div class="col-md-6">

            <div class="form-floating">


                <select class="form-select" id="status" name="status">


                    <option value="A" <?php echo $ModulesGetResult->status === 'A' ? 'selected' : null ?>>

                        Ativo

                    </option>

                    <option value="I" <?php echo $ModulesGetResult->status === 'I' ? 'selected' : null ?>>

                        Inativo

                    </option>

                    <option value="D" <?php echo $ModulesGetResult->status === 'D' ? 'selected' : null ?>>

                        Remover

                    </option>

                </select>

                <label for="floatingPosition">Situação</label>

            </div>

        </div>

        <div class="col-md-12">

            <select class="form-select" id="company_id" name="company_id">

                <?php

                /** Percorro todos os itens localizados */
                foreach ($Companies->All(null, null, null) as $key => $result) { ?>

                    <option value="<?php echo $result->company_id ?>" <?php echo $result->company_id === $ModulesGetResult->company_id ? 'selected' : null ?>>

                        <?php echo $result->name_business ?>

                    </option>

                <?php } ?>

            </select>

        </div>

        <div class="row">

            <div class="col-md-12 text-center p-2">

                <button class="btn btn-primary m-1 float-end" id="btnModulesSave" onclick='new Request({"request" : {"path" : "action/modules/modules_save"}, 
                                                                        "loader" : {"type" : 1, 
                                                                        "target" : "btnModulesSave"}, 
                                                                        "response" : {"target" : "ModulesFormResponse"}, 
                                                                        "form" : "ModulesForm"})' type="button">

                    <i class="bi bi-check me-1"></i>Salvar

                </button>

                <button class="btn btn-danger m-1 float-end" data-bs-dismiss="modal" aria-label="Close" type="button">

                    <i class="bi bi-x me-1"></i>Cancelar

                </button>

            </div>

            <input type="hidden" name="csrf_token" value="<?php echo $Main->getCSRF(); ?>">
            <input type="hidden" name="module_id" value="<?php echo (int)$ModulesGetResult->module_id ?>">

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
                'title' => $ModulesValidate->getModuleId() > 0 ? 'Editando Cadastro' : 'Cadastrar Módulo',
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
