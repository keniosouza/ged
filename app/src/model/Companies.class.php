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

class Companies
{
    /** Declaro as vaiavéis da classe */
    private $connection = null;
    private $sql = null;
    private $stmt = null;
    private ?int $companyId = null;
    private ?int $start = null;
    private ?int $max = null;
    private ?string $limit = null;
    private ?string $search = null;
    private ?string $nameBusiness = null;
    private ?string $nameFantasy = null;
    private ?string $status = null;
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
        $this->sql = "describe companies";

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

    /** Localiza um usuário pelo e-mail e senha */
    public function Save(int $companyId, string $nameBusiness, string $nameFantasy, string $status)
    {
        /** Parametros de entrada */
        $this->companyId = $companyId;
        $this->nameBusiness = $nameBusiness;
        $this->nameFantasy = $nameFantasy;
        $this->status = $status;

        /** Consulta SQL */
        $this->sql = 'INSERT INTO companies (`company_id`, 
                                             `name_business`, 
                                             `name_fantasy`,
                                             `status`) value (
                                             :companyId, 
                                             :nameBusiness, 
                                             :nameFantasy,
                                             :status)
                      ON DUPLICATE KEY UPDATE `name_business` = :nameBusiness, 
                                              `name_fantasy` = :nameFantasy,
                                              `status` = :status;';

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':companyId', $companyId);
        $this->stmt->bindParam(':nameBusiness', $nameBusiness);
        $this->stmt->bindParam(':nameFantasy', $nameFantasy);
        $this->stmt->bindParam(':status', $status);

        /** Executo o SQL */
        return $this->stmt->execute();
    }

    /** Retorna a quantidade de registros cadastradas */
    public function Count(?string $search): int
    {

        /** Parametros de entrada */
        $this->search = !empty($search) ? '%' . $search . '%' : '';

        /** Verifico se há paginação */
        if ($this->max) {
            $this->limit = " limit $this->start, $this->max";
        }

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'select count(company_id) as qtde 
                      from companies 
                      where status <> \'D\'';

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            $this->sql .= ' and name_fantasy like(:name_fantasy) ';
        }

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            /** Preencho os parâmetros do SQL */
            $this->stmt->bindParam(':name_fantasy', $this->search);
        }

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorno o resultado */
        return (int)$this->stmt->fetchObject()->qtde;
    }

    /** Lista todas as empresas */
    public function All(?int $start, ?int $max, ?string $search): array
    {

        /** Parametros de entrada */
        $this->start = $start;
        $this->max = $max;
        $this->search = isset($search) ? '%' . $search . '%' : null;

        /** Verifico se há paginação */
        if ($this->max) {
            $this->limit = " limit $this->start, $this->max";
        }

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'select * from companies where status <> \'D\'';

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            $this->sql .= ' and name_fantasy like(:name_fantasy) ';
        }

        $this->sql .= $this->limit;

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            /** Preencho os parâmetros do SQL */
            $this->stmt->bindParam(':name_fantasy', $this->search);
        }

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /** Localiza uma empresa pelo ID */
    public function Get(int $companyId): object|bool
    {

        /** Parametros de entrada */
        $this->companyId = $companyId;

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'select * from companies where company_id = :companyId';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':companyId', $this->companyId);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchObject();
    }


    /** Remove uma empresa pelo ID */
    public function Delete(int $companyId): object|bool
    {

        /** Parametros de entrada */
        $this->companyId = $companyId;

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'update companies set status = \'D\' 
                      where company_id = :companyId';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':companyId', $this->companyId);

        /** Executa a consulta SQL. */
        return $this->stmt->execute();
    }


    /** Fecha uma conexão aberta 
     * anteriormente com 
     * o banco de dados */
    public function __destruct()
    {
        $this->connection = null;
    }
}
