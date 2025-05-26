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

namespace src\controller\files_types;

/** Importação de classes */

use src\controller\main\Main;

class FilesTypesValidate
{

    /** Parâmetros da classes */
    private $Main = null;
    private $errors = array();
    private $info = null;

    private ?int $fileTypeId = null;
    private ?int $companyId = null;
    private ?string $description = null;
    private ?string $status = null;
    private ?int $userId = null;
    private ?string $search = null;
    private ?int $start = null;
    private ?int $page = null;
    private ?int $max = null;

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

    /** Retorna o campo ID */
    public function getSearch(): ?string
    {
        return (string)$this->search;
    }

    /** Trata o campo ID */
    public function setFileTypeId(int $fileTypeId): void
    {

        /** Tratamento da informação */
        $this->fileTypeId = (int)$this->Main->antiInjection($fileTypeId);

        /** Validação da informação */
        // if ($this->fileTypeId < 0) {

        //     /** Adição de elemento */
        //     array_push($this->errors, 'O campo "file_type_id", deve ser válido');
        // }
    }

    /** Retorna o campo ID */
    public function getFileTypeId(): ?int
    {
        return (int)$this->fileTypeId;
    }

    /** Trata o campo company_id */
    public function setCompanyId(int $companyId): void
    {

        /** Tratamento da informação */
        $this->companyId = (int)$this->Main->antiInjection($companyId);

        /** Validação da informação */
        if (empty($this->companyId)) {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "company_id", deve ser válido');
        }
    }

    /** Retorna o campo company_id */
    public function getCompanyId(): ?int
    {
        return (int)$this->companyId;
    }

    /** Trata o campo descrição */
    public function setDescription(string $description): void
    {

        /** Tratamento da informação */
        $this->description = (string)$this->Main->antiInjection($description);

        /** Validação da informação */
        if (empty($this->description)) {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Descrição", deve ser informado');
        }
    }

    /** Retorna o campo descrição */
    public function getDescription(): ?string
    {
        return (string)$this->description;
    }

    /** Trata o campo satus */
    public function setStatus(string $status): void
    {

        /** Tratamento da informação */
        $this->status = (string)$this->Main->antiInjection($status);

        /** Validação da informação */
        if (empty($this->status)) {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Status", deve ser informado');
        }
    }

    /** Retorna o campo descrição */
    public function getStatus(): ?string
    {
        return (string)$this->status;
    }

    /** Trata o campo user_id */
    public function setUserId(string $userId): void
    {

        /** Tratamento da informação */
        $this->userId = (int)$this->Main->antiInjection($userId);

        /** Validação da informação */
        if ($this->userId == 0) {

            /** Adição de elemento */
            array_push($this->errors, 'O usuário responsáel pelo cadastro deve ser informado');
        }
    }

    /** Retorna o campo descrição */
    public function getUserId(): ?string
    {
        return (string)$this->userId;
    }


    /** Retorna os erros */
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
