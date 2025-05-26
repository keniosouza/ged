<?php

/** Importação de classes */

use src\model\Companies;
use src\model\Users;
use src\model\Logs;

try {

    /** Instânciamento de classes */
    $Companies = new Companies();
    $Users = new Users();
    $Logs = new Logs();

    /** Parametros de entrada */
    $companyId = isset($_POST['company_id']) ? (string)filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Busco o registro desejado */
    $CompaniesGetResult = $Companies->Get($companyId);
    $UsersAllByCompanyId = $Users->AllByCompanyId($CompaniesGetResult->company_id);
    $LogsAllByCompanyIdResult = $Logs->GraphByCompanyId($CompaniesGetResult->company_id);

?>

    <div class="card">

        <div class="card-header border-0 bg-transparent">

            <div class="d-flex align-items-center">

                <div class="flex-shrink-0">

                    <div class="border p-2 rounded">

                        <img src="assets/img/default/company.png" class="img-fluid" width="64px">

                    </div>

                </div>

                <div class="flex-grow-1 ms-3">

                    <div class="fs-3 fw-bold mb-0">

                        <?php echo $CompaniesGetResult->name_business ?>

                    </div>

                    <div class="fs-4 fw-normal">

                        <?php echo $CompaniesGetResult->name_business ?>

                    </div>

                </div>

            </div>

        </div>

        <div class="card-body">

            <ul class="nav nav-tabs nav-justified nav-bordered mb-3">

                <li class="nav-item" role="presentation">

                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button">

                        <i class="bi bi-window-fullscreen me-1"></i>Usuários

                    </button>

                </li>

                <li class="nav-item" role="presentation">

                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button">

                        <i class="bi bi-graph-up me-1"></i>Tráfego

                    </button>

                </li>

            </ul>

            <div class="tab-content" id="myTabContent">

                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" tabindex="0">

                    <button class="btn btn-primary btn-sm w-100 mb-3" onclick='new Request({"request" : {"path" : "view/users/users_form"}, "params" : {"company_id" : "<?php echo $CompaniesGetResult->company_id ?>"}})'>

                        + Usuários

                    </button>

                    <table class="table table-borderless border align-items-center shadow-sm">

                        <thead>

                            <tr>

                                <th scope="col" class="text-center">

                                    #

                                </th>

                                <th scope="col">

                                    Status

                                </th>

                                <th scope="col">

                                    Nome/Email

                                </th>

                                <th scope="col">

                                    Equipe/Função

                                </th>

                                <th scope="col" class="text-center">

                                    Operações

                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php

                            /** Percorro todos os itens localizados */
                            foreach ($UsersAllByCompanyId as $key => $result) { ?>

                                <tr class="align-middle border-top">

                                    <th scope="row" class="text-center">

                                        <?php echo $result->user_id ?>

                                    </th>

                                    <td>

                                        <span class="status-dot bg-success rounded-circle me-1"></span>Ativo

                                    </td>

                                    <td>

                                        <div class="fs-6 fw-bold">

                                            <?php echo $result->name ?>

                                        </div>

                                        <div class="fs-6 fw-normal">

                                            <?php echo $result->email ?>

                                        </div>

                                    </td>

                                    <td>

                                        <div class="fs-6 fw-bold">

                                            <?php echo $result->team ?>

                                        </div>

                                        <div class="fs-6 fw-normal">

                                            <?php echo $result->position ?>

                                        </div>

                                    </td>

                                    <td class="text-center">

                                        <div class="dropdown">

                                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                                                <i class="bi bi-three-dots"></i>

                                            </button>

                                            <ul class="dropdown-menu shadow-sm">

                                                <li>

                                                    <a class="dropdown-item" onclick='new Request({"request": {"path" : "view/users_acls/users_acls_index"}, "params" : {"user_id" : "<?php echo $result->user_id ?>"}})'>

                                                        <i class="bi bi-list-check me-1"></i>Permissões

                                                    </a>

                                                </li>

                                                <li>

                                                    <a class="dropdown-item" onclick='new Request({"request": {"path" : "view/users/users_form"}, "params" : {"user_id" : "<?php echo $result->user_id ?>"}})'>

                                                        <i class="bi bi-pencil me-1"></i>Editar

                                                    </a>

                                                </li>

                                                <li>

                                                    <a class="dropdown-item" onclick='new Request({"request": {"path" : "action/users/users_delete"}, "params" : {"user_id" : "<?php echo $result->user_id ?>"}})'>

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

                </div>

                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" tabindex="0">

                    <div class="card">

                        <div class="card-body">

                            <div id="pieChart" style="width:800px; height:500px;"></div>

                            <script type="text/javascript">
                                // Initialize the echarts instance based on the prepared dom
                                var myChart = echarts.init(document.getElementById('pieChart'), null, {
                                    renderer: 'svg'
                                });

                                // Specify the configuration items and data for the chart
                                var option = {
                                    grid: {
                                        top: '10%',
                                        bottom: '10%',
                                        left: '5%',
                                        right: '5%',
                                    },
                                    toolbox: {
                                        show: true,
                                        feature: {
                                            dataView: {
                                                readOnly: false
                                            },
                                            saveAsImage: {
                                                type: 'png' // Salvar como imagem PNG
                                            },
                                        }
                                    },
                                    tooltip: {
                                        trigger: 'item'
                                    },
                                    legend: {
                                        orient: 'vertical',
                                        left: 'left'
                                    },
                                    xAxis: {
                                        type: 'category',
                                        data: [
                                            <?php foreach ($LogsAllByCompanyIdResult as $key => $result) { ?>

                                                '<?php echo $result->date_register_formated ?>',

                                            <?php } ?>
                                        ]
                                    },
                                    yAxis: {
                                        type: 'value'
                                    },
                                    series: [{
                                        data: [
                                            <?php foreach ($LogsAllByCompanyIdResult as $key => $result) { ?>

                                                <?php echo $result->quantity ?>,

                                            <?php } ?>
                                        ],
                                        type: 'line'
                                    }]
                                };

                                // Display the chart using the configuration items and data just specified.
                                myChart.setOption(option);

                                /** Adiciono um evento para monitorar o redimensionamento da tela */
                                window.addEventListener('resize', function() {

                                    /** Verifico se o gráfico existe */
                                    if (myChart !== null && myChart !== undefined) {

                                        /** Redimensiono o gráfico */
                                        myChart.resize();

                                    }

                                });
                            </script>

                        </div>

                    </div>

                </div>

            </div>

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
                'title' => 'Empresas / Visão Geral / Detalhes',
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
