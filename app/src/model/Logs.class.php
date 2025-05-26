<?php

/** Defino o local da classe */

namespace src\model;

class Logs
{

    /** Variaveis da classe */
    private $connection = null;
    private $sql = null;
    private $stmt = null;
    private ?int $logId = null;
    private ?int $logTypeId = null;
    private ?int $companyId = null;
    private ?int $parentId = null;
    private ?int $registerId = null;
    private ?int $userId = null;
    private ?string $request = null;
    private ?string $data = null;
    private ?string $dateRegister = null;

    /** Construtor da classe */
    public function __construct()
    {

        /** Instanciamento da classe */
        $this->connection = new Mysql();
    }

    /** Método para salvar um registro */
    public function Save(int $logId, int $logTypeId, int $companyId, ?int $parentId, ?int $registerId, int $userId, string $request, string $data, string $dateRegister)
    {

        /** Parametros de entrada */
        $this->logId = $logId;
        $this->logTypeId = $logTypeId;
        $this->companyId = $companyId;
        $this->parentId = $parentId;
        $this->registerId = $registerId;
        $this->userId = $userId;
        $this->request = $request;
        $this->data = $data;
        $this->dateRegister = $dateRegister;

        /** Sql para inserção */
        $this->sql = 'INSERT INTO logs(`log_id`, 
                                       `log_type_id`, 
                                       `company_id`, 
                                       `parent_id`, 
                                       `register_id`, 
                                       `user_id`, 
                                       `request`, 
                                       `data`, 
                                       `date_register`) 
                                VALUES(:log_id, 
                                       :log_type_id, 
                                       :company_id, 
                                       :parent_id, 
                                       :register_id, 
                                       :user_id, 
                                       :request, 
                                       :data, 
                                       :date_register)';

        /** Preparo o SQL */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os valores do sql */
        $this->stmt->bindParam(':log_id', $this->logId, $this->logId > 0 ? \PDO::PARAM_INT : \PDO::PARAM_NULL);
        $this->stmt->bindParam(':log_type_id', $this->logTypeId, $this->logTypeId > 0 ? \PDO::PARAM_INT : \PDO::PARAM_NULL);
        $this->stmt->bindParam(':company_id', $this->companyId, $this->companyId > 0 ? \PDO::PARAM_INT : \PDO::PARAM_NULL);
        $this->stmt->bindParam(':parent_id', $this->parentId, $this->parentId > 0 ? \PDO::PARAM_INT : \PDO::PARAM_NULL);
        $this->stmt->bindParam(':register_id', $this->registerId, $this->registerId > 0 ? \PDO::PARAM_INT : \PDO::PARAM_NULL);
        $this->stmt->bindParam(':user_id', $this->userId, $this->userId > 0 ? \PDO::PARAM_INT : \PDO::PARAM_NULL);
        $this->stmt->bindParam(':request', $this->request);
        $this->stmt->bindParam(':data', $this->data);
        $this->stmt->bindParam(':date_register', $this->dateRegister);

        /** Execução do sql */
        return $this->stmt->execute();
    }

    public function AllByRegisterIdAndRequest(int $registerId, string $request)
    {

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'SELECT l.*, u.* FROM logs l
                      JOIN users u on l.user_id = u.user_id
                      WHERE l.register_id = :registerId 
                      and l.request like :request
                      ORDER BY l.log_id DESC';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        //Ajusto para pesquisa partes de palavras
        $request = '%' . $request . '%';

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':registerId', $registerId, \PDO::PARAM_STR);
        $this->stmt->bindParam(':request', $request, \PDO::PARAM_STR);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function AllByRequest(string $request)
    {

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'SELECT l.*, u.* FROM logs l
                      JOIN users u on l.user_id = u.user_id
                      WHERE l.request like :request
                      ORDER BY l.log_id DESC
                      limit 10';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        //Ajusto para pesquisa partes de palavras
        $request = '%' . $request . '%';

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':request', $request, \PDO::PARAM_STR);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function AllByCompanyId(int $companyId)
    {

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'SELECT l.*, u.* FROM logs l
                      JOIN users u on l.user_id = u.user_id
                      WHERE l.company_id = :companyId
                      ORDER BY l.log_id DESC 
                      LIMIT 100';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':companyId', $companyId, \PDO::PARAM_STR);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function GetFirstByTableByRegisterId(string $request, int $registerId)
    {


        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'SELECT l.*
                        FROM logs l
                        WHERE l.register_id = :registerId 
                          AND l.request LIKE :request
                        LIMIT 1;';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        // Ajusto para pesquisa partes de palavras
        $request = '%' . $request . '%';

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':request', $request, \PDO::PARAM_STR);
        $this->stmt->bindParam(':registerId', $registerId, \PDO::PARAM_STR);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchObject();
    }

    public function GraphByCompanyId(int $companyId)
    {

        /** Monta a consulta SQL para recuperar os chamados abertos da empresa. */
        $this->sql = 'SELECT 
                            COUNT(l.log_id) AS quantity,
                            DATE_FORMAT(l.date_register, \'%d-%m-%Y\') AS date_register_formated
                        FROM 
                            logs l
                        WHERE l.company_id = :companyId
                        GROUP BY 
                            date_register_formated;';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':companyId', $companyId, \PDO::PARAM_STR);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /** Destrutor da classe */
    public function __destruct()
    {

        /** Instanciamento da classe */
        $this->connection = null;
    }
}
