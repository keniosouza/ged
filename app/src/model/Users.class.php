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

class Users
{
    /** Declaro as vaiavéis da classe */
    private $connection = null;
    private $sql = null;
    private $stmt = null;
    private ?int $start = null;
    private ?int $max = null;
    private ?string $limit = null;
    private ?int $userId = null;
    private ?string $name = null;
    private ?string $search = null;
    private ?string $email = null;
    private ?string $password = null;
    private ?string $operationUserID = null;
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
        $this->sql = "describe users";

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
    public function Login(string $email, string $password): object
    {

        /** Parametros de entrada */
        $this->email = $email;
        $this->password = $password;

        /** Consulta SQL */
        $this->sql = 'select u.user_id,
                             u.name
					  from users u
					  where u.email = :email
					        and u.password = :password;';

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':email', $this->email);
        $this->stmt->bindParam(':password', $this->password);

        /** Executo o SQL */
        $this->stmt->execute();

        /** Retorno o resultado */
        return $this->stmt->fetchObject();
    }

    /** Localiza um usuário pelo e-mail e senha */
    public function Save(
        int $operationUserID,
        int $userId,
        string $name,
        string $team,
        string $position,
        string $status,
        string $passwordTempConfirm,
        string $email,
        string $passwordTemp,
        int $companyId
    ) {

        /** Consulta SQL */
        $this->sql = 'INSERT INTO users (`user_id`, 
                                         `company_id`, 
                                         `name`, 
                                         `email`, 
                                         `password`, 
                                         `position`, 
                                         `team`,
                                         `status`,
                                         `password_temp`,
                                         `password_temp_confirm`) value 
                                        (:userId, 
                                         :companyId, 
                                         :name, 
                                         :email, 
                                         :password, 
                                         :position, 
                                         :team,
                                         :status,
                                         :password_temp,
                                         :password_temp_confirm)
                      ON DUPLICATE KEY UPDATE `company_id` = :companyId, 
                                              `name` = :name, 
                                              `email` = :email, 
                                              `password` = :password, 
                                              `position` = :position, 
                                              `team` = :team,
                                              `status` = :status,
                                              `password_temp` = :password_temp,
                                              `password_temp_confirm` = :password_temp_confirm,
                                              `user_id_update` = :user_id_update,
                                              `date_update` = NOW()';

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':userId', $userId);
        $this->stmt->bindParam(':companyId', $companyId);
        $this->stmt->bindParam(':name', $name);
        $this->stmt->bindParam(':email', $email);
        $this->stmt->bindParam(':password', $password);
        $this->stmt->bindParam(':position', $position);
        $this->stmt->bindParam(':team', $team);
        $this->stmt->bindParam(':status', $status);
        $this->stmt->bindParam(':password_temp', $passwordTemp);
        $this->stmt->bindParam(':password_temp_confirm', $passwordTempConfirm);
        $this->stmt->bindParam(':user_id_update', $operationUserID);


        /** Executo o SQL */
        return $this->stmt->execute();
    }

    /** Localiza um usuário pelo e-mail e senha */
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
        $this->sql = 'select u.user_id,
                             u.company_id,
                             u.name,
                             u.email,
                             u.password,
                             u.password_temp,
                             u.password_temp_confirm,
                             u.position,
                             u.team,
                             u. status,
                             u.date_register,
                             u.date_update,
                             u.user_id_create,
                             u.user_id_update 
                      from users u 
                      where u.status <> \'D\'';

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            $this->sql .= ' and u.name like(:name) ';
        }

        $this->sql .= $this->limit;

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            /** Preencho os parâmetros do SQL */
            $this->stmt->bindParam(':name', $this->search);
        }

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /** Retorna a quantidade de registros */
    public function Count(?string $search): int
    {
        /** Parametros de entrada */
        $this->search = isset($search) ? '%' . $search . '%' : null;

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'select count(u.user_id) as qtde 
                      from users u
                      where u.status <> \'D\' ';

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            $this->sql .= ' and u.name like(:name) ';
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

        /** Retorna o resultado da consulta como um array de objetos. */
        return (int)$this->stmt->fetchObject()->qtde;
    }

    /** Localiza um usuário pelo e-mail e senha */
    public function AllByCompanyId(int $companyId): array
    {

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'select * from users u where u.company_id = :companyId';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':companyId', $companyId);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /** Localiza um usuário pelo e-mail e senha */
    public function Get(int $userId): object|bool
    {

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'select * from users where user_id = :userId';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':userId', $userId);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchObject();
    }

    /** Localiza um usuário pelo e-mail e senha */
    public function GetByEmail(string $email): object|bool
    {

        try{
            /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
            $this->sql = 'select * from users where email like :email order by user_id desc limit 1 ';

            /** Prepara a consulta SQL utilizando a conexão estabelecida. */
            $this->stmt = $this->connection->prepare($this->sql);

            /** Preencho os parâmetros do SQL */
            $this->stmt->bindParam(':email', $email);

            /** Executa a consulta SQL. */
            $this->stmt->execute();

            /** Retorna o resultado da consulta como um array de objetos. */
            return $this->stmt->fetchObject();

        } catch (PDOException $e){

            echo $e->getMessage();
            return false;            
        }

    } 


    /** Localiza um usuário pelo e-mail e senha */
    public function Delete(int $userId, int $operationUserID): object|bool
    {

        /** Parametros de entrada */
        $this->userId = $userId;
        $this->operationUserID = $operationUserID;

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'update users set `status` = \'D\', 
                                       `date_update` = NOW(),
                                       `user_id_update` = :user_id_update
                      where user_id = :user_id';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':user_id', $this->userId);
        $this->stmt->bindParam(':user_id_update', $this->operationUserID);

        /** Executa a consulta SQL. */
        return $this->stmt->execute();
    }

    /** Atualiza a nova senha do usuário */
    public function SaveNewPassword(int $userId, string $password): object|bool
    {

        /** Parametros de entrada */
        $this->userId = $userId;
        $this->password = $password;

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'update users set `date_update` = NOW(),
                                       `user_id_update` = :user_id_update,
                                       `password` = :password
                      where user_id = :user_id';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':user_id', $this->userId);
        $this->stmt->bindParam(':user_id_update', $this->userId);
        $this->stmt->bindParam(':password', $this->password);

        /** Executa a consulta SQL. */
        return $this->stmt->execute();
    }


    /** Gera um password hash */
    public function passwordHash($password)
    {

        /** Parametros de entradas */
        $this->password = $password;

        /** Verifica se a senha foi informada */
        if ($this->password) {

            $hash = PASSWORD_DEFAULT;
            /** Padrão de criptogrfia */
            $cost = array("cost" => 10);
            /** Nível de criptografia */

            /** Gera o hash da senha */
            return password_hash($this->password, $hash, $cost);
        }
    }

    /** Fecha uma conexão aberta anteriormente com o banco de dados */
    public function __destruct()
    {

        $this->connection = null;
    }
}
