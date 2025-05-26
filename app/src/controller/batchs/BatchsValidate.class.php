<?php

/**
 * Classe BatchsValidate.class.php
 * @filesource
 * @autor		Kenio de Souza
 * @copyright	Copyright 2025 - Souza Consultoria Tecnológica
 * @package		vendor
 * @subpackage	controller/batchs
 * @version		1.0
 * @date		 	03/03/2025
 */


/** Defino o local onde esta a classe */

namespace src\controller\batchs;

/** Importação de classes */

use src\controller\main\Main;

class BatchsValidate
{
	/** Declaro as variavéis da classe */
	private $Main = null;
	private $errors = array();
	private $info = null;
	private ?int $batchId = null;
	private ?int $companyId = null;
	private ?int $fileCategoryId = null;
	private ?int $userIdCreate = null;
	private ?int $userIdUpdate = null;
	private ?int $userId = null;
	private ?string $dateCreate = null;
	private ?string $dateUpdate = null;
	private ?string $description = null;
	private ?string $status = null;
	private ?string $search = null;
	private ?int $start = null;
	private ?int $page = null;
	private ?int $max = null;

	/** Construtor da classe */
	function __construct()
	{

		/** Instânciamento da classe de validação */
		$this->Main = new Main();
	}

	/** Trata o campo start */
	public function setStart(?int $start): void
	{
		/** Tratamento da informação */
		$this->start = $start > 0 ? (int)$this->Main->antiInjection($start) : 0;
	}

	/** Retorna o campo start */
	public function getStart(): ?int
	{
		return (int)$this->start;
	}

	/** Trata o campo page */
	public function setPage(?int $page): void
	{
		/** Tratamento da informação */
		$this->page = $page > 0 ? (int)$this->Main->antiInjection($page) : 0;
	}

	/** Retorna o campo page */
	public function getPage(): ?int
	{
		return (int)$this->page;
	}

	/** Trata o campo max */
	public function setMax(?int $max): void
	{
		/** Tratamento da informação */
		$this->max = $max > 0 ? (int)$this->Main->antiInjection($max) : 0;
	}

	/** Retorna o campo max */
	public function getMax(): ?int
	{
		return (int)$this->max;
	}

	/** Método trata campo batch_id */
	public function setBatchId(?int $batchId): void
	{

		/** Trata a entrada da informação  */
		$this->batchId = $batchId > 0 ? (int)$this->Main->antiInjection($batchId) : 0;

		/** Verifica se a informação foi informada */
		// if (empty($this->batchId)) {

		// 	/** Adição de elemento */
		// 	array_push($this->errors, 'O campo "batch_id", deve ser informado');
		// }
	}

	/** Método trata campo user_id */
	public function setUserId(int $userId): void
	{

		/** Trata a entrada da informação  */
		$this->userId = $userId > 0 ? (int)$this->Main->antiInjection($userId) : 0;

		/** Verifica se a informação foi informada */
		if ($this->userId == 0) {

			/** Adição de elemento */
			array_push($this->errors, 'Informe um usuário para esta solicitação');
		}
	}

	/** Método trata campo company_id */
	public function setCompanyId(?int $companyId): void
	{

		/** Trata a entrada da informação  */
		$this->companyId = $companyId > 0 ? (int)$this->Main->antiInjection($companyId) : 0;

		/** Verifica se a informação foi informada */
		if ($this->companyId == 0) {

			/** Adição de elemento */
			array_push($this->errors, 'O campo "company_id", deve ser informado');
		}
	}

	/** Método trata campo file_category_id */
	public function setFileCategoryId(?int $fileCategoryId): void
	{

		/** Trata a entrada da informação  */
		$this->fileCategoryId = $fileCategoryId > 0 ? (int)$this->Main->antiInjection($fileCategoryId) : 0;

		/** Verifica se a informação foi informada */
		if ($this->fileCategoryId == 0) {

			/** Adição de elemento */
			array_push($this->errors, 'Informe uma categoria');
		}
	}

	/** Método trata campo user_id_create */
	public function setUserIdCreate(int $userIdCreate): void
	{

		/** Trata a entrada da informação  */
		$this->userIdCreate = isset($userIdCreate) ? $this->Main->antiInjection($userIdCreate) : null;

		/** Verifica se a informação foi informada */
		if (empty($this->userIdCreate)) {

			/** Adição de elemento */
			array_push($this->errors, 'O campo "user_id_create", deve ser informado');
		}
	}

	/** Método trata campo user_id_update */
	public function setUserIdUpdate(int $userIdUpdate): void
	{

		/** Trata a entrada da informação  */
		$this->userIdUpdate = isset($userIdUpdate) ? $this->Main->antiInjection($userIdUpdate) : null;

		/** Verifica se a informação foi informada */
		if (empty($this->userIdUpdate)) {

			/** Adição de elemento */
			array_push($this->errors, 'O campo "user_id_update", deve ser informado');
		}
	}

	/** Método trata campo date_create */
	public function setDateCreate(string $dateCreate): void
	{

		/** Trata a entrada da informação  */
		$this->dateCreate = isset($dateCreate) ? $this->Main->antiInjection($dateCreate) : null;

		/** Verifica se a informação foi informada */
		if (empty($this->dateCreate)) {

			/** Adição de elemento */
			array_push($this->errors, 'O campo "date_create", deve ser informado');
		}
	}

	/** Método trata campo date_update */
	public function setDateUpdate(string $dateUpdate): void
	{

		/** Trata a entrada da informação  */
		$this->dateUpdate = isset($dateUpdate) ? $this->Main->antiInjection($dateUpdate) : null;

		/** Verifica se a informação foi informada */
		if (empty($this->dateUpdate)) {

			/** Adição de elemento */
			array_push($this->errors, 'O campo "date_update", deve ser informado');
		}
	}

	/** Método trata campo search */
	public function setSearch(?string $search): void
	{

		/** Trata a entrada da informação  */
		$this->search = isset($search) ? (string)$this->Main->antiInjection($search) : null;
	}

	/** Método trata campo description */
	public function setDescription(?string $description): void
	{

		/** Trata a entrada da informação  */
		$this->description = isset($description) ? (string)$this->Main->antiInjection($description) : null;

		/** Verifica se a informação foi informada */
		if (empty($this->description)) {

			/** Adição de elemento */
			array_push($this->errors, 'Informe uma descrição');
		}
	}

	/** Método trata campo status */
	public function setStatus(?string $status): void
	{

		/** Trata a entrada da informação  */
		$this->status = isset($status) ? (string)$this->Main->antiInjection($status) : null;

		/** Verifica se a informação foi informada */
		if (empty($this->status)) {

			/** Adição de elemento */
			array_push($this->errors, 'Informe uma situação');
		}
	}

	/** Método retorna campo batch_id */
	public function getBatchId(): ?int
	{

		/** Retorno da informação */
		return (int)$this->batchId;
	}

	/** Método retorna campo user_id */
	public function getUserId(): ?int
	{

		/** Retorno da informação */
		return (int)$this->userId;
	}

	/** Método retorna campo company_id */
	public function getCompanyId(): ?int
	{

		/** Retorno da informação */
		return (int)$this->companyId;
	}

	/** Método retorna campo file_category_id */
	public function getFileCategoryId(): ?int
	{

		/** Retorno da informação */
		return (int)$this->fileCategoryId;
	}

	/** Método retorna campo user_id_create */
	public function getUserIdCreate(): ?int
	{

		/** Retorno da informação */
		return (int)$this->userIdCreate;
	}

	/** Método retorna campo user_id_update */
	public function getUserIdUpdate(): ?int
	{

		/** Retorno da informação */
		return (int)$this->userIdUpdate;
	}

	/** Método retorna campo date_create */
	public function getDateCreate(): ?string
	{

		/** Retorno da informação */
		return (string)$this->dateCreate;
	}

	/** Método retorna campo date_update */
	public function getDateUpdate(): ?string
	{

		/** Retorno da informação */
		return (string)$this->dateUpdate;
	}

	/** Método retorna campo description */
	public function getDescription(): ?string
	{

		/** Retorno da informação */
		return (string)$this->description;
	}

	/** Método retorna campo search */
	public function getSearch(): ?string
	{

		/** Retorno da informação */
		return (string)$this->search;
	}

	/** Método retorna campo status */
	public function getStatus(): ?string
	{

		/** Retorno da informação */
		return (string)$this->status;
	}

	/** Retorna os erros */
	public function getErrors(): ?string
	{

		/** Verifico se deve informar os erros */
		if (count($this->errors)) {

			/** Verifica a quantidade de erros para informar a legenda */
			$this->info = count($this->errors) > 1 ? '<b>Os seguintes erros foram encontrados:</b><hr/>' : '<b>O seguinte erro foi encontrado:</b><hr/>';
			$i = 0;

			/** Lista os erros  */
			foreach ($this->errors as $keyError => $error) {

				/** Monto a mensagem de erro */
				$this->info .= ($i > 0 ? '<br/>' : '') . ($keyError + 1) . ' - ' . $error;
				$i++;
			}

			/** Retorno os erros encontrados */
			return (string)'<div class="alert alert-warning mt-2" role="alert">' . $this->info . '</div>';
		} else {

			return false;
		}
	}

	function __destruct() {}
}
