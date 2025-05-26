<?php

/** Importação de classes */

use src\model\Users;
use src\controller\users\UsersValidate;

try {

    /** Instânciamento de classes */
    $Users = new Users();
    $UsersValidate = new UsersValidate();

    /** Parâmetros de paginação **/
    $start  = isset($_POST['start'])  ? (int)filter_input(INPUT_POST, 'start',  FILTER_SANITIZE_NUMBER_INT) : 0;
    $page   = isset($_POST['page'])   ? (int)filter_input(INPUT_POST, 'page',  FILTER_SANITIZE_NUMBER_INT)  : 0;
    $max    = $Main->getRows() > 0    ? $Main->getRows()                                                    : 20; //Se não houver definição de quantidade de registros, define 20 com padrão
    $search = isset($_POST['search']) ? filter_input(INPUT_POST, 'search', FILTER_SANITIZE_SPECIAL_CHARS)   : '';

    /** Sanitiza os paramentros de entrada */
    $UsersValidate->setStart($start);
    $UsersValidate->setPage($page);
    $UsersValidate->setMax($max);
    $UsersValidate->setSearch($search);

    /** Consulta a quantidade de registros */
    $rows = $Users->Count($UsersValidate->getSearch());

    /** Busco todos os usuários */
    $UsersAllResult = $Users->All(
        $UsersValidate->getStart(),
        $UsersValidate->getMax(),
        $UsersValidate->getSearch()
    );

?>

    <!-- [ page-header ] start -->
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Usuários</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="home">Home</a></li>
                <li class="breadcrumb-item">Usuários cadastrados</li>
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


                <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">

                    <form id="frmSearch" class="float-end w-100" action="javascript:void">

                        <div class="input-group">

                            <input type="text"
                                class="form-control"
                                data-bs-toggle="tooltip"
                                data-bs-title="Informe sua pesquisa aqui..."
                                placeholder="Informe sua pesquisa aqui..."
                                id="search"
                                name="search"
                                wfd-id="id6"
                                value="<?php echo $UsersValidate->getSearch(); ?>"
                                data-required="S">

                            <button class="btn btn-outline-secondary" id="btnSearch" type="button" onclick='validateForm("#frmSearch", `{"request": {"path" : "view/users/users_index"},
                                                                                                                         "loader" : {"type" : 1, "target" : "btnSearch"}, 
                                                                                                                         "response" : {"target" : "app-main"},
                                                                                                                         "form" : "frmSearch"}`)'>


                                <i class="bi bi-search"></i>

                            </button>

                        </div>

                    </form>

                    <button class="btn btn-primary" onclick='new Request({"request" : {"path" : "view/users/users_form"}, 
                                                                          "loader" : {"type" : 3},                                                                          
                                                                          "form" : null})'>
                        <i class="bi bi-plus"></i> Adicionar
                    </button>

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

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-hover">

                                <thead>

                                    <tr>

                                        <th scope="col" class="text-center">

                                            #

                                        </th>

                                        <th scope="col" class="text-center">

                                            Cadastro

                                        </th>

                                        <th scope="col" class="text-center">

                                            Status

                                        </th>

                                        <th scope="col" class="text-center">

                                            Nome/Email

                                        </th>

                                        <th scope="col" class="text-center">

                                            Equipe/Função

                                        </th>

                                        <th scope="col" class="text-center">



                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php

                                    /** Percorro todos os itens localizados */
                                    foreach ($UsersAllResult as $key => $result) { ?>

                                        <tr class="align-middle border-top" id="<?php echo $result->user_id ?>">

                                            <td scope="row" class="text-center" width="90">

                                                <?php echo $result->user_id ?>

                                            </td>

                                            <td scope="row" class="text-center" width="90">

                                                <?php echo date('d/m/Y', strtotime($result->date_register)) ?>

                                            </td>

                                            <td class="text-center" width="90">

                                                <span class="badge text-bg-<?php echo $result->status == 'A' ? 'success' : 'danger'; ?>"><?php echo $result->status == 'A' ? 'Ativo' : 'Inativo'; ?></span>

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

                                            <td class="text-center" width="60" id="<?php echo $result->user_id ?>">

                                                <div class="dropdown">

                                                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                                                        <i class="bi bi-three-dots"></i>

                                                    </button>

                                                    <ul class="dropdown-menu shadow-sm">

                                                        <li>

                                                            <a class="dropdown-item" onclick='new Request({"request": {"path" : "view/users/users_form"}, 
                                                                                                    "loader" : {"type" : 3},   
                                                                                                    "params" : {"user_id" : "<?php echo $result->user_id ?>"}})'>

                                                                <i class="bi bi-pencil me-1"></i>Editar

                                                            </a>

                                                        </li>

                                                        <li>

                                                            <a class="dropdown-item" onclick='new Request({"request": {"path" : "action/users/users_delete"}, 
                                                                                                       "params" : {"user_id" : "<?php echo $result->user_id ?>"},
                                                                                                       "loader" : {"type" : 3}})'>

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

                    </div> <!-- /.card-body -->
                    <div class="card-footer clearfix">

                        <?php

                        /** Verifica se a quantidade de 
                         * registros é maior que o máximo por página,
                         * caso seja, gera a paginação de registros */
                        if ($rows > $max) {

                            /** Monta os parametros a 
                             * serem passados na paginação */
                            $jsonData = [];

                            /** Link da solicitação */
                            $jsonData['request'] = [];
                            $jsonData['request']['path'] = 'view/users/users_index';

                            /** Loader da solicitação 
                             * Tipo 1 => Informar target
                             * Tipo 2 => Informar target
                             * Tipo 3 => Não informar target
                             */
                            $jsonData['loader'] = [];
                            $jsonData['loader']['type'] = 3;
                            //$jsonData['loader']['target'] = 'app-main';

                            /** Informar o target de resposta */
                            $jsonData['response'] = [];
                            $jsonData['response']['target'] = 'app-main';

                            /** Mostra a paginação na tela */
                            echo $Main->pagination($rows, $start, $max, $page, $jsonData, '#frmSearch');
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        /** Operações ao carregar a página */
        $(document).ready(function(e) {

            /** Efetua a consulta caso clique em enter */
            $('input[name="search"]').keypress(function(event) {

                /** Captura o clicar da tecla */
                var keycode = (event.keyCode ? event.keyCode : event.which);

                /** Verifica se a tecla pressionada é o enter */
                if (keycode == '13') {

                    event.stopPropagation();

                    /** Efetua a validação do formulário */
                    validateForm("#frmSearch", `{"request": {"path" : "view/users/users_index"},
                                                             "loader" : {"type" : 1, "target" : "btnSearch"}, 
                                                             "response" : {"target" : "app-main"},
                                                             "form" : "frmSearch"}`)

                }

            });

        });
    </script>

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
