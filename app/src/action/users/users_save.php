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
    $path                 = isset($_POST['path'])                  ? (string)filter_input(INPUT_POST, 'path', FILTER_SANITIZE_SPECIAL_CHARS)                  : '';
    $name                 = isset($_POST['name'])                  ? (string)filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS)                  : '';
    $team                 = isset($_POST['team'])                  ? (string)filter_input(INPUT_POST, 'team', FILTER_SANITIZE_SPECIAL_CHARS)                  : '';
    $position             = isset($_POST['position'])              ? (string)filter_input(INPUT_POST, 'position', FILTER_SANITIZE_SPECIAL_CHARS)              : '';
    $status               = isset($_POST['status'])                ? (string)filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS)                : '';
    $passwordTempConfirm  = isset($_POST['password_temp_confirm']) ? (string)filter_input(INPUT_POST, 'password_temp_confirm', FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $email                = isset($_POST['email'])                 ? (string)filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL)                         : '';
    $passwordTemp         = isset($_POST['password_temp'])         ? (string)filter_input(INPUT_POST, 'password_temp', FILTER_SANITIZE_SPECIAL_CHARS)         : '';
    $companyId            = isset($_POST['company_id'])            ? (int)filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_NUMBER_INT)                  : 0;
    $userId               = isset($_POST['user_id'])               ? (int)filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_NUMBER_INT)                     : 0;
    $csrfToken            = isset($_POST['csrf_token'])            ? (string)filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS)            : null;


    /** Verifica se o Token CSRF é válido */
    if ($csrfToken === $_SESSION['csrf_token']) {


        /** Validando os campos de entrada */
        $UsersValidate->setName($name);
        $UsersValidate->setTeam($team);
        $UsersValidate->setPosition($position);
        $UsersValidate->setStatus($status);
        $UsersValidate->setPasswordTempConfirm($passwordTempConfirm);
        $UsersValidate->setEmail($email);
        $UsersValidate->setPasswordTemp($passwordTemp);
        $UsersValidate->setCompanyId($companyId);
        $UsersValidate->setUserId($userId);


        /** Verifico a existência de erros */
        if (!empty($UsersValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($UsersValidate->getErrors(), 0);
        } else {

            /** Efetua um novo cadastro ou salva os novos dados */
            if ($Users->Save(
                $_SESSION['MY_SAAS_USER']->user_id,
                $UsersValidate->getUserId(),
                $UsersValidate->getName(),
                $UsersValidate->getTeam(),
                $UsersValidate->getPosition(),
                $UsersValidate->getStatus(),
                $UsersValidate->getPasswordTempConfirm(),
                $UsersValidate->getEmail(),
                $UsersValidate->getPasswordTemp(),
                $UsersValidate->getCompanyId()
            )) {

                /** Prepara a mensagem de retorno - sucesso */
                $result = [

                    'code' => 200,
                    'data' => ($UsersValidate->getUserId() > 0 ? 'Cadastro atualizado com sucesso' : 'Cadastro efetuado com sucesso'),
                    'toast' => [
                        [
                            'background' => ($UsersValidate->getStatus() == 'D' ? 'warning' : 'success'),
                            'data' => ($UsersValidate->getStatus() == 'D' ? '<i class="bi bi-trash3 me-1"></i>Cadastro removido com sucesso' : '<i class="bi bi-check me-1"></i>Dados salvos com sucesso')
                        ]
                    ],
                ];

                /** Verifica se existem algum item a ser excluído */
                if ($UsersValidate->getStatus() == 'D') {

                    $result["remove"] = [
                        [
                            'id' => $UsersValidate->getStatus() == 'D' ? $UsersValidate->getUserId() : null
                        ]
                    ];
                } else {

                    $result["redirect"] = [
                        [
                            'path' => 'view/users/users_index',
                            'type' => 3,
                            'target' => 'app-main'
                        ]
                    ];
                }
            } else {

                /** Informo */
                throw new InvalidArgumentException(($UsersValidate->getUserId() > 0 ? 'Não foi possível atualizar o cadastro' : 'Não foi possível efetuar o cadastro'), 0);
            }
        }

        /** Envio **/
        echo json_encode($result);

        /** Paro o procedimento **/
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
