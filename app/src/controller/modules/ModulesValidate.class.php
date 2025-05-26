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

namespace src\controller\modules;

/** Importação de classes */

use src\controller\main\Main;

class ModulesValidate
{

    /** Parâmetros da classes */
    private $Main = null;
    private $errors = array();
    private $info = null;

    private ?int $moduleId = null;
    private ?int $companyId = null;
    private ?string $name = null;
    private ?string $description = null;
    private ?string $status = null;
    private ?int $start = null;
    private ?int $page = null;
    private ?int $max = null;
    private ?string $search = null;

    /** Método construtor */
    public function __construct()
    {
        /** Instância de classes */
        $this->Main = new Main();
    }

    /** Trata o campo start */
    public function setStart(?int $start): void
    {
        /** Tratamento da informação */
        $this->start = $start > 0 ? (int)$this->Main->antiInjection($start) : 0;
    }

    /** Retorna o campo start */
    public function getStart(): ?int
    {
        return (int)$this->start;
    }

    /** Trata o campo page */
    public function setPage(?int $page): void
    {
        /** Tratamento da informação */
        $this->page = $page > 0 ? (int)$this->Main->antiInjection($page) : 0;
    }

    /** Retorna o campo page */
    public function getPage(): ?int
    {
        return (int)$this->page;
    }

    /** Trata o campo max */
    public function setMax(?int $max): void
    {
        /** Tratamento da informação */
        $this->max = $max > 0 ? (int)$this->Main->antiInjection($max) : 0;
    }

    /** Retorna o campo max */
    public function getMax(): ?int
    {
        return (int)$this->max;
    }

    /** Trata o campo search */
    public function setSearch(?string $search): void
    {
        /** Tratamento da informação */
        $this->search = isset($search) ? (string)$this->Main->antiInjection($search) : null;
    }

    /** Retorna o campo search */
    public function getSearch(): ?string
    {
        return (string)$this->search;
    }

    /** Definir e validar module_id */
    public function setModuleId(int $moduleId): void
    {
        /** Tratamento da informação */
        $this->moduleId = (int)$this->Main->antiInjection($moduleId);

        /** Validação da informação */
        // if ($this->moduleId < 0) {
        //     array_push($this->errors, 'O campo "Módulo ID" deve ser válido');
        // }
    }

    /** Retorna o module_id */
    public function getModuleId(): ?int
    {
        return (int)$this->moduleId;
    }

    /** Definir e validar company_id */
    public function setCompanyId(int $companyId): void
    {
        /** Tratamento da informação */
        $this->companyId = (int)$this->Main->antiInjection($companyId);

        /** Validação da informação */
        if ($this->companyId <= 0) {
            array_push($this->errors, 'O campo "Empresa ID" deve ser válido');
        }
    }

    public function getCompanyId(): ?int
    {
        return (int)$this->companyId;
    }

    /** Definir e validar name */
    public function setName(string $name): void
    {
        /** Tratamento da informação */
        $this->name = (string)$this->Main->antiInjection($name);

        /** Validação da informação */
        if (empty($this->name)) {

            array_push($this->errors, 'O campo "Nome" deve ser válido');
        } else {

            // Formato o nome a ser salvo
            $this->name = str_replace(' ', '_', strtolower($this->name));
        }
    }

    public function getName(): ?string
    {
        return (string)$this->name;
    }

    /** Definir e validar description */
    public function setDescription(string $description): void
    {
        /** Tratamento da informação */
        $this->description = (string)$this->Main->antiInjection($description);

        /** Validação da informação */
        if (empty($this->description)) {
            array_push($this->errors, 'O campo "Descrição" deve ser válido');
        }
    }

    /** Retorna o campo description */
    public function getDescription(): ?string
    {
        return (string)$this->description;
    }

    /** Definir o valor do status */
    public function setStatus(string $status): void
    {
        /** Tratamento da informação */
        $this->status = (string)$this->Main->antiInjection($status);

        /** Validação da informação */
        if (empty($this->status)) {
            array_push($this->errors, 'O campo "Situação" deve ser informado');
        }
    }

    /** Retorna o campo status */
    public function getStatus(): ?string
    {
        return (string)$this->status;
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
                $this->info .= ($keyError + 1) . ' - ' . $error . '<br/>';
            }

            /** Retorno os erros encontrados */
            return (string)'<div class="alert alert-warning mt-2" role="alert">' . $this->info . '</div>';
        } else {

            return false;
        }
    }

    /** destrutor da classe */
    public function __destruct() {}
}
