<?php

/** Ativo a exibição de erros */
error_reporting(E_ALL);
ini_set('display_errors', 'On');

/** Importação do Autoload lado servidor */
require_once('./app/autoload.php');

// IMportação de classes
use src\controller\main\Main;

// Instânciamento de classes
$Main = new Main('app'); 

/** Inicializa a sessão */
$Main->startSession();

/** Sanitiza os parametros de entrada */
$newPassword = isset($_GET['new-password']) ? filter_input(INPUT_GET, 'new-password', FILTER_SANITIZE_SPECIAL_CHARS) : null;
$hash        = isset($_GET['hash'])         ? filter_input(INPUT_GET, 'hash', FILTER_SANITIZE_SPECIAL_CHARS)         : null;
$expired     = false;
$formNewPwd  = false;

/** Verifica se é uma solicitação de nova senha */
if (!is_null($newPassword) && !is_null($hash)) {

    /** Descriptografa o hash */
    $hash = $Main->decryptData(str_replace(' ', '+', $_GET['hash']));

    /** Separa a informação do hash */
    $info = explode('*', $hash);

    /** Armazena o email e o id do usuario em sessão */
    $_SESSION['USERSEMAIL'] = $info[0];
    $_SESSION['USERSID'] = $info[1];

    /** Verifica se o link expirou */
    if (strtotime(date('Y-m-d', strtotime($info[2]))) < strtotime(date('Y-m-d'))) {

        /** Informa que o 
         * link expirou */
        $expired = true;
    }

    /** Informa para habilitar o 
     * formulário de alterar a senha */
    $formNewPwd = true;
}


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <base href="<?php echo str_replace('app/', '', $Main->getUrlApp()); ?>" />
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keyword" content="">
    <meta name="author" content="WRAPCODERS">

    <title>DOCVERSO || Tela de Acesso</title>

    <link rel="shortcut icon" type="image/x-icon" href="<?php echo str_replace('app/', '', $Main->getUrlApp()); ?>/app/assets/images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="<?php echo str_replace('app/', '', $Main->getUrlApp()); ?>/app/assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo str_replace('app/', '', $Main->getUrlApp()); ?>/app/assets/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo str_replace('app/', '', $Main->getUrlApp()); ?>/app/assets/css/theme.min.css">

</head>

<body>

    <!-- Modal -->
    <div id="wrapper-modal"></div>

    <!-- Modal -->
    <div id="wrapper-toast"></div>

    <div class="position-absolute start-0 end-0 start-0 bottom-0 w-100 h-100">

        <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 800 800">

            <g fill-opacity="0.03">

                <circle style="fill: rgba(var(--ct-primary-rgb), 0.1);" cx="400" cy="400" r="600"></circle>
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.2);" cx="400" cy="400" r="500"></circle>
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.3);" cx="400" cy="400" r="300"></circle>
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.4);" cx="400" cy="400" r="200"></circle>
                <circle style="fill: rgba(var(--ct-primary-rgb), 0.5);" cx="400" cy="400" r="100"></circle>

            </g>

        </svg>

    </div>

    <?php

    /** Verifica se o link de nova senha está ativo */
    if (($expired === false) && ($formNewPwd === true)) { ?>

        <main class="auth-minimal-wrapper">
            <div class="auth-minimal-inner">
                <div class="minimal-card-wrapper">
                    <div class="card mb-4 mt-5 mx-4 mx-sm-0 position-relative">

                        <div class="card-body p-sm-5">
                            <h2 class="fs-20 fw-bolder mb-4">
                                <img src="./app/assets/images/logo-full.png" alt="" class="img-fluid">
                            </h2>
                            <h2 class="fs-20 fw-bolder mb-4">Cadastre uma nova senha</h2>
                            <p class="alert alert-danger">A senha precisa ter letras, números e, se possível, caracteres especiais, e um máximo de 10 dígitos.</p>
                            <form action="javascript:void(0)" id="UsersNewPassForm" class="w-100 mt-4 pt-2">

                                <div class="mb-4">
                                    <div class="form-floating">
                                        <input class="form-control" value="<?php echo $info[0]; ?>" disabled>
                                        <label for="floatingPosition">E-mail</label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-floating">
                                        <input type="password" id="password-inform" maxlength="10" name="password-inform" class="form-control" data-required="S" data-bs-toggle="tooltip" data-bs-title="Informe a nova senha">
                                        <label for="floatingPosition">Senha</label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-floating">
                                        <input type="password" id="password-confirm" maxlength="10" name="password-confirm" class="form-control" data-required="S" data-bs-toggle="tooltip" data-bs-title="Confirme a nova senha">
                                        <label for="floatingPosition">Confirmar Senha</label>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <button type="button" id="btnSavePassword" class="btn btn-lg btn-primary w-100" onclick='validateForm("#UsersLoginForm", `{"request": {"path" : "action/users/users_save_new_password"}, 
                                                                                                                                          "loader" : {"type" : 1, "target" : "btnSavePassword"}, 
                                                                                                                                          "form" : "UsersNewPassForm", 
                                                                                                                                          "response" : {"target" : "UsersResetFormResponse"}}`, "app")'>
                                        CADASTRAR SENHA
                                    </button>
                                </div>

                                <span id="UsersResetFormResponse"></span>
                                <input type="hidden" name="csrf_token" value="<?php echo $Main->getCSRF(); ?>">
                            </form>
                            <div class="mt-5 text-muted">
                                <span> Ja possui uma senha?</span>
                                <a href="login" class="fw-bold">Clique aqui para acessar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>


    <?php

    } else {

    ?>
        <main class="auth-creative-wrapper">
            <div class="auth-creative-inner">
                <div class="creative-card-wrapper">
                    <div class="card my-4 overflow-hidden" style="z-index: 1">
                        <div class="row flex-1 g-0">
                            <div class="col-lg-6 h-100 my-auto order-1 order-lg-0">

                                <div class="creative-card-body card-body p-sm-5">
                                    <h2 class="fs-20 fw-bolder mb-4">
                                        <img src="./app/assets/images/logo-full.png" alt="" class="img-fluid">
                                    </h2>
                                    <h4 class="fs-13 mb-2">
                                        Digite seu endereço de e-mail e senha para acessar o sistema.
                                    </h4>
                                    <!-- <p class="fs-12 fw-medium text-muted"> </p> -->
                                    <form action="javascript:void(0)" class="w-100 mt-4 pt-2" id="UsersLoginForm">
                                        <div class="mb-4">

                                            <div class="form-floating">
                                                <input id="email" name="email" type="email" class="form-control" maxlength="160" data-required="S" data-bs-toggle="tooltip" data-bs-title="Informe o e-mail" value="<?php echo isset($_COOKIE['RememberAccess']) ? $Main->decryptData($_COOKIE['UserEmail']) : ''; ?>" required>
                                                <label for="floatingPosition">
                                                    Informe seu e-mail
                                                </label>
                                            </div>

                                        </div>
                                        <div class="mb-3">

                                            <div class="form-floating">
                                                <input id="password" name="password" type="password" class="form-control" maxlength="10" value="<?php echo isset($_COOKIE['RememberAccess']) ? $Main->decryptData($_COOKIE['UserPassword']) : ''; ?>" required>
                                                <label for="floatingPosition">
                                                    Informe sua senha
                                                </label>
                                            </div>

                                        </div>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="remember_access" name="remember_access" value="S" <?php echo isset($_COOKIE['RememberAccess']) ? 'checked' : ''; ?>>
                                                    <label class="custom-control-label c-pointer" for="rememberMe">Lembrar
                                                        dados de acesso
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="mt-5">
                                            <button id="UsersBtnLogin" class="btn btn-lg btn-primary w-100" onclick='new Request({"request": {"path" : "action/users/users_login"}, 
                                                                                                                                  "loader" : {"type" : 1, "target" : "UsersBtnLogin"}, 
                                                                                                                                  "form" : "UsersLoginForm", 
                                                                                                                                  "response" : {"target" : "UsersLoginFormResponse"}}, "app")'>
                                                Autenticar Usuário
                                            </button>
                                        </div>

                                        <span id="UsersLoginFormResponse"></span>
                                        <input type="hidden" name="csrf_token" value="<?php echo $Main->getCSRF(); ?>">
                                    </form>


                                </div>
                            </div>
                            <div class="col-lg-6 bg-primary order-0 order-lg-1">
                                <div class="h-100 d-flex align-items-center justify-content-center">

                                    <div class="row p-4">

                                        <div class="col-lg-12 text-center text-white">

                                            <h2 class="text-white">Bem vindo de volta!</h2>

                                            Esqueceu a senha?

                                            <div class="text-center pt-2">
                                                <button class="btn btn-light" id="btnReset" onclick='validateForm("#UsersLoginForm", `{"request": {"path" : "action/users/users_reset"}, 
                                                                                                                        "loader" : {"type" : 1, "target" : "btnReset"}, 
                                                                                                                        "form" : "UsersLoginForm", 
                                                                                                                        "response" : {"target" : "UsersResetFormResponse"}}`, "app")'>
                                                    Solicitar nova senha
                                                </button>
                                                <span id="UsersResetFormResponse"></span>
                                            </div>

                                        </div>

                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

    <?php } ?>

    <script src="<?php echo str_replace('app/', '', $Main->getUrlApp()); ?>app/assets/vendors/js/vendors.min.js"></script>
    <script src="<?php echo str_replace('app/', '', $Main->getUrlApp()); ?>app/assets/js/common-init.min.js"></script>
    <script src="<?php echo str_replace('app/', '', $Main->getUrlApp()); ?>app/assets/js/theme-customizer-init.min.js"></script>
    <script src="<?php echo str_replace('app/', '', $Main->getUrlApp()); ?>app/assets/js/jquery.mask.min.js"></script>
    <script src="<?php echo str_replace('app/', '', $Main->getUrlApp()); ?>app/assets/js/jquery.price.format.js"></script>
    <script src="<?php echo str_replace('app/', '', $Main->getUrlApp()); ?>app/assets/src/utils/loader.js"></script>
    <script src="<?php echo str_replace('app/', '', $Main->getUrlApp()); ?>app/assets/src/utils/modal.js"></script>
    <script src="<?php echo str_replace('app/', '', $Main->getUrlApp()); ?>app/assets/src/utils/toast.js"></script>
    <script src="<?php echo str_replace('app/', '', $Main->getUrlApp()); ?>app/assets/src/services/response.js"></script>
    <script src="<?php echo str_replace('app/', '', $Main->getUrlApp()); ?>app/assets/src/services/router.js"></script>
    <script src="<?php echo str_replace('app/', '', $Main->getUrlApp()); ?>app/assets/src/utils/offcanva.js"></script>
    <script src="<?php echo str_replace('app/', '', $Main->getUrlApp()); ?>app/assets/src/utils/main.js"></script>

    <script type="text/javascript">
        /** Operações ao carregar a página */
        $(document).ready(function(e) {

            /** Verifica se o link 
             * de renovar a senha expirou */
            if ('<?php echo $expired; ?>' == true) {

                /** Exibe a mensagem de erro */
                new Toast({
                    create: true,
                    background: 'warning',
                    text: 'O link de renovação de senha expirou, solicite um novo link.',
                });
            }

            if (document.getElementById("password-inform")) {

                /** Coloca o foco no campo e-mail */
                $('input[name="password-inform"]').focus();
            }

            if (document.getElementById("email")) {

                /** Coloca o foco no campo e-mail */
                $('input[name="email"]').focus();


                /** Ao pressionar enter no campo e-mail, avança para o campo password */
                $('input[name="email"]').keypress(function(event) {

                    var keycode = (event.keyCode ? event.keyCode : event.which);

                    if (keycode == '13') {

                        //Coloco o foco no campo de senha
                        $('input[name="password"]').focus();

                    }

                    event.stopPropagation();

                });


                $('input[name="password"]').keypress(function(event) {

                    var keycode = (event.keyCode ? event.keyCode : event.which);

                    if (keycode == '13') {

                        /** Envia a solicitação de acesso */
                        new Request({
                            "request": {
                                "path": "action/users/users_login"
                            },
                            "loader": {
                                "type": 1,
                                "target": "UsersBtnLogin"
                            },
                            "form": "UsersLoginForm",
                            "response": {
                                "target": "UsersLoginFormResponse"
                            }
                        }, "app");

                    }

                    event.stopPropagation();

                });
            }

        });
    </script>

</body>

</html>