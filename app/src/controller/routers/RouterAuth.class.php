<?php

/** Defino o local da classes */

namespace src\controller\Routers;

class RouterAuth
{

    /** Parâmetros da classe */
    private bool $status = false;


    /** Verifica o acesso */
    public function checkAccess(string $path): bool
    {

        /** Arquivos liberados do uso 
         * da sessão do usuário */
        $result = [
            'view/users/users_register.php',
            'view/users/users_login.php',
            'view/users/users_login.php',
            'action/users/users_login.php',
            'action/users/users_authenticate.php',
            'action/users/users_reset.php',
            'action/users/users_save_new_password.php'
        ];

        /** Verifico se existeo valor na array */
        if (!in_array($path, $result)) {

            /** Obtenho os dados da sessão */
            @$UserSessionResult = $_SESSION['MY_SAAS_USER'];

            /** Verifico se existe sessão ativa */
            if (@(int) $UserSessionResult->user_id === 0) {

                /** Informo que a sessão não esta ativa */
                $this->status = false;
            } else {

                /** Informo que a sessão esta ativa */
                $this->status = true;
            }
        } else {

            /** Informo que a sessão esta ativa */
            $this->status = true;
        }

        /** Retorno da informação */
        return $this->status;
    }
}
