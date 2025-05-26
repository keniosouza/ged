<?php

/** Importação de classes */

use src\model\Modules;

try {

    /** Instânciamento de classes */
    $Modules = new Modules(); ?>

    <div class="d-flex justify-content-between align-items-start mb-2">

        <div class="text-body">

            <h6 class="fw-light">

                Módulos /

            </h6>

            <h5>

                Visão Geral

            </h5>

        </div>

        <button class="btn btn-primary btn-sm" data-route='{"request": "view/modules/modules_form"}'>

            <i class="bi bi-plus me-1"></i>Adicionar

        </button>

    </div>

    <table class="table table-borderless border align-items-center">

        <thead>

            <tr>

                <th scope="col" class="text-center">

                    #

                </th>

                <th scope="col">

                    Nome

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
            foreach ($Modules->All() as $key => $result) { ?>

                <tr class="align-middle border-top">

                    <th scope="row" class="text-center">

                        <?php echo $result->module_id ?>

                    </th>

                    <td>

                        <span class="status-dot bg-success rounded-circle me-1"></span>Ativo

                    </td>

                    <td>

                        <div class="fs-6 fw-bold">

                            <?php echo $result->name ?>

                        </div>

                        <div class="fs-6 fw-normal">

                            <?php echo $result->description ?>

                        </div>

                    </td>

                    <td class="text-center">

                        <button class="btn btn-primary btn-sm" data-route='{"request": "view/modules/modules_form", "params" : {"module_id" : "<?php echo $result->module_id ?>"}}'>

                            <i class="bi bi-pencil me-1"></i>Editar

                        </button>

                        <button class="btn btn-primary btn-sm" data-route='{"request": "view/modules/modules_details", "params" : {"module_id" : "<?php echo $result->module_id ?>"}}'>

                            <i class="bi bi-pencil me-1"></i>Editar

                        </button>

                    </td>

                </tr>

            <?php } ?>

        </tbody>

    </table>

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
