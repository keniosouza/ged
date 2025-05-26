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

class FilesTypes
{
    /** Declaro as vaiavéis da classe */
    private $connection = null;
    private $sql = null;
    private $stmt = null;
    private ?int $fileTypeId = null;
    private ?int $companyId = null;
    private ?string $description = null;
    private ?string $status = null;
    private ?int $userId = null;
    private ?string $search = null;
    private ?int $start = null;
    private ?int $max = null;
    private ?string $limit = null;
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
        $this->sql = "describe files_types";

        /** Preparo o SQL para execução */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

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
        int $fileTypeId,
        int $companyId,
        string $description,
        string $status,
        int $userId
    ) {

        /** Parametros de entrada */
        $this->fileTypeId = $fileTypeId;
        $this->companyId = $companyId;
        $this->description = $description;
        $this->status = $status;
        $this->userId = $userId;

        /** Consulta SQL */
        $this->sql = 'INSERT INTO files_types (`file_type_id`, 
                                               `company_id`, 
                                               `description`,
                                               `status`,
                                               `user_id_create`) value (
                                               :file_type_id, 
                                               :company_id, 
                                               :description,
                                               :status,
                                               ' . $this->userId . ')
                      ON DUPLICATE KEY UPDATE `company_id` = :company_id, 
                                              `description` = :description,
                                              `status` = :status,
                                              `date_update` = NOW(),
                                              `user_id_update` = ' . $this->userId . ';';

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':file_type_id', $this->fileTypeId);
        $this->stmt->bindParam(':company_id', $this->companyId);
        $this->stmt->bindParam(':description', $this->description);
        $this->stmt->bindParam(':status', $this->status);

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
        $this->sql = 'select count(file_type_id) as qtde 
                      from files_types 
                      where status <> \'D\'';

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            $this->sql .= ' and description like(:description)';
        }

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

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

    /** Lista todos os regitros */
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
        $this->sql = 'select * from files_types where `status` <> \'D\'';

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            $this->sql .= ' and description like(:description)';
        }

        /** Informa a limitação de registros */
        $this->sql .= $this->limit;

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

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

    /** Localiza um registro especificio */
    public function Get(
        int $fileTypeId
    ): object|bool {
        /** Parametros de entrada */
        $this->fileTypeId = $fileTypeId;

        /** Monta a consulta SQL . */
        $this->sql = 'select * from files_types where file_type_id = :file_type_id';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':file_type_id', $this->fileTypeId);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchObject();
    }

    /** Exclui um registro especifico */
    public function Delete(
        int $fileTypeId,
        int $userId
    ): object|bool {
        /** Parametros de entrada */
        $this->fileTypeId = $fileTypeId;
        $this->userId = $userId;

        /** Atualiza o status do registro para D => Deletado. */
        $this->sql = 'update files_types set `status` = \'D\',
                                             `date_update` = NOW(),
                                             `user_id_update` = :user_id
                      where file_type_id = :file_type_id';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':file_type_id', $this->fileTypeId);
        $this->stmt->bindParam(':user_id', $this->userId);

        /** Executa a consulta SQL. */
        return $this->stmt->execute();
    }

    /** Fecha uma conexão aberta anteriormente com o banco de dados */
    public function __destruct()
    {

        $this->connection = null;
    }
}
