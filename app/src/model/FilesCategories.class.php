<?php

/**
 * Classe Users.class.php
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2021 - Souza Consultoria Tecnológica
 * @package        model
 * @subpackage    model.class
 * @version        1.0
 */

/** Defino o local onde esta a classe */

namespace src\model;

class FilesCategories
{
    /** Declaro as vaiavéis da classe */
    private $connection = null;
    private $sql = null;
    private $stmt = null;
    private ?int $fileCategoryId = null;
    private ?int $fileTypeId = null;
    private ?string $description = null;
    private ?string $status = null;
    private ?int $start = null;
    private ?int $max = null;
    private ?string $limit = null;
    private ?int $userId = null;
    private ?string $search = null;
    private ?array $field = null;

    /** Construtor da classe */
    public function __construct()
    {
        /** Cria o objeto de conexão com o banco de dados */
        $this->connection = new Mysql();
    }

    /** Carrega os campos de uma tabela */
    public function Describe()
    {

        /** Consulta SQL */
        $this->sql = "describe files_categories";

        /** Preparo o SQL para execução */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Executo o SQL */
        $this->stmt->execute();

        /** Retorno o resultado */
        $this->field = $this->stmt->fetchAll(\PDO::FETCH_OBJ);

        /** Declara o objeto */
        $resultDescribe = new \stdClass();
        $Field = '';

        /** Lista os campos da tabela para objetos */
        foreach ($this->field as $UsersKey => $Result) {

            /** Pega o nome do Field/Campo */
            $Field = $Result->Field;

            /** Carrega os objetos como null */
            $resultDescribe->$Field = null;
        }

        /** Retorna os campos declarados como vazios */
        return $resultDescribe;
    }

    /** Salva o registro junto ao banco de dados */
    public function Save(
        int $fileCategoryId,
        int $fileTypeId,
        string $description,
        string $status,
        int $userId
    ) {

        /** Parametros de entrada */
        $this->fileCategoryId = $fileCategoryId;
        $this->fileTypeId = $fileTypeId;
        $this->description = $description;
        $this->status = $status;
        $this->userId = $userId;

        /** Consulta SQL */
        $this->sql = 'INSERT INTO files_categories (`file_category_id`, 
                                                    `file_type_id`, 
                                                    `description`,
                                                    `status`,
                                                    `user_id_create`) value (
                                                    :file_category_id, 
                                                    :file_type_id, 
                                                    :description,
                                                    :status,
                                                    ' . $this->userId . ')
                      ON DUPLICATE KEY UPDATE `file_category_id` = :file_category_id, 
                                              `file_type_id` = :file_type_id,
                                              `description` = :description,
                                              `status` = :status,
                                              `date_update` = NOW(),
                                              `user_id_update` = ' . $this->userId . ';';

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':file_category_id', $fileCategoryId);
        $this->stmt->bindParam(':file_type_id', $fileTypeId);
        $this->stmt->bindParam(':description', $description);
        $this->stmt->bindParam(':status', $status);

        /** Executo o SQL */
        return $this->stmt->execute();
    }

    /** Retorna a quantidade de registros cadastradas */
    public function Count(
        ?string $search
    ): int {

        /** Parametros de entrada */
        $this->search = !empty($search) ? '%' . $search . '%' : null;

        /** Monta a consulta SQL . */
        $this->sql = 'select count(file_category_id) as qtde 
                      from files_categories 
                      where status <> \'D\'';

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            $this->sql .= ' and description like(:description)';
        }

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            /** Preencho os parâmetros do SQL */
            $this->stmt->bindParam(':description', $this->search);
        }

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorno o resultado */
        return (int)$this->stmt->fetchObject()->qtde;
    }

    /** Retorna todos os registros junto ao banco de dados */
    public function Select(?int $fileCategoryId): array
    {

        /** Parametros de entrada */
        $this->fileCategoryId = $fileCategoryId;

        /** Monta a consulta SQL . */
        $this->sql = 'select fc.file_category_id,
                             fc.file_type_id,
                             fc.description,
                             fc.user_id_create,
                             fc.user_id_update,
                             fc.date_create,
                             fc.date_update,
                             fc.status, 
                             ft.description as type       
                      from files_categories fc
                      left join files_types ft on fc.file_type_id = ft.file_type_id
                      where fc.status <> \'D\'
                      and (select count(fct.file_category_tag_id) from files_categories_tags fct where fct.file_category_id = fc.file_category_id) > 0';

        /** Verifica se a categoria foi informada */
        if ($this->fileCategoryId > 0) {

            $this->sql .= ' and fc.file_category_id = :file_category_id';
        }

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Verifica se a categoria foi informada */
        if ($this->fileCategoryId > 0) {

            /** Preencho os parâmetros do SQL */
            $this->stmt->bindParam(':file_category_id', $this->fileCategoryId);
        }

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /** Retorna todos os registros junto ao banco de dados */
    public function All(
        ?int $start,
        ?int $max,
        ?string $search
    ): array {

        /** Parametros de entrada */
        $this->start = $start;
        $this->max = $max;
        $this->search = !empty($search) ? '%' . $search . '%' : null;

        /** Verifico se há paginação */
        if ($this->max) {
            $this->limit = " limit $this->start, $this->max";
        }

        /** Monta a consulta SQL . */
        $this->sql = 'select fc.file_category_id,
                             fc.file_type_id,
                             fc.description,
                             fc.user_id_create,
                             fc.user_id_update,
                             fc.date_create,
                             fc.date_update,
                             fc.status, 
                             ft.description as type       
                      from files_categories fc
                      left join files_types ft on fc.file_type_id = ft.file_type_id
                      where fc.status <> \'D\'';

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            $this->sql .= ' and fc.description like(:description)';
        }

        /** Informa a limitação de registros */
        $this->sql .= $this->limit;

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            /** Preencho os parâmetros do SQL */
            $this->stmt->bindParam(':description', $this->search);
        }

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /** Localiza um registro especifico */
    public function Get(
        int $fileCategoryId
    ): object|bool {
        /** Parametros de entrada */
        $this->fileCategoryId = $fileCategoryId;

        /** Monta a consulta SQL . */
        $this->sql = 'select * from files_categories 
                      where file_category_id = :file_category_id';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':file_category_id', $this->fileCategoryId);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchObject();
    }

    /** Localiza um registro especifico */
    public function GetType(
        int $fileTypeId
    ): array {
        /** Parametros de entrada */
        $this->fileTypeId = $fileTypeId;

        /** Monta a consulta SQL . */
        $this->sql = 'select * from files_categories 
                      where file_type_id = :file_type_id';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':file_type_id', $this->fileTypeId);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }


    /** Exclui um registro especifico */
    public function Delete(
        int $fileCategoryId,
        int $userId
    ): object|bool {
        /** Parametros de entrada */
        $this->fileCategoryId = $fileCategoryId;
        $this->userId = $userId;

        /** Monta a consulta SQL . */
        $this->sql = 'update files_categories set status = \'D\',
                                                  `user_id_update` = :user_id_update,
                                                  `date_update` = NOW()
                      where file_category_id = :file_category_id';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':file_category_id', $this->fileCategoryId);
        $this->stmt->bindParam(':user_id_update', $this->userId);

        /** Executa a consulta SQL. */
        return $this->stmt->execute();
    }

    /** Fecha uma conexão aberta anteriormente com o banco de dados */
    public function __destruct()
    {
        $this->connection = null;
    }
}
