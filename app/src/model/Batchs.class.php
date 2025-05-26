<?php

/**
 * Classe Batchs.class.php
 * @filesource
 * @autor		Kenio de Souza
 * @copyright	Copyright 2025 - Souza Consultoria Tecnológica
 * @package		vendor
 * @subpackage	model
 * @version		1.0
 * @date			03/03/2025
 */


/** Defino o local onde esta a classe */

namespace src\model;

class Batchs
{
	/** Declaro as vaiavéis da classe */
	private $connection = null;
	private $sql = null;
	private $stmt = null;
	private ?int $start = null;
	private ?int $max = null;
	private ?string $limit = null;
	private ?int $batchId = null;
	private ?int $companyId = null;
	private ?int $fileCategoryId = null;
	private ?string $dateCreate = null;
	private ?string $dateUpdate = null;
	private ?int $userIdCreate = null;
	private ?int $userIdUpdate = null;
	private ?int $userId = null;
	private ?string $description = null;
	private ?string $search = null;
	private ?string $status = null;
	private ?array $field = null;

	/** Construtor da classe */
	function __construct()
	{
		/** Cria o objeto de conexão com o banco de dados */
		$this->connection = new Mysql();
	}

	/** Carrega os campos de uma tabela */
	public function Describe()
	{

		/** Consulta SQL */
		$this->sql = "describe batchs";

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

	/** Lista os registros do banco de dados com limitação */
	public function Get(int $batchId)
	{

		/** Parametros de entrada */
		$this->batchId = $batchId;

		/** Consulta SQL */
		$this->sql = 'select * from batchs  
					  where batch_id = :batch_id';

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam(':batch_id', $this->batchId);

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject();
	}

	/** Lista todos os egistros do banco com ou sem paginação*/
	public function All(?int $start, ?int $max, ?string $search)
	{
		/** Parametros de entrada */
		$this->start = $start;
		$this->max = $max;
		$this->search = !empty($search) ? '%' . $search . '%' : null;

		/** Verifico se há paginação */
		if ($this->max) {
			$this->limit = "limit $this->start, $this->max";
		}

		/** Consulta SQL */
		$this->sql = 'select b.batch_id,
					         b.company_id,
					         b.file_category_id,
					         b.user_id_create,
					         b.user_id_update,
					         b.date_create,
					         b.date_update,
					         b.description,
					         b.status,
							 fc.description as category,
                             ft.description as type
                      from batchs b
					  left join files_categories fc on b.file_category_id = fc.file_category_id 
					  left join files_types ft on fc.file_type_id = ft.file_type_id
		              where b.status <> \'D\'';

		/** Verifica se a consulta foi informada */
		if (!empty($this->search)) {

			$this->sql .= ' and b.description like(:description)';
		}

		$this->sql .= $this->limit;

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Verifica se a consulta foi informada */
		if (!empty($this->search)) {

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam(':description', $this->search);
		}

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
	}

	/** Conta a quantidades de registros */
	public function Count(?string $search)
	{
		/** Parametros de entrada */
		$this->search = !empty($search) ? '%' . $search . '%' : '';

		/** Consulta SQL */
		$this->sql = 'select count(batch_id) as qtde
					  from batchs 
					  where status <> \'D\' ';

		/** Verifica se a consulta foi informada */
		if (!empty($this->search)) {

			$this->sql .= ' and description like(:description)';
		}

		/** Preparo o SQL para execução */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Verifica se a consulta foi informada */
		if (!empty($this->search)) {

			/** Preencho os parâmetros do SQL */
			$this->stmt->bindParam(':description', $this->search);
		}

		/** Executo o SQL */
		$this->stmt->execute();

		/** Retorno o resultado */
		return $this->stmt->fetchObject()->qtde;
	}

	/** Insere um novo registro no banco */
	public function Save(
		?int $batchId,
		?int $companyId,
		?string $fileCategoryId,
		?string $userId,
		?string $description,
		?string $status
	) {


		/** Parametros */
		$this->batchId = $batchId;
		$this->companyId = $companyId;
		$this->fileCategoryId = $fileCategoryId;
		$this->userId = $userId;
		$this->description = $description;
		$this->status = $status;

		/** Consulta SQL */
		$this->sql = 'INSERT INTO batchs (`batch_id`, 
                                          `company_id`, 
                                          `file_category_id`,
										  `user_id_create`,
										  `description`,
                                          `status`) value (
                                          :batch_id, 
                                          :company_id, 
                                          :file_category_id,
										  ' . $this->userId . ',
										  :description,										  
                                          :status)
                      ON DUPLICATE KEY UPDATE `company_id` = :company_id,
					                          `file_category_id` = :file_category_id,
											  `user_id_update` = ' . $this->userId . ',
											  `date_update` = NOW(),
					  						  `description` = :description, 
                                              `status` = :status;';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('batch_id', $this->batchId);
		$this->stmt->bindParam('company_id', $this->companyId);
		$this->stmt->bindParam('file_category_id', $this->fileCategoryId);
		$this->stmt->bindParam('description', $this->description);
		$this->stmt->bindParam('status', $this->status);

		/** Executo o SQL */
		return $this->stmt->execute();
	}

	/** Deleta um determinado registro no banco de dados */
	function Delete(?int $batchId, ?int $userIdUpdate)
	{
		/** Parametros de entrada */
		$this->batchId = $batchId;
		$this->userIdUpdate = $userIdUpdate;

		/** Consulta SQL */
		$this->sql = 'update batchs set status = \'D\',
		                     user_id_update = :user_id_update,
							 date_update = NOW()
					  where  batch_id = :batch_id';

		/** Preparo o sql para receber os valores */
		$this->stmt = $this->connection->connect()->prepare($this->sql);

		/** Preencho os parâmetros do SQL */
		$this->stmt->bindParam('batch_id', $this->batchId);
		$this->stmt->bindParam('user_id_update', $this->userIdUpdate);

		/** Executo o SQL */
		return $this->stmt->execute();
	}

	/** Fecha uma conexão aberta anteriormente com o banco de dados */
	function __destruct()
	{
		$this->connection = null;
	}
}
