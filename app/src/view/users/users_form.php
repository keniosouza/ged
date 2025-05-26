<?php

/** Importação de classes */

use src\model\Users;
use src\model\Companies;
use src\controller\users\UsersValidate;

try {

    /** Instânciamento de classes */
    $Users = new Users();
    $Companies = new Companies();
    $UsersValidate = new UsersValidate();

    /** Parametros de entrada */
    $companyId = isset($_POST['company_id']) ? (int)filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_NUMBER_INT) : 0;
    $userId    = isset($_POST['user_id'])    ? (int)filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT)    : 0;

    /** Sanitiza os itens de entrada */
    $UsersValidate->setCompanyId($companyId);
    $UsersValidate->setUserId($userId);

    /** Busco o registro desejado */
    $UsersGetResult = $Users->Get($UsersValidate->getUserId());

    /** Verifica se o objeto foi carregado,
     * caso não tenha sido, cria o mesmo vazio
     */
    if (!is_object($UsersGetResult)) {

        /** Monta o objeto de acordo com a tabela */
        $UsersGetResult = $Users->Describe();
    }

?>

    <form id="UsersForm" class="row g-2">

        <div class="col-md-12" id="UsersFormResponse"></div>

        <div class="col-md-12">

            <div class="form-floating">

                <input type="text" class="form-control" id="floatingName" name="name" value="<?php echo (string)$UsersGetResult->name ?>">
                <label for="floatingName">Nome</label>

            </div>

        </div>

        <div class="col-md-3">

            <div class="form-floating">

                <input type="text" class="form-control" id="floatingTeam" name="team" value="<?php echo (string)$UsersGetResult->team ?>">
                <label for="floatingTeam">Time</label>

            </div>

        </div>

        <div class="col-md-3">

            <div class="form-floating">

                <input type="text" class="form-control" id="floatingPosition" name="position" value="<?php echo (string)$UsersGetResult->position ?>">
                <label for="floatingPosition">Cargo</label>

            </div>

        </div>

        <div class="col-md-3">

            <div class="form-floating">


                <select class="form-select" id="status" name="status">


                    <option value="A" <?php echo $UsersGetResult->status === 'A' ? 'selected' : null ?>>

                        Ativo

                    </option>

                    <option value="I" <?php echo $UsersGetResult->status === 'I' ? 'selected' : null ?>>

                        Inativo

                    </option>

                    <option value="D" <?php echo $UsersGetResult->status === 'D' ? 'selected' : null ?>>

                        Remover

                    </option>

                </select>

                <label for="floatingPosition">Situação</label>

            </div>

        </div>

        <div class="col-md-3">

            <div class="form-floating">


                <select class="form-select" id="password_temp_confirm" name="password_temp_confirm">


                    <option value="S" <?php echo $UsersGetResult->password_temp_confirm === 'S' ? 'selected' : null ?>>

                        Sim

                    </option>

                    <option value="N" <?php echo $UsersGetResult->password_temp_confirm === 'N' || empty($UsersGetResult->password_temp_confirm)  ? 'selected' : null ?>>

                        Não

                    </option>

                </select>

                <label for="floatingPosition">Senha temporária</label>

            </div>

        </div>

        <div class="col-md-8">

            <div class="form-floating">

                <input type="email" class="form-control" id="floatingEmail" name="email" value="<?php echo (string)$UsersGetResult->email ?>">
                <label for="floatingEmail">Email</label>

            </div>

        </div>

        <div class="col-md-4">

            <div class="form-floating">

                <input type="text" class="form-control" id="password_temp" disabled name="password_temp" value="<?php echo isset($UsersGetResult->password_temp) && !empty($UsersResult->password_temp) ? $UsersResult->password_temp : $Main->NewPassword(); ?>">
                <label for="password_temp">Senha temporária</label>

            </div>

        </div>

        <?php

        // Verifico se já existe a identificação da empresa, caso sim, ingora a seleção
        if ($companyId === 0) { ?>

            <div class="col-md-12">

                <select class="form-select" id="company_id" name="company_id">

                    <?php

                    /** Percorro todos os itens localizados */
                    foreach ($Companies->All(null, null, null) as $key => $result) { ?>

                        <option value="<?php echo $result->company_id ?>" <?php echo $result->company_id === $UsersGetResult->company_id ? 'selected' : null ?>>

                            <?php echo $result->name_business ?>

                        </option>

                    <?php } ?>

                </select>

            </div>

        <?php } ?>

        <div class="row">

            <div class="col-md-12 text-center p-2">


                <!-- Salva os dados no banco de dados -->
                <button class="btn btn-primary m-1 float-end" id="btnUsersSave" onclick='new Request({"request" : {"path" : "action/users/users_save"}, 
                                                                                                   "loader" : {"type" : 1, 
                                                                                                   "target" : "btnUsersSave"}, 
                                                                                                   "response" : {"target" : "UsersFormResponse"}, 
                                                                                                   "form" : "UsersForm"})' type="button">

                    <i class="bi bi-check me-1"></i>Salvar

                </button>

                <!-- Cancela o formulário -->
                <button class="btn btn-danger m-1 float-end" data-bs-dismiss="modal" aria-label="Close" type="button">

                    <i class="bi bi-x me-1"></i>Cancelar

                </button>

            </div>

        </div>

        <input type="hidden" name="csrf_token" value="<?php echo $Main->getCSRF(); ?>">
        <input type="hidden" name="user_id" value="<?php echo (int)$UsersGetResult->user_id ?>">

        <?php

        // Verifico se já existe a identificação da empresa, caso sim, cria o input hiddem
        if ($companyId > 0) { ?>

            <input type="hidden" name="company_id" value="<?php echo $companyId ?>">

        <?php } ?>

    </form>

    <script type="text/javascript">
        /** Carrega as mascaras dos campos inputs */
        $(document).ready(function(e) {

            enabledInput('#password_temp_confirm', '#password_temp');
            loadMask();

        });
    </script>

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
                'title' => $UsersValidate->getUserId() > 0 ? 'Editando Cadastro' : 'Cadastrar Usuário',
                'data' => $data,
                'size' => 'lg',
                'type' => null,
                'procedure' => null
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
