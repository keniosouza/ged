<?php

/**
 * Classe FilesCategoriesValidate.class.php
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2025 - Souza Consultoria Tecnológica
 * @package      vendor
 * @subpackage   controller
 * @version      1.0
 * @date         05/02/2025
 */

/** Defino o local da classes */

namespace src\controller\files_categories_tags;

/** Importação de classes */

use src\controller\main\Main;

class FilesCategoriesTagsValidate
{

    /** Parâmetros da classes */
    private $Main = null;
    private $errors = array();
    private $info = null;

    private ?int $fileCategoryTagId = null;
    private ?int $fileCategoryId = null;
    private ?string $description = null;
    private ?array $preferences = null;
    private ?string $status = null;
    private ?int $userId = null;
    private ?string $search = null;
    private ?int $input = null;
    private ?int $fileTypeId = null;
    private ?int $start = null;
    private ?int $page = null;
    private ?int $max = null;
    private ?int $fileId = null;

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

    /** Trata o campo ID */
    public function setFileTypeId(int $fileTypeId): void
    {

        /** Tratamento da informação */
        $this->fileTypeId = (int)$this->Main->antiInjection($fileTypeId);
    }

    /** Retorna o campo ID */
    public function getFileTypeId(): ?int
    {
        return (int)$this->fileTypeId;
    }

    /** Trata o campo inteiro */
    public function setInputInt(?int $input): void
    {

        /** Tratamento da informação */
        $this->input = (int)$this->Main->antiInjection($input);
    }

    /** Retorna o campo inteiro */
    public function getInputInt(): ?int
    {
        return (int)$this->input;
    }

    /** Trata o campo file_category_tag_id */
    public function setFileCategoryTagId(?int $fileCategoryTagId): void
    {

        /** Tratamento da informação */
        $this->fileCategoryTagId = (int)$this->Main->antiInjection($fileCategoryTagId);

        // /** Validação da informação */
        // if ($this->fileCategoryTagId == 0) {

        //     /** Adição de elemento */
        //     array_push($this->errors, 'Nenhum registro informado para esta solicitação');
        // }
    }

    /** Retorna o campo file_category_tag_id */
    public function getFileCategoryTagId(): ?int
    {
        return (int)$this->fileCategoryTagId;
    }

    /** Trata o campo file_category_id */
    public function setFileCategoryId(int $fileCategoryId): void
    {

        /** Tratamento da informação */
        $this->fileCategoryId = (int)$this->Main->antiInjection($fileCategoryId);

        /** Validação da informação */
        if ($this->fileCategoryId == 0) {

            /** Adição de elemento */
            array_push($this->errors, 'Selecione uma categoria');
        }
    }

    /** Retorna o campo file_category_id */
    public function getFileCategoryId(): ?int
    {
        return (int)$this->fileCategoryId;
    }

    /** Trata o campo file_id */
    public function setFileId(int $fileId): void
    {

        /** Tratamento da informação */
        $this->fileId = $fileId > 0 ? (int)$this->Main->antiInjection($fileId) : 0;

        /** Validação da informação */
        if ($this->fileId == 0) {

            /** Adição de elemento */
            array_push($this->errors, 'Informe um arquivo');
        }
    }

    /** Retorna o campo file_id */
    public function getFileId(): ?int
    {
        return (int)$this->fileId;
    }

    /** Trata o campo descrição */
    public function setDescription(string $description): void
    {

        /** Tratamento da informação */
        $this->description = (string)$this->Main->antiInjection($description);

        /** Validação da informação */
        if (empty($this->description)) {

            /** Adição de elemento */
            array_push($this->errors, 'Informe uma descrição');
        }
    }

    /** Retorna o campo descrição */
    public function getDescription(): ?string
    {
        return (string)$this->description;
    }

    /** Trata o campo preferences */
    public function setPreferences(array $preferences): void
    {

        /** Tratamento da informação */
        $this->preferences = $this->Main->antiInjectionArray($preferences);

        /** Verifica se as preferências foram informadas */
        if ((count($this->preferences) == 0) || (count($this->preferences) < 4)) {

            /** Adição de elemento */
            array_push($this->errors, 'Informe as preferências');
        }
    }

    /** Retorna o campo preferences */
    public function getPreferences(): ?string
    {
        return json_encode($this->preferences, JSON_UNESCAPED_UNICODE);
    }


    /** Trata o campo status */
    public function setStatus(string $status): void
    {

        /** Tratamento da informação */
        $this->status = (string)$this->Main->antiInjection($status);

        /** Validação da informação */
        if (empty($this->status)) {

            /** Adição de elemento */
            array_push($this->errors, 'Informe a situação');
        }
    }

    /** Retorna o campo descrição */
    public function getStatus(): ?string
    {
        return (string)$this->status;
    }

    /** Trata o campo search */
    public function setSearch(?string $search): void
    {

        /** Tratamento da informação */
        $this->search = (string)$this->Main->antiInjection($search);
    }

    /** Retorna o campo search */
    public function getSearch(): ?string
    {
        return (string)$this->search;
    }

    /** Trata o campo user_id */
    public function setUserId(int $userId): void
    {

        /** Tratamento da informação */
        $this->userId = (int)$this->Main->antiInjection($userId);

        /** Validação da informação */
        if ($this->userId == 0) {

            /** Adição de elemento */
            array_push($this->errors, 'Informe o usuário');
        }
    }

    /** Retorna o campo user_id */
    public function getUserId(): ?int
    {
        return (int)$this->userId;
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
