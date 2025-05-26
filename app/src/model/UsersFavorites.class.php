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

class UsersFavorites
{
    /** Declaro as vaiavéis da classe */
    private $connection = null;
    private $sql = null;
    private $stmt = null;

    /** Construtor da classe */
    public function __construct()
    {
        /** Cria o objeto de conexão com o banco de dados */
        $this->connection = new Mysql();

    }

    /** Localiza um usuário pelo e-mail e senha */
    public function Save(int $userFavoriteId, int $userId, string $table, int $registerId)
    {

        /** Consulta SQL */
        $this->sql = 'INSERT INTO users_favorites (`user_favorite_id`, `user_id`, `table`, `register_id`) value (:userFavoriteId, :userId, :table, :registerId)';

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':userFavoriteId', $userFavoriteId);
        $this->stmt->bindParam(':userId', $userId);
        $this->stmt->bindParam(':table', $table);
        $this->stmt->bindParam(':registerId', $registerId);

        /** Executo o SQL */
        return $this->stmt->execute();

    }

    /** Localiza um usuário pelo e-mail e senha */
    public function AllRegisterIdByTable(string $table) : array
    {

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'select register_id from users_favorites uf where uf.table = :table ';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':table', $table);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchAll(\PDO::FETCH_COLUMN);

    }

    /** Localiza um usuário pelo e-mail e senha */
    public function Get(int $companyId) : object|bool
    {

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'select * from companies where company_id = :companyId';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':companyId', $companyId);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchObject();

    }

    /** Localiza um usuário pelo e-mail e senha */
    public function GetByUserIdTableRegisterId(int $userId, $table, $registerId) : object|bool
    {

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'select * from users_favorites uf 
                      where uf.user_id = :userId
                      and uf.table = :table
                      and uf.register_id = :registerId
                      limit 1';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':userId', $userId);
        $this->stmt->bindParam(':table', $table);
        $this->stmt->bindParam(':registerId', $registerId);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchObject();

    }

    /** Localiza um usuário pelo e-mail e senha */
    public function Delete(int $userId, $table, $registerId) : object|bool
    {

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'delete from users_favorites uf 
                      where uf.user_id = :userId
                      and uf.table = :table
                      and uf.register_id = :registerId';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':userId', $userId);
        $this->stmt->bindParam(':table', $table);
        $this->stmt->bindParam(':registerId', $registerId);

        /** Executa a consulta SQL. */
        return $this->stmt->execute();

    }
    
    /** Fecha uma conexão aberta anteriormente com o banco de dados */
    public function __destruct()
    {

        $this->connection = null;

    }

}
