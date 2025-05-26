<?php
/**
 * Classe Users.class.php
 * @foldersource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2021 - Souza Consultoria Tecnológica
 * @package        model
 * @subpackage    model.class
 * @version        1.0
 */

/** Defino o local onde esta a classe */
namespace src\model;

class Folders
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
    public function Save($folderId, $companyId, $parentId, $name, $description)
    {
        /** Consulta SQL */
        $this->sql = 'INSERT INTO folders (`folder_id`, `company_id`, `parent_id`, `name`, `description`) VALUES(:folderId, :companyId, :parentId, :name, :description)
                      ON DUPLICATE KEY UPDATE `company_id` = :companyId, `parent_id` = :parentId, `name` = :name, `description` = :description;';

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':folderId', $folderId, $folderId > 0 ? \PDO::PARAM_INT : \PDO::PARAM_NULL);
        $this->stmt->bindParam(':companyId', $companyId, $companyId > 0 ? \PDO::PARAM_INT : \PDO::PARAM_NULL);
        $this->stmt->bindParam(':parentId', $parentId, $parentId > 0 ? \PDO::PARAM_INT : \PDO::PARAM_NULL);
        $this->stmt->bindParam(':name', $name);
        $this->stmt->bindParam(':description', $description);

        /** Executo o SQL */
        return $this->stmt->execute();

    }

    /** Localiza um usuário pelo e-mail e senha */
    public function All() : array
    {

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'select * from folders';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

    }

    /** Localiza um usuário pelos IDs do arquivo */
    public function AllInFolderId(array $fileIds): array
    {
        /** Cria a quantidade correta de placeholders '?' com base no número de elementos em $fileIds */
        $placeholders = implode(',', array_fill(0, count($fileIds), '?'));

        /** Monta a consulta SQL para recuperar os arquivos com base nos IDs fornecidos */
        $this->sql = "SELECT * FROM folders f WHERE f.folder_id IN ($placeholders)";

        /** Prepara a consulta SQL utilizando a conexão estabelecida */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Executa a consulta SQL com o array de IDs sendo passados como parâmetros */
        $this->stmt->execute($fileIds);

        /** Retorna o resultado da consulta como um array de objetos */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);

    }
    

    /** Localiza um usuário pelo e-mail e senha */
    public function Get(int $folderId) : object|bool
    {

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'select * from folders where folder_id = :folderId';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':folderId', $folderId);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchObject();

    }

    /** Localiza um usuário pelo e-mail e senha */
    public function Delete(int $folderId) : object|bool
    {

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'delete from folders f where f.folder_id = :folderId';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':folderId', $folderId);

        /** Executa a consulta SQL. */
        return $this->stmt->execute();
        
    }

    /** Fecha uma conexão aberta anteriormente com o banco de dados */
    public function __destruct()
    {

        $this->connection = null;

    }

}
