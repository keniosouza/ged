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

    /** Parametros de entrada */
    $userId = isset($_POST['user_id']) ? (int)filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Validando os campos de entrada */
    $UsersValidate->setUserId($userId);

    /** Verifico a existência de erros */
    if (!empty($UsersValidate->getErrors())) {

        /** Informo */
        throw new InvalidArgumentException($UsersValidate->getErrors(), 0);
    } else {

        /** Efetua um novo cadastro ou salva os novos dados */
        if ($Users->Delete($UsersValidate->getUserId(), $_SESSION['MY_SAAS_USER']->user_id)) {

            // Result
            $result = [

                'code' => 200,
                'data' => 'Perfil Atualizado',
                'toast' => [
                    [
                        'background' => 'primary',
                        'data' => '<i class="bi bi-trash me-1"></i>Registro removido'
                    ]
                ],
                'remove' => [
                    [
                        'id' => $UsersValidate->getUserId(),
                    ]
                ]

            ];
        } else {

            /** Retorno mensagem de erro */
            throw new InvalidArgumentException('Não foi possivel remover o registro', 0);
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
