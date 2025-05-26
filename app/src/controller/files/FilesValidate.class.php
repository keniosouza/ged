<?php

/**
 * Classe FilesValidate.class.php
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2022 - Souza Consultoria Tecnológica
 * @package        vendor
 * @subpackage    controller/files
 * @version        1.0
 * @date            25/04/2022
 */


/** Defino o local onde esta a classe */

namespace src\controller\files;

/** Importação de classes */

use src\controller\main\Main;

class FilesValidate
{
    /** Declaro as variavéis da classe */
    private $Main = null;
    private $errors = [];
    private $err = [];
    private $msgErr = [];
    private $info = null;
    private ?int $fileId = null;
    private ?int $userId = null;
    private ?int $fileCategoryId = null;
    private ?int $folderId = null;
    private ?int $companyId = null;
    private ?int $registerId = null;
    private ?string $table = null;
    private ?string $name = null;
    private ?string $path = null;
    private ?string $description = null;
    private ?array $tags = null;
    private ?array $required = null;
    private ?string $history = null;
    private ?string $base64 = null;
    private ?string $extension = null;
    private ?object $crop = null;
    private ?int $i = 0;
    private ?string $search = null;
    private ?int $start = null;
    private ?int $page = null;
    private ?int $max = null;
    private ?int $batchId = null;
    private ?string $convert = null;

    /** Construtor da classe */
    public function __construct()
    {
        /** Instânciamento da classe de validação */
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

    /** Trata o campo convert */
    public function setConvert(?string $convert): void
    {
        /** Tratamento da informação */
        $this->convert = isset($convert) ? (string)$this->Main->antiInjection($convert) : null;
    }

    /** Retorna o campo convert */
    public function getConvert(): ?string
    {
        return (string)$this->convert;
    }

    /** Método trata campo file_id */
    public function setFileId(int $fileId): void
    {

        /** Trata a entrada da informação  */
        $this->fileId = isset($fileId) ? (int)$this->Main->antiInjection($fileId) : 0;
    }

    /** Método trata campo batch_id */
    public function setBatchId(int $batchId): void
    {

        /** Trata a entrada da informação  */
        $this->batchId = isset($batchId) ? (int)$this->Main->antiInjection($batchId) : 0;
    }

    /** Método retorna campo batch_id */
    public function getBatchId(): ?int
    {
        /** Retorno da informação */
        return (int)$this->batchId;
    }

    /** Método trata campo user_id */
    public function setUserId(int $userId): void
    {

        /** Trata a entrada da informação  */
        $this->userId = isset($userId) ? (int)$this->Main->antiInjection($userId) : null;
    }

    /** Método retorna campo user_id */
    public function getUserId(): ?int
    {
        /** Retorno da informação */
        return (int)$this->userId;
    }

    /** Método trata campo file_category_id */
    public function setFileCategoryId(int $fileCategoryId): void
    {

        /** Trata a entrada da informação  */
        $this->fileCategoryId = isset($fileCategoryId) ? (int)$this->Main->antiInjection($fileCategoryId) : 0;

        /** Verifica se a categoria foi informada */
        if ($this->fileCategoryId == 0) {

            // Adição de elemento ao array de erros
            array_push($this->errors, 'Nenhuma categoria selecionada para este arquivo');
        }
    }

    /** Método trata campo folder_id */
    public function setFolderId(int $folderId): void
    {

        /** Trata a entrada da informação  */
        $this->folderId = isset($folderId) ? (int)$this->Main->antiInjection($folderId) : null;
    }

    /** Método retorna campo folder_id */
    public function getFolderId(): ?int
    {

        /** Retorno da informação */
        return (int)$this->folderId;
    }

    /** Método trata campo company_id */
    public function setCompanyId(int $companyId): void
    {

        /** Trata a entrada da informação  */
        $this->companyId = isset($companyId) ? (int)$this->Main->antiInjection($companyId) : null;
    }

    /** Método retorna campo company_id */
    public function getCompanyId(): ?int
    {

        /** Retorno da informação */
        return (int)$this->companyId;
    }

    /** Método trata campo register_id */
    public function setRegisterId(int $registerId): void
    {

        /** Trata a entrada da informação  */
        $this->registerId = isset($registerId) ? (int)$this->Main->antiInjection($registerId) : null;
    }

    /** Método trata campo table */
    public function setTable(string $table): void
    {

        /** Trata a entrada da informação  */
        $this->table = isset($table) ? (string)$this->Main->antiInjection($table) : null;
    }

    /**
     * Método setName
     * Define o valor para o campo 'name' após tratamento e validação.
     *
     * @param string $name - Valor a ser atribuído ao campo 'name'.
     *
     * @return void
     */
    public function setName(string $name): void
    {

        // Trata a entrada da informação usando o método antiInjection de $this->Main
        $this->name = isset($name) ? (string)$this->Main->antiInjection($name) : null;

        // Verifica se a informação foi informada
        if (empty($this->name)) {

            // Adição de elemento ao array de erros se o campo 'name' não foi informado
            array_push($this->errors, 'O campo "name" deve ser informado');
        } else {

            // Formatação do nome removendo caracteres especiais usando o método RemoveSpecialChars de $this->Main
            $this->name = $this->Main->RemoveSpecialChars($this->name);
            $this->name = strtoupper($this->name);

            $words = ['/', '-', '_', '+', ' '];

            $this->name = str_replace($words, '_', $this->name);
        }
    }

    /** Método trata campo description */
    public function setDescription(string $description): void
    {

        /** Trata a entrada da informação  */
        $this->description = isset($description) ? (string)$this->Main->antiInjection($description) : null;
    }

    /** Método trata campo path */
    public function setPath(string $path): void
    {

        /** Trata a entrada da informação  */
        $this->path = isset($path) ? (string)$this->Main->antiInjection($path, 'S') : null;
    }

    /** Método trata campo history */
    public function setHistory(string $history): void
    {

        /** Trata a entrada da informação  */
        $this->history = isset($history) ? (string)$this->Main->antiInjection($history, 'S') : null;
    }

    /** Método trata campo base64 */
    public function setBase64(string $base64): void
    {

        /** Trata a entrada da informação  */
        $this->base64 = isset($base64) ? (string)$this->Main->antiInjection($base64, 'S') : null;
    }

    /** Método trata campo extension */
    public function setExtension(string $extension): void
    {

        /** Trata a entrada da informação  */
        $this->extension = pathinfo($this->Main->antiInjection($extension), PATHINFO_EXTENSION);
    }

    /** Método trata campo crop */
    public function setCrop(object $crop): void
    {

        /** Trata a entrada da informação  */
        $this->crop = $crop;
    }

    /** Método retorna campo file_id */
    public function getFileId(): ?int
    {
        /** Retorno da informação */
        return (int)$this->fileId;
    }

    /** Método retorna campo file_category_id */
    public function getFileCategoryId(): ?int
    {

        /** Retorno da informação */
        return (int)$this->fileCategoryId;
    }

    /** Método retorna campo register_id */
    public function getRegisterId(): ?int
    {

        /** Retorno da informação */
        return (int)$this->registerId;
    }

    /** Método retorna campo table */
    public function getTable(): ?string
    {

        /** Retorno da informação */
        return (string)$this->table;
    }

    /** Método retorna campo name */
    public function getName(): ?string
    {

        /** Retorno da informação */
        return (string)$this->name;
    }

    /** Método retorna campo path */
    public function getPath(): ?string
    {

        /** Retorno da informação */
        return (string)$this->path;
    }

    /** Método retorna campo path */
    public function getDescription(): ?string
    {

        /** Retorno da informação */
        return (string)$this->description;
    }

    /** Método retorna campo history */
    public function getHistory(): ?string
    {

        /** Retorno da informação */
        return (string)$this->history;
    }

    /** Método retorna campo history */
    public function getGenerateHistory(): ?string
    {

        /** Retorno da informação */
        return (string)$this->history;
    }

    /** Método retorna campo base64 */
    public function getBase64(): ?string
    {

        /** Retorno da informação */
        return (string)$this->base64;
    }

    /** Método retorna campo extension */
    public function getExtension(): ?string
    {

        /** Retorno da informação */
        return (string)$this->extension;
    }

    /** Método retorna campo crop */
    public function getCrop(): ?object
    {

        /** Retorno da informação */
        return (object)$this->crop;
    }

    /** Método trata campo tags */
    public function setTags(array $tags): void
    {
        /** Parametros de entrada  */
        $this->tags = $tags;

        /** Remoção de espaçamentos */
        $this->tags = array_map('trim', $this->tags);

        /** Remoção de tags PHP e HTML */
        $this->tags = array_map('strip_tags', $this->tags);

        /** Adição de barras invertidas */
        $this->tags = array_map('addslashes', $this->tags);

        /** Verifica se não existem itens */
        if (count($this->tags) == 0) {

            // Adição de elemento ao array de erros se o campo 'name' não foi informado
            array_push($this->errors, 'Existem marcações não informadas');
        } else {

            /** Adiciona novas 
             * informações na indexação */
            $this->tags['file_id'] = $this->getFileId();
            $this->tags['file_category_id'] = $this->getFileCategoryId();
            $this->tags['indexacao_data'] = date('d/m/Y H:i:s');
            $this->tags['indexacao_usuario'] = $this->getUserId();
        }
    }

    /** Método retorna campo tags */
    public function getTags(): ?string
    {

        /** Retorno da informação */
        return json_encode($this->tags, JSON_UNESCAPED_UNICODE);
    }


    /** Método trata campo required */
    public function setRequired(array $required): void
    {
        /** Parametros de entrada  */
        $this->required = $required;

        /** Remoção de espaçamentos */
        $this->required = array_map('trim', $this->required);

        /** Remoção de tags PHP e HTML */
        $this->required = array_map('strip_tags', $this->required);

        /** Adição de barras invertidas */
        $this->required = array_map('addslashes', $this->required);
    }

    /** Método retorna campo required */
    public function getRequired(): ?string
    {

        /** Retorno da informação */
        return json_encode($this->required, JSON_UNESCAPED_UNICODE);
    }

    /** Método que verifica se algum campo requerido não foi informado */
    public function checkRequerid(): void
    {

        foreach ($this->tags as $key => $value) {

            if ((empty($value)) && ((int)$this->required[$this->i] == 1)) {

                // Adição de elemento ao array de erros se o campo 'name' não foi informado
                array_push($this->errors, '<b>Informe: </b>' . $key);
            }

            $this->i++;
        }
    }

    public function getErrors(): ?string
    {

        /** Verifico se deve informar os erros */
        if (count($this->errors)) {

            /** Remove os itens duplicados */
            $this->err = array_unique($this->errors);

            /** Lista os erros  */
            for ($i = 0; $i < count($this->err); $i++) {

                if (!in_array($this->err[$i], $this->msgErr)) {

                    array_push($this->msgErr, $this->err[$i]);

                    /** Monto a mensagem de erro */
                    $this->info .= ($i > 0 ? '</br>' : '') . $this->err[$i];
                }
            }

            /** Retorno os erros encontrados */
            return (string)$this->info;
        } else {

            return false;
        }
    }

    public function __destruct()
    {
        $this->errors = [];
    }
}
