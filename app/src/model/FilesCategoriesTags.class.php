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

class FilesCategoriesTags
{
    /** Declaro as variavéis da classe */
    private $connection = null;
    private $sql = null;
    private $stmt = null;
    private ?int $start = null;
    private ?int $max = null;
    private ?string $limit = null;
    private ?int $fileCategoryTagId = null;
    private ?int $fileCategoryId = null;
    private ?string $description = null;
    private ?string $preferences = null;
    private ?string $status = null;
    private ?int $userId = null;
    private ?string $search = null;
    private ?int $fileTypeId = null;
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
        $this->sql = "describe files_categories_tags";

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

    /** Salva os dados no banco de dados */
    public function Save(
        int $fileCategoryTagId,
        int $fileCategoryId,
        string $description,
        string $preferences,
        string $status,
        int $userId
    ) {

        /** Parametros de entrada */
        $this->fileCategoryTagId = $fileCategoryTagId;
        $this->fileCategoryId = $fileCategoryId;
        $this->description = $description;
        $this->preferences = $preferences;
        $this->status = $status;
        $this->userId = $userId;

        /** Consulta SQL */
        $this->sql = 'INSERT INTO files_categories_tags (`file_category_tag_id`, 
                                                         `file_category_id`, 
                                                         `description`, 
                                                         `preferences`,
                                                         `status`,
                                                         `user_id_create`) value (
                                                         :file_category_tag_id, 
                                                         :file_category_id, 
                                                         :description, 
                                                         :preferences,
                                                         :status,
                                                         ' . $this->userId . ')
                      ON DUPLICATE KEY UPDATE `file_category_id` = :file_category_id, 
                                              `description` = :description, 
                                              `preferences` = :preferences,
                                              `date_update` = NOW(),
                                              `status` = :status,
                                              `user_id_update` = ' . $this->userId . ';';

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':file_category_tag_id', $this->fileCategoryTagId);
        $this->stmt->bindParam(':file_category_id', $this->fileCategoryId);
        $this->stmt->bindParam(':description', $this->description);
        $this->stmt->bindParam(':preferences', $this->preferences);
        $this->stmt->bindParam(':status', $this->status);

        /** Executo o SQL */
        return $this->stmt->execute();
    }

    /** Lista todos os registros junto ao banco de dados
     * que não estão com o status deletado
     */
    public function All(
        ?int $start,
        ?int $max,
        ?string $search,
        ?int $fileTypeId,
        ?int $fileCategoryId
    ): array {

        /** Parametros de entrada */
        $this->start = $start;
        $this->max = $max;
        $this->search = !empty($search) ? '%' . $search . '%' : null;
        $this->fileTypeId = $fileTypeId;
        $this->fileCategoryId = $fileCategoryId;

        /** Verifico se há paginação */
        if ($this->max) {
            $this->limit = " limit $this->start, $this->max";
        }


        /** Monta a consulta SQL. */
        $this->sql = 'select fct.file_category_tag_id,
                             fct.file_category_id,
                             fct.description,
                             fct.preferences,
                             fct.user_id_create,
                             fct.user_id_update,
                             fct.date_create,
                             fct.date_update,
                             fct.status,
                             fc.description as category,
                             ft.description as type
                      from files_categories_tags fct
                      left join files_categories fc on fct.file_category_id = fc.file_category_id
                      left join files_types ft on fc.file_type_id = ft.file_type_id
                      where fct.status <> \'D\'';

        /** Verifica se existe uma consulta a ser efetuada */
        if (!empty($this->search)) {

            $this->sql .= ' and fct.description like(:description)';
        }

        /** Verifica se existe uma consulta a ser efetuada */
        if ((int)$this->fileTypeId > 0) {

            $this->sql .= ' and ft.file_type_id = :file_type_id';
        }

        /** Verifica se existe uma consulta a ser efetuada */
        if ((int)$this->fileCategoryId > 0) {

            $this->sql .= ' and fct.file_category_id = :file_category_id';
        }

        /** Aplica a limitação de registros */
        $this->sql .= $this->limit;

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            /** Preencho os parâmetros do SQL */
            $this->stmt->bindParam(':description', $this->search);
        }

        /** Verifica se a consulta foi informada */
        if ((int)$this->fileTypeId > 0) {

            /** Preencho os parâmetros do SQL */
            $this->stmt->bindParam(':file_type_id', $this->fileTypeId);
        }

        /** Verifica se a consulta foi informada */
        if ((int)$this->fileCategoryId > 0) {

            /** Preencho os parâmetros do SQL */
            $this->stmt->bindParam(':file_category_id', $this->fileCategoryId);
        }

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /** Lista as tags de uma determinada categoria */
    public function AllByCategoryId(int $fileCategoryId): array
    {

        /** Parametros de entrada */
        $this->fileCategoryId = $fileCategoryId;


        /** Monta a consulta SQL. */
        $this->sql = 'select fct.file_category_tag_id,
                             fct.file_category_id,
                             fct.description,
                             fct.preferences,
                             fct.user_id_create,
                             fct.user_id_update,
                             fct.date_create,
                             fct.date_update,
                             fct.status 
                      from files_categories_tags fct 
                      where fct.file_category_id = :file_category_id';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':file_category_id', $this->fileCategoryId);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /** Localiza um registro especifico */
    public function Get(int $fileCategoryTagId): object|bool
    {

        /** Parametros de entrada */
        $this->fileCategoryTagId = $fileCategoryTagId;

        /** Monta a consulta SQL. */
        $this->sql = 'select * from files_categories_tags 
                      where file_category_tag_id = :file_category_tag_id';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':file_category_tag_id', $this->fileCategoryTagId);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchObject();
    }

    /** Retorna a quantidade de registros */
    public function Count(
        ?string $search,
        ?int $fileTypeId,
        ?int $fileCategoryId
    ): int {

        /** Parametros de entrada */
        $this->search = !empty($search) ? '%' . $search . '%' : null;
        $this->fileTypeId = $fileTypeId;
        $this->fileCategoryId = $fileCategoryId;

        /** Monta a consulta SQL. */
        $this->sql = 'select count(fct.file_category_tag_id) as qtde
                      from files_categories_tags fct
                      left join files_categories fc on fct.file_category_id = fc.file_category_id
                      left join files_types ft on fc.file_type_id = ft.file_type_id';

        /** Verifica se existe uma consulta a ser efetuada */
        if (!empty($this->search)) {

            $this->sql .= ' where fct.description like(:description)';
        }

        /** Verifica se existe uma consulta a ser efetuada */
        if ((int)$this->fileTypeId > 0) {

            $this->sql .= ' and ft.file_type_id = :file_type_id';
        }

        /** Verifica se existe uma consulta a ser efetuada */
        if ((int)$this->fileCategoryId > 0) {

            $this->sql .= ' and fc.file_category_id = :file_category_id';
        }

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            /** Preencho os parâmetros do SQL */
            $this->stmt->bindParam(':description', $this->search);
        }

        /** Verifica se a consulta foi informada */
        if ((int)$this->fileTypeId > 0) {

            /** Preencho os parâmetros do SQL */
            $this->stmt->bindParam(':file_type_id', $this->fileTypeId);
        }

        /** Verifica se a consulta foi informada */
        if ((int)$this->fileCategoryId > 0) {

            /** Preencho os parâmetros do SQL */
            $this->stmt->bindParam(':file_category_id', $this->fileCategoryId);
        }

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchObject()->qtde;
    }

    /** Altera o status de registro para D => Deletado */
    public function Delete(
        int $fileCategoryTagId,
        int $userId
    ): object|bool {

        /** Parametros de entrada */
        $this->fileCategoryTagId = $fileCategoryTagId;
        $this->userId = $userId;

        /** Monta a consulta SQL. */
        $this->sql = 'update files_categories_tags set `date_update` = NOW(),
                                                       `user_id_update` = :user_id_update
                      where file_category_tag_id = :file_category_tag_id';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':file_category_tag_id', $this->fileCategoryTagId);
        $this->stmt->bindParam(':user_id_update', $this->userId);

        /** Executa a consulta SQL. */
        return $this->stmt->execute();
    }


    /** Fecha uma conexão aberta anteriormente
     *  com o banco de dados */
    public function __destruct()
    {
        $this->connection = null;
    }
}
