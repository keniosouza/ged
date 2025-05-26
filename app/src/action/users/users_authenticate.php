<?php

/** Importação de classes  */

use src\model\Users;
use src\controller\users\UsersValidate;

try {

    /** Instânciamento de classes  */
    $Users = new Users();
    $UsersValidate = new UsersValidate();

    /** Controle de resultados */
    $result = null;

    /** Parametros de entrada */
    $email    = isset($_POST['email'])    ? (string)filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL)            : '';
    $password = isset($_POST['password']) ? (string)filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS) : '';

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

                /** Inicializo a sesão */
                session_start();

                /** Crio as variaveis da sesão */
                $_SESSION['MY_SAAS_USER'] = $UsersGetByEmailResult;

                // Result
                $result = [

                    'code' => 200,
                    'toast' => [
                        [
                            'background' => 'primary',
                            'data' => 'Usuário localizado!'
                        ]
                    ],
                    'procedure' => [
                        [
                            'name' => 'HideModal',
                            'options' => [
                                'target' => 'CallActivityContextMenuZone'
                            ]
                        ]
                    ]

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
