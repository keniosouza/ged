<?php

/**
 * Classe UsersValidade.class.php
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2024 - Souza Consultoria Tecnológica
 * @package      vendor
 * @subpackage   controller
 * @version      1.0
 * @date         06/05/2024
 */

/** Defino o local da classes */

namespace src\controller\companies;

/** Importação de classes */

use src\controller\main\Main;

class CompaniesValidate
{

    /** Parâmetros da classes */
    private $Main = null;
    private $errors = array();
    private $info = null;

    /** Variaveis privadas */
    private ?int $companyId = null;
    private ?string $nameBusiness = null;
    private ?string $nameFantasy = null;
    private ?string $status = null;
    private ?int $start = null;
    private ?int $page = null;
    private ?int $max = null;
    private ?string $search = null;

    /** Método construtor */
    public function __construct()
    {
        /** Instânciamento de classes */
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

    /** Trata o campo company id */
    public function setCompanyId(int $companyId): void
    {
        /** Tratamento da informação */
        $this->companyId = (int)$this->Main->antiInjection($companyId);

        /** Validação da informação */
        if ($this->companyId < 0) {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Usuário ID", deve ser válido');
        }
    }

    /** Retorna o campo company id */
    public function getCompanyId(): ?int
    {
        return (int)$this->companyId;
    }

    /** Trata o campo situação */
    public function setStatus(string $status): void
    {

        /** Tratamento da informação */
        $this->status = (string)$this->Main->antiInjection($status);

        /** Validação da informação */
        if (empty($this->status)) {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Situação", deve ser válido');
        }
    }

    /** Retorna o campo situação */
    public function getStatus(): ?string
    {
        return (string)$this->status;
    }

    /** Trata o campo nome da empresa */
    public function setNameBusiness(string $nameBusiness): void
    {
        /** Tratamento da informação */
        $this->nameBusiness = (string)$this->Main->antiInjection($nameBusiness);

        /** Validação da informação */
        if (empty($this->nameBusiness)) {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Nome Empresarial", deve ser válido');
        }
    }

    /** Retorna o nome da empresa */
    public function getNameBusiness(): ?string
    {
        return (string)$this->nameBusiness;
    }

    /** Trata o nome fantasia da empresa */
    public function setNameFantasy(string $nameFantasy): void
    {
        /** Tratamento da informação */
        $this->nameFantasy = (string)$this->Main->antiInjection($nameFantasy);

        /** Validação da informação */
        if (empty($this->nameFantasy)) {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Nome Fantasia", deve ser válido');
        }
    }

    /** Retorna o nome fantasia da empresa */
    public function getNameFantasy(): ?string
    {
        return (string)$this->nameFantasy;
    }

    /** Retorna os erros */
    public function getErrors(): ?string
    {

        /** Verifico se deve informar os erros */
        if (count($this->errors)) {

            /** Verifica a quantidade de erros para informar a legenda */
            $this->info = count($this->errors) > 1 ? '<b>Os seguintes erros foram encontrados:</b><hr/>' : '<b>O seguinte erro foi encontrado:</b><hr/>';
            $i = 0;

            /** Lista os erros  */
            foreach ($this->errors as $keyError => $error) {

                /** Monto a mensagem de erro */
                $this->info .= ($i > 0 ? '<br/>' : '') . ($keyError + 1) . ' - ' . $error;
                $i++;
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
