<?php

/**
 * Classe ModulesValidate.class.php
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2022 - Souza Consultoria Tecnológica
 * @package      vendor
 * @subpackage   controller
 * @version      1.0
 * @date         06/05/2022
 */

/** Defino o local da classes */

namespace src\controller\modules_acls;

/** Importação de classes */

use src\controller\main\Main;

class ModulesAclsValidate
{

    /** Parâmetros da classes */
    private $Main = null;
    private $errors = array();
    private $info = null;

    private $moduleAclId = null;
    private $moduleId = null;
    private $description = null;
    private $preferences = null;

    /** Método construtor */
    public function __construct()
    {
        /** Instância de classes */
        $this->Main = new Main();
    }

    /** Definir e validar module_id */
    public function setModuleAclId(int $moduleAclId): void
    {
        /** Tratamento da informação */
        $this->moduleAclId = (int)$this->Main->antiInjection($moduleAclId);

        /** Validação da informação */
        if ($this->moduleAclId < 0) {
            array_push($this->errors, 'O campo "Módulo ID" deve ser válido');
        }
    }

    public function getModuleAclId(): ?int
    {
        return (int)$this->moduleAclId;
    }


    /** Definir e validar module_id */
    public function setModuleId(int $moduleId): void
    {
        /** Tratamento da informação */
        $this->moduleId = (int)$this->Main->antiInjection($moduleId);

        /** Validação da informação */
        if ($this->moduleId < 0) {
            array_push($this->errors, 'O campo "Módulo ID" deve ser válido');
        }
    }

    public function getModuleId(): ?int
    {
        return (int)$this->moduleId;
    }

    /** Definir e validar description */
    public function setDescription(string $description): void
    {
        /** Tratamento da informação */
        $this->description = (string)$this->Main->antiInjection($description);

        /** Validação da informação */
        if (empty($this->description)) {
            array_push($this->errors, 'O campo "Descrição" deve ser válido');
        } else {

            // Formato o nome a ser salvo
            $this->description = str_replace(' ', '_', strtolower($this->description));
        }
    }

    public function getDescription(): ?string
    {
        return (string)$this->description;
    }

    /** Definir e validar description */
    public function setPreferences(string $preferences): void
    {
        /** Tratamento da informação */
        $this->preferences = (string)$this->Main->antiInjection($preferences, 'S');

        /** Validação da informação */
        if (empty($this->preferences)) {
            array_push($this->errors, 'O campo "Descrição" deve ser válido');
        }
    }

    public function getPreferences(): ?string
    {
        return (string)$this->preferences;
    }

    /** Método para obter os erros */
    public function getErrors(): ?string
    {
        /** Verifico se deve informar os erros */
        if (count($this->errors)) {

            /** Verifica a quantidade de erros para informar a legenda */
            $this->info = count($this->errors) > 1 ? '<b>Os seguintes erros foram encontrados:</b><hr/>' : '<b>O seguinte erro foi encontrado:</b><hr/>';

            /** Lista os erros  */
            foreach ($this->errors as $keyError => $error) {

                /** Monto a mensagem de erro */
                $this->info .= ($keyError + 1) . ' - ' . $error;
            }

            /** Retorno os erros encontrados */
            return (string)'<div class="alert alert-warning mt-2" role="alert">' . $this->info . '</div>';
        } else {

            return false;
        }
    }
}
