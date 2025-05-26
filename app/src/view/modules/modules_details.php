<?php

/** Importação de classes */

use src\model\Modules;
use src\model\ModulesAcls;

try {

    /** Instânciamento de classes */
    $Modules = new Modules();
    $ModulesAcls = new ModulesAcls();

    /** Parametros de entrada */
    $moduleId = isset($_POST['module_id']) ? (int)filter_input(INPUT_POST, 'module_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Busco o registro desejado */
    $ModulesGetResult = $Modules->Get($moduleId);

?>

    <div class="row g-3 mt-1">

        <?php

        /** Controle */
        $i = 0;

        /** Percorro todos os itens localizados */
        foreach ($ModulesAcls->AllByModuleId($ModulesGetResult->module_id) as $key => $result) {

            // Decodifico as permissões
            $result->preferences = (object) json_decode($result->preferences);

            /** Contabiliza as permissões do modulo */
            $i++;

        ?>

            <div class="col-md-3 d-flex" id="md<?php echo $result->module_acl_id; ?>">

                <div class="card w-100">

                    <div class="card-header">

                        <?php echo $result->description ?>
                    </div>

                    <div class="card-body">

                        <ul>

                            <?php

                            /** Percorro todos os itens localizados */
                            foreach ($result->preferences as $keyPreference => $resultPreference) { ?>

                                <li>

                                    <?php echo $resultPreference ?>

                                </li>

                            <?php } ?>

                        </ul>

                    </div>

                    <div class="card-footer bg-transparent">

                        <div class="dropdown">

                            <button class="btn btn-secondary btn-sm dropdown-toggle w-100" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                                Operações

                            </button>

                            <ul class="dropdown-menu shadow-sm">

                                <li>

                                    <a class="dropdown-item" onclick='new Request({"request" : {"path" : "view/modules_acls/modules_acls_form"}, 
                                                                                                "loader" : {"type" : 3},
                                                                                                "params" : {"module_id" : <?php echo $result->module_id ?>, 
                                                                                                            "module_acl_id" : <?php echo $result->module_acl_id ?>}})'>

                                        <i class="bi bi-pencil me-1"></i>Editar

                                    </a>

                                </li>

                                <li>

                                    <a class="dropdown-item" onclick='new Request({"request" : {"path" : "action/modules_acls/modules_acls_delete"}, "params" : {"module_acl_id" : <?php echo $result->module_acl_id ?>}})'>

                                        <i class="bi bi-trash me-1"></i>Remover

                                    </a>

                                </li>

                            </ul>

                        </div>

                    </div>

                </div>

            </div>

        <?php
        }

        /** Verifica se não existem 
         * permissões a serem listadas */
        if ($i == 0) { ?>

            <div class="alert alert-warning mt-2" role="alert"><i class="bi bi-exclamation-triangle me-2">Não há níveis de acesso cadastrados para este módulo</i></div>

        <?php
        }
        ?>

        <button class="btn btn-primary btn-sm w-100" id="btnNivelAcesso" onclick='new Request({"request" : {"path" : "view/modules_acls/modules_acls_form"},
                                                                                           "loader" : {"type" : 1, "target" : "btnNivelAcesso"}, 
                                                                                           "params" : {"module_id" : "<?php echo $ModulesGetResult->module_id ?>"}})'>

            Adicionar Niveis de Acesso

        </button>

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
                'title' => 'Módulo / ' . $ModulesGetResult->name,
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
