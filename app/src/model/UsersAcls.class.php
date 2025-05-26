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

class UsersAcls
{
    /** Declaro as variáveis da classe */
    private $connection = null;
    private $sql = null;
    private $stmt = null;

    /** Construtor da classe */
    public function __construct()
    {
        /** Cria o objeto de conexão com o banco de dados */
        $this->connection = new Mysql();
    }

    /** Localiza um registro pelo ID do módulo */
    public function All(): array|bool
    {
        /** Monta a consulta SQL para recuperar o registro */
        $this->sql = 'SELECT * FROM users_acls';

        /** Prepara a consulta SQL utilizando a conexão estabelecida */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Executa a consulta SQL */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /** Localiza um registro pelo ID do módulo */
    public function AllByUserId($userId): array|bool
    {
        /** Monta a consulta SQL para recuperar o registro */
        $this->sql = 'SELECT ua.*, 
                       ma.description as module_acl_description,
                       ma.preferences,
                       m.description as module_description 
                       FROM users_acls ua
                      JOIN modules_acls ma on ma.module_acl_id = ua.module_acl_id
                      JOIN modules m on ma.module_id = m.module_id
                      WHERE ua.user_id = :userId';

        /** Prepara a consulta SQL utilizando a conexão estabelecida */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preenche os parâmetros do SQL */
        $this->stmt->bindParam(':userId', $userId);

        /** Executa a consulta SQL */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /** Localiza um registro pelo ID do módulo */
    public function Get(int $userAclId): object|bool
    {
        /** Monta a consulta SQL para recuperar o registro */
        $this->sql = 'SELECT * FROM users_acls WHERE user_acl_id = :userAclId';

        /** Prepara a consulta SQL utilizando a conexão estabelecida */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preenche os parâmetros do SQL */
        $this->stmt->bindParam(':userAclId', $userAclId);

        /** Executa a consulta SQL */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um objeto */
        return $this->stmt->fetchObject();
    }

    /** Salva um registro */
    public function Save(int $userAclId, int $moduleAclId, int $userId)
    {
        /** Consulta SQL para inserir ou atualizar */
        $this->sql = 'INSERT INTO users_acls (`user_acl_id`, `module_acl_id`, `user_id`) 
                      VALUES (:userAclId, :moduleAclId, :userId)
                      ON DUPLICATE KEY UPDATE `module_acl_id` = :moduleAclId, `user_id` = :userId;';

        /** Prepara o SQL para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preenche os parâmetros do SQL */
        $this->stmt->bindParam(':userAclId', $userAclId);
        $this->stmt->bindParam(':moduleAclId', $moduleAclId);
        $this->stmt->bindParam(':userId', $userId);
        /** Executa o SQL */
        return $this->stmt->execute();
    }

    /** Remove um registro */
    public function Delete(int $userAclId): bool
    {
        /** Monta a consulta SQL para excluir o registro */
        $this->sql = 'DELETE FROM users_acls WHERE user_acl_id = :userAclId';

        /** Prepara a consulta SQL utilizando a conexão estabelecida */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preenche os parâmetros do SQL */
        $this->stmt->bindParam(':userAclId', $userAclId);

        /** Executa a consulta SQL */
        return $this->stmt->execute();
    }

    /** Fecha uma conexão aberta anteriormente com o banco de dados */
    public function __destruct()
    {
        $this->connection = null;
    }

}
