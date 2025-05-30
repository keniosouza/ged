<?php

/**
 * Classe Users.class.php
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2021 - Souza Consultoria Tecnológica
 * @package      model
 * @subpackage   model.class
 * @version      1.0
 */

namespace src\model;

class Modules
{
    /** Declaro as variáveis da classe */
    private $connection = null;
    private $sql = null;
    private $stmt = null;

    private ?int $moduleId = null;
    private ?int $companyId = null;
    private ?string $name = null;
    private ?string $description = null;
    private ?int $userId = null;
    private ?string $status = null;
    private ?int $start = null;
    private ?int $max = null;
    private ?string $limit = null;
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
        $this->sql = "describe modules";

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

    /** Retorna a quantidade de registros cadastradas */
    public function Count(?string $search): int
    {

        /** Parametros de entrada */
        $this->search = !empty($search) ? '%' . $search . '%' : '';

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'select count(module_id) as qtde 
                      from modules 
                      where status <> \'D\'';

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            $this->sql .= ' and name like(:name) ';
        }

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            /** Preencho os parâmetros do SQL */
            $this->stmt->bindParam(':name', $this->search);
        }

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorno o resultado */
        return (int)$this->stmt->fetchObject()->qtde;
    }

    /** Localiza um registro pelo ID do módulo */
    public function All(?int $start, ?int $max, ?string $search): array|bool
    {
        /** Parametros de entrada */
        $this->start = $start;
        $this->max = $max;
        $this->search = isset($search) ? '%' . $search . '%' : null;

        /** Verifico se há paginação */
        if ($this->max) {
            $this->limit = " limit $this->start, $this->max";
        }

        /** Monta a consulta SQL para recuperar o registro */
        $this->sql = 'SELECT * FROM modules
                      where status <> \'D\' ';

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            $this->sql .= ' and name like(:name) ';
        }

        /** Informa o limite da consulta */
        $this->sql .= $this->limit;

        /** Prepara a consulta SQL utilizando a conexão estabelecida */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            /** Preencho os parâmetros do SQL */
            $this->stmt->bindParam(':name', $this->search);
        }

        /** Executa a consulta SQL */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /** Localiza um registro pelo ID do módulo */
    public function Get(int $moduleId): object|bool
    {
        /** Monta a consulta SQL para recuperar o registro */
        $this->sql = 'SELECT * FROM modules WHERE module_id = :moduleId';

        /** Prepara a consulta SQL utilizando a conexão estabelecida */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preenche os parâmetros do SQL */
        $this->stmt->bindParam(':moduleId', $moduleId);

        /** Executa a consulta SQL */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um objeto */
        return $this->stmt->fetchObject();
    }

    /** Localiza um registro pelo e-mail */
    public function GetByCompany(int $companyId): array
    {
        /** Monta a consulta SQL para recuperar os registros de uma empresa */
        $this->sql = 'SELECT * FROM modules WHERE company_id = :companyId';

        /** Prepara a consulta SQL utilizando a conexão estabelecida */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preenche os parâmetros do SQL */
        $this->stmt->bindParam(':companyId', $companyId);

        /** Executa a consulta SQL */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /** Salva um registro */
    public function Save(
        int $userId,
        int $moduleId,
        int $companyId,
        string $name,
        string $description,
        string $status
    ) {
        /** Parametros de entrada */
        $this->userId = $userId;
        $this->moduleId = $moduleId;
        $this->companyId = $companyId;
        $this->name = $name;
        $this->description = $description;
        $this->status = $status;


        /** Consulta SQL para inserir ou atualizar */
        $this->sql = 'INSERT INTO modules (`module_id`, 
                                           `company_id`,
                                           `user_id_create`, 
                                           `name`, 
                                           `description`,
                                           `status`) 
                                   VALUES (:module_id, 
                                           :company_id,
                                           ' . $this->userId . ', 
                                           :name, 
                                           :description,
                                           :status)
                      ON DUPLICATE KEY UPDATE `company_id` = :company_id, 
                                              `user_id_update` = ' . $this->userId . ',
                                              `date_update` = NOW(),
                                              `name` = :name, 
                                              `description` = :description,
                                              `status` = :status;';

        /** Prepara o SQL para receber os valores */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preenche os parâmetros do SQL */
        $this->stmt->bindParam(':module_id', $this->moduleId);
        $this->stmt->bindParam(':company_id', $this->companyId);
        $this->stmt->bindParam(':name', $this->name);
        $this->stmt->bindParam(':description', $this->description);
        $this->stmt->bindParam(':status', $this->status);

        /** Executa o SQL */
        return $this->stmt->execute();
    }

    /** Remove um registro */
    public function Delete(int $moduleId, int $userId): bool
    {
        /** Parametros de entrada */
        $this->userId = $userId;
        $this->moduleId = $moduleId;

        /** Monta a consulta SQL para excluir o registro */
        $this->sql = 'update modules set user_id_update = :user_id_update,
                                         date_update = NOW(),
                                         status = \'D\'
                      WHERE module_id = :moduleId';

        /** Prepara a consulta SQL utilizando a conexão estabelecida */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preenche os parâmetros do SQL */
        $this->stmt->bindParam(':moduleId', $this->moduleId);
        $this->stmt->bindParam(':user_id_update', $this->userId);

        /** Executa a consulta SQL */
        return $this->stmt->execute();
    }

    /** Fecha uma conexão aberta anteriormente com o banco de dados */
    public function __destruct()
    {
        $this->connection = null;
    }
}
