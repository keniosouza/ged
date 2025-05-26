<?php

/** Importação de classes */

use src\model\Users;
use src\controller\users\UsersValidate;

try {

    /** Instânciamento de classes */
    $Users = new Users();
    $UsersValidate = new UsersValidate();

    /** Controle de resultados */
    $result = null;

    /** Parametros de entrada  */
    $userId          = isset($_SESSION['USERSID'])       ? (int)$_SESSION['USERSID']                                                           : 0;
    $email           = isset($_SESSION['USERSEMAIL'])    ? (string)$_SESSION['USERSEMAIL']                                                     : '';
    $passwordInform  = isset($_POST['password-inform'])  ? (string)filter_input(INPUT_POST, 'password-inform', FILTER_SANITIZE_SPECIAL_CHARS)  : '';
    $passwordConfirm = isset($_POST['password-confirm']) ? (string)filter_input(INPUT_POST, 'password-confirm', FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $csrfToken       = isset($_POST['csrf_token'])       ? (string)filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS)       : null;

    /** Verifica se o Token CSRF é válido */
    if ($csrfToken === $_SESSION['csrf_token']) {

        /** Validando os campos de entrada */
        $UsersValidate->setUserId($userId);
        $UsersValidate->setEmail($email);
        $UsersValidate->setPasswordInform($passwordInform);
        $UsersValidate->setPasswordConfirm($passwordConfirm);

        /** Verifico a existência de erros */
        if (!empty($UsersValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($UsersValidate->getErrors(), 0);
        } else {

            /** Atualiza a nova senha */
            if ($Users->SaveNewPassword(
                $UsersValidate->getUserId(),
                $UsersValidate->getPasswordInform()
            )) {

                /** Result **/
                $result = [

                    'code' => 200,
                    'data' => 'Senha atualizada com sucesso',
                    'toast' => [
                        [
                            'background' => 'success',
                            'data' => '<i class="bi bi-check me-1"></i>Senha atualizada com sucesso, redirecionando para a página de login'
                        ]
                    ],
                    'reload' => [
                        [
                            'url' => str_replace('app/', '', $Main->getUrlApp()),
                        ]
                    ],
                ];


                /** Envio **/
                echo json_encode($result);

                /** Para o procedimento */
                exit;
            } else {

                /** Informo */
                throw new InvalidArgumentException('Erro ao atualizar a senha', 0);
            }
        }
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

    /** Para o procedimento */
    exit;
}
