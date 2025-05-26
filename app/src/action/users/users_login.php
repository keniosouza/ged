<?php

/** Importação de classes  */

use src\controller\mail\Mail;
use src\controller\main\Main;
use src\model\UsersAcls;
use src\model\Users;
use src\controller\users\UsersValidate;

try {

    /** Instânciamento de classes  */
    $Mail = new Mail();
    $Main = new Main();
    $UsersAcls = new UsersAcls();
    $Users = new Users();
    $UsersValidate = new UsersValidate();

    /** Controle de resultados */
    $result = null;

    /** Parametros de entrada */
    $email          = isset($_POST['email'])           ? (string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL)                   : '';
    $password       = isset($_POST['password'])        ? (string) filter_input(INPUT_POST, 'password', FILTER_SANITIZE_EMAIL)                : '';
    $rememberAccess = isset($_POST['remember_access']) ? (string) filter_input(INPUT_POST, 'remember_access', FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $csrfToken      = isset($_POST['csrf_token'])      ? (string)filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS)       : null;

    /** Verifica se o Token CSRF é válido */
    if ($csrfToken === $_SESSION['csrf_token']) {

        /** Validando os campos de entrada */
        $UsersValidate->setEmail($email);
        $UsersValidate->setPassword($password);

        /** Verifico a existência de erros */
        if (!empty($UsersValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($UsersValidate->getErrors(), 0);
        } else {

            /** Busco o email informado */
            $UsersGetByEmailResult = $Users->GetByEmail($UsersValidate->getEmail());

            /** Verifico se o email foi localizado */
            if (!empty($UsersGetByEmailResult->email)) {

                /** Verifico se as senhas são iguais */
                if (password_verify($UsersValidate->getPassword(), $UsersGetByEmailResult->password)) {

                    // Controle de preferencias
                    $preferences = [];

                    // Estruturo as permissões
                    foreach ($UsersAcls->AllByUserId($UsersGetByEmailResult->user_id) as $key => $result) {

                        // Decodifico as preferencias
                        $result->preferences = (array) json_decode($result->preferences);

                        // Percorro todas as preferencias
                        foreach ($result->preferences as $keyPreference => $resultPreference) {

                            // Guardo a permissão de acordo com o nome
                            $preferences[$result->module_description][$resultPreference] = true;
                        }
                    }

                    // Guardos as preferencias estruturadas
                    $UsersGetByEmailResult->preferences = $preferences;

                    /** Crio as variaveis da sesão */
                    $_SESSION['MY_SAAS_USER'] = $UsersGetByEmailResult;

                    /** Gera o cookie do usuário com duração de um dia */
                    setcookie("UserEmail", $Main->encryptData($UsersGetByEmailResult->email), time() + (86400 * 30), "/"); // 86400 = 1 day

                    if ($rememberAccess == 'S') {

                        setcookie("UserPassword", $Main->encryptData($UsersValidate->getPassword()), time() + (86400 * 30), "/"); // 86400 = 1 day
                        setcookie("RememberAccess", $rememberAccess, time() + (86400 * 30), "/"); // 86400 = 1 day

                    }

                    /** Verifica se é ambiente de produção */
                    if ($Main->getEnvironment() == 'production') {

                        /** Inicio a coleta de dados */
                        ob_start();

                        /** Inclusão do arquivo desejado */
                        @include_once 'src/view/users/users_login_email.php';

                        /** Prego a estrutura do arquivo */
                        $html = ob_get_contents();

                        /** Removo o arquivo incluido */
                        ob_clean();

                        /** Realizo o envio do email */
                        $Mail->Send($html, $UsersGetByEmailResult, 'Login Realizado: ' . date('d/m/Y H:i:s'), $MainGetConfigResult->mail);
                    }

                    // Result
                    $result = [

                        'code' => 200,
                        'toast' => [
                            [
                                'background' => 'primary',
                                'data' => 'Usuário localizado!'
                            ]
                        ],
                        'reload' => [
                            [
                                'url' => $Main->getUrlApp()
                            ]
                        ],

                    ];
                } else {

                    /** Informo */
                    throw new InvalidArgumentException('<div class="alert alert-warning mt-2" role="alert">A senha informada é inválida</div>', 0);
                }
            } else {

                /** Informo */
                throw new InvalidArgumentException('<div class="alert alert-warning mt-2" role="alert">Não foi localizado usuário para o email informado</div>', 0);
            }
        }

        /** Envio */
        echo json_encode($result);

        /** Paro o procedimento */
        exit;
    } else {

        /** Informo */
        throw new InvalidArgumentException('Token CSRF inválido', 0);
    }
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
