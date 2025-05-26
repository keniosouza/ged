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

class ModulesAcls
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
        $this->sql = 'SELECT ma.*, m.description as module_description FROM modules_acls ma
                      JOIN modules m ON ma.module_id = m.module_id';

        /** Prepara a consulta SQL utilizando a conexão estabelecida */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Executa a consulta SQL */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /** Localiza um registro pelo ID do módulo */
    public function AllByModuleId($moduleId): array|bool
    {
        /** Monta a consulta SQL para recuperar o registro */
        $this->sql = 'SELECT * FROM modules_acls ma WHERE ma.module_id = :moduleId';

        /** Prepara a consulta SQL utilizando a conexão estabelecida */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preenche os parâmetros do SQL */
        $this->stmt->bindParam(':moduleId', $moduleId);

        /** Executa a consulta SQL */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /** Localiza um registro pelo ID do módulo */
    public function Get(int $moduleAclId): object|bool
    {
        /** Monta a consulta SQL para recuperar o registro */
        $this->sql = 'SELECT * FROM modules_acls WHERE module_acl_id = :moduleAclId';

        /** Prepara a consulta SQL utilizando a conexão estabelecida */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preenche os parâmetros do SQL */
        $this->stmt->bindParam(':moduleAclId', $moduleAclId);

        /** Executa a consulta SQL */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um objeto */
        return $this->stmt->fetchObject();
    }

    /** Salva um registro */
    public function Save(int $moduleAclId, int $moduleId, string $description, string $preferences)
    {
        /** Consulta SQL para inserir ou atualizar */
        $this->sql = 'INSERT INTO modules_acls (`module_acl_id`, `module_id`, `description`, `preferences`) 
                      VALUES (:moduleAclId, :moduleId, :description, :preferences)
                      ON DUPLICATE KEY UPDATE `module_id` = :moduleId, `description` = :description, `preferences` = :preferences;';

        /** Prepara o SQL para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preenche os parâmetros do SQL */
        $this->stmt->bindParam(':moduleAclId', $moduleAclId);
        $this->stmt->bindParam(':moduleId', $moduleId);
        $this->stmt->bindParam(':description', $description);
        $this->stmt->bindParam(':preferences', $preferences);

        /** Executa o SQL */
        return $this->stmt->execute();
    }

    /** Remove um registro */
    public function Delete(int $moduleAclId): bool
    {
        /** Monta a consulta SQL para excluir o registro */
        $this->sql = 'DELETE FROM modules_acls WHERE module_acl_id = :moduleAclId';

        /** Prepara a consulta SQL utilizando a conexão estabelecida */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preenche os parâmetros do SQL */
        $this->stmt->bindParam(':moduleAclId', $moduleAclId);

        /** Executa a consulta SQL */
        return $this->stmt->execute();
    }

    /** Fecha uma conexão aberta anteriormente com o banco de dados */
    public function __destruct()
    {
        $this->connection = null;
    }
}
