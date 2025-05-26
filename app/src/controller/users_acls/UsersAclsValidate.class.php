<?php

/**
 * Classe UsersValidade.class.php
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2022 - Souza Consultoria Tecnológica
 * @package      vendor
 * @subpackage   controller
 * @version      1.0
 * @date         06/05/2022
 */

/** Defino o local da classes */

namespace src\controller\users_acls;

/** Importação de classes */

use src\controller\main\Main;

class UsersAclsValidate
{

    /** Parâmetros da classes */
    private $Main = null;
    private $errors = array();
    private $info = null;

    private ?int $userAclId = null;
    private null|array|int $moduleAclId = null;
    private ?int $userId = null;

    /** Método construtor */
    public function __construct()
    {

        /** Instânciamento de classes */
        $this->Main = new Main();

    }

    public function setUserAclId(?int $userAclId): void
    {
        /** Tratamento da informação */
        $this->userAclId = (int)$this->Main->antiInjection($userAclId);
    }

    public function getUserAclId(): ?int
    {
        return (int)$this->userAclId;
    }

    public function setModuleAclId(null|array|int $moduleAclId): void
    {
        /** Tratamento da informação */
        $this->moduleAclId = $moduleAclId;
    }

    public function getModuleAclId(): null|array|int
    {
        return $this->moduleAclId;
    }

    public function setUserId(?int $userId): void
    {
        /** Tratamento da informação */
        $this->userId = $this->Main->antiInjection($userId);
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function getErrors(): ?string
    {

        /** Verifico se deve informar os erros */
        if (count($this->errors)) {

            /** Verifica a quantidade de erros para informar a legenda */
            $this->info = count($this->errors) > 1 ? '<center>Os seguintes erros foram encontrados</center>' : '<center>O seguinte erro foi encontrado</center>';

            /** Lista os erros  */
            foreach ($this->errors as $keyError => $error) {

                /** Monto a mensagem de erro */
                $this->info .= '</br>' . ($keyError + 1) . ' - ' . $error;

            }

            /** Retorno os erros encontrados */
            return (string)$this->info;

        } else {

            return false;

        }

    }

}
