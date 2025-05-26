<?php

/**
 * Classe Users.class.php
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2024 - Souza Consultoria Tecnológica
 * @package        model
 * @subpackage    model.class
 * @version        1.0
 */

/** Defino o local onde esta a classe */

namespace src\model;

class Files
{
    /** Declaro as vaiavéis da classe */
    private $connection = null;
    private $sql = null;
    private $stmt = null;
    private ?int $fileId = null;
    private ?int $companyId = null;
    private ?int $fileCategoryId = null;
    private ?string $tags = null;
    private ?int $userId = null;
    private ?int $start = null;
    private ?int $max = null;
    private ?string $limit = null;
    private ?string $search = null;
    private ?string $table = null;
    private ?string $name = null;
    private ?string $extension = null;
    private ?string $path = null;
    private ?int $folderId = null;
    private ?string $content = null;
    private ?array $fileIds = null;
    private ?string $placeholders = null;
    private ?string $data = null;
    private ?int $batchId = null;

    /** Construtor da classe */
    public function __construct()
    {
        /** Cria o objeto de conexão com o banco de dados */
        $this->connection = new Mysql();
    }

    /** Retorna o ID do arquivo */
    public function GenerateId(
        ?int $fileId,
        ?int $companyId
    ) {

        /** Parametros de entrada */
        $this->fileId = $fileId;
        $this->companyId = $companyId;

        /** Consulta SQL */
        $this->sql = 'INSERT INTO files (`file_id`, 
                                         `company_id`) VALUES 
                                        (:file_id, 
                                         :company_id);';

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':file_id', $this->fileId);
        $this->stmt->bindParam(':company_id', $this->companyId);

        /** Executo o SQL */
        $this->stmt->execute();

        /** Retorno o ultimo id gerado */
        return $this->connection->connect()->lastInsertId();
    }

    /** Salva o arquivo junto ao banco de dados */
    public function Save(
        ?int $fileId,
        ?int $companyId,
        ?int $batchId,
        ?string $table,
        ?string $name,
        ?string $extension,
        ?string $path
    ) {

        /** Parametros de entrada */
        $this->fileId = $fileId;
        $this->companyId = $companyId;
        $this->batchId = $batchId;
        $this->table = $table;
        $this->name = $name;
        $this->extension = $extension;
        $this->path = $path;

        /** Consulta SQL */
        $this->sql = 'INSERT INTO files (`file_id`, 
                                         `company_id`, 
                                         `batch_id`,
                                         `table`, 
                                         `name`, 
                                         `extension`, 
                                         `path`) VALUES 
                                        (:fileId, 
                                        :company_id, 
                                        :batch_id,
                                        :table, 
                                        :name, 
                                        :extension, 
                                        :path)
                      ON DUPLICATE KEY UPDATE `company_id` = :company_id, 
                                              `batch_id` = :batch_id,
                                              `table` = :table, 
                                              `name` = :name, 
                                              `extension` = :extension, 
                                              `path` = :path;';

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':fileId', $this->fileId);
        $this->stmt->bindParam(':company_id', $this->companyId);
        $this->stmt->bindParam(':batch_id', $this->batchId);
        $this->stmt->bindParam(':table', $this->table);
        $this->stmt->bindParam(':name', $this->name);
        $this->stmt->bindParam(':extension', $this->extension);
        $this->stmt->bindParam(':path', $this->path);

        /** Executo o SQL */
        return $this->stmt->execute();
    }

    /** Salva o nome e descrição do arquivo */
    public function SaveNameAndDescription(
        ?int $fileId,
        ?string $name,
        ?string $description
    ) {
        /** Consulta SQL */
        $this->sql = 'UPDATE files f SET f.name = :name, 
                                         f.description = :description
                      WHERE f.file_id = :fileId';

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':fileId', $fileId);
        $this->stmt->bindParam(':name', $name);
        $this->stmt->bindParam(':description', $description);

        /** Executo o SQL */
        return $this->stmt->execute();
    }

    /** Salva as tags do arquivo */
    public function SaveTags(
        ?int $fileId,
        ?int $fileCategoryId,
        ?string $tags,
        ?int $userId
    ) {
        /** Parametros de entrada */
        $this->fileId = $fileId;
        $this->fileCategoryId = $fileCategoryId;
        $this->tags = $tags;
        $this->userId = $userId;

        /** Consulta SQL */
        $this->sql = 'UPDATE files f SET f.tags = :tags,
                                         f.file_category_id = :file_category_id, 
                                         f.user_id_update = :user_id_update,
                                         f.date_update = NOW()
                      WHERE f.file_id = :file_id';

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':file_id', $this->fileId);
        $this->stmt->bindParam(':file_category_id', $this->fileCategoryId);
        $this->stmt->bindParam(':user_id_update', $this->userId);
        $this->stmt->bindParam(':tags', $this->tags);

        /** Executo o SQL */
        return $this->stmt->execute();
    }

    /** Salva a pasta do arquivo */
    public function SaveFolder(
        ?int $fileId,
        ?int $folderId
    ) {

        /** Parametros de entrada */
        $this->fileId = $fileId;
        $this->folderId = $folderId;

        /** Consulta SQL */
        $this->sql = 'UPDATE files f SET f.folder_id = :folderId 
                      WHERE f.file_id = :fileId';

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':fileId', $this->fileId);
        $this->stmt->bindParam(':folderId', $this->folderId);

        /** Executo o SQL */
        return $this->stmt->execute();
    }

    /** Salva o conteúdo */
    public function SaveContent(
        ?int $fileId,
        ?string $content
    ) {

        /** Parametros de entrada */
        $this->fileId = $fileId;
        $this->content = $content;

        /** Consulta SQL */
        $this->sql = 'UPDATE files f SET f.content = :content 
                      WHERE f.file_id = :fileId';

        /** Preparo o sql para receber os valores */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':fileId', $this->fileId);
        $this->stmt->bindParam(':content', $this->content);

        /** Executo o SQL */
        return $this->stmt->execute();
    }

    /** Retorna os registros com paginação */
    public function All(
        ?int $start,
        ?int $max,
        ?string $search
    ): array {

        /** Parametros de entrada */
        $this->start = $start;
        $this->max = $max;
        $this->search = !empty($search) ? '%' . $search . '%' : null;

        /** Verifico se há paginação */
        if ($this->max) {
            $this->limit = " limit $this->start, $this->max";
        }

        /** Consulta SQL. */
        $this->sql = 'select f.file_id,
                             f.company_id,
                             f.folder_id,
                             f.file_category_id,
                             f.user_id,
                             f.batch_id,
                             f.table,
                             f.name,
                             f.description,
                             f.extension,
                             f.path,
                             f.content,
                             f.tags,
                             f.date_create,
                             f.date_update,
                             f.user_id_create,
                             f.user_id_update,
                             f.status,
                             b.description as batch 
                      from files f 
                      left join batchs b on f.batch_id = b.batch_id
                      where f.status <> \'D\'';

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            $this->sql .= ' and name like(:name)';
        }

        /** Informa a limitação de registros */
        $this->sql .= $this->limit;

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

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

    /** Retorna os placeholders */
    public function AllInFileId(
        array $fileIds
    ): array {

        /** Parametros de entrada */
        $this->fileIds = $fileIds;

        /** Cria a quantidade correta de placeholders '?' com base no número de elementos em $fileIds */
        $this->placeholders = implode(',', array_fill(0, count($this->fileIds), '?'));

        /** Monta a consulta SQL para recuperar os arquivos com base nos IDs fornecidos */
        $this->sql = "SELECT * FROM files f WHERE f.file_id IN ({$this->placeholders})";

        /** Prepara a consulta SQL utilizando a conexão estabelecida */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Executa a consulta SQL com o array de IDs sendo passados como parâmetros */
        $this->stmt->execute($this->fileIds);

        /** Retorna o resultado da consulta como um array de objetos */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /** Retorna uma pasta especifica */
    public function AllByFolderId(
        int $folderId
    ): array {

        /** Parametros de entrada */
        $this->folderId = $folderId;

        /** Monta a consulta SQL para recuperar 
         * os chamados abertos da empresa. */
        $this->sql = 'select * from files f 
                      where f.folder_id = :folderId ';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':folderId', $this->folderId);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /** Retorna a quantidade de arquivos */
    public function Count(
        ?string $search
    ): int {

        /** Parametros de entrada */
        $this->search = !empty($search) ? '%' . $search . '%' : null;

        /** Consulta SQL. */
        $this->sql = 'select COUNT(f.file_id) as qtde 
                      from files f
                      where f.status <> \'D\'';

        /** Verifica se a consulta foi informada */
        if (!empty($this->search)) {

            $this->sql .= ' and name like(:name)';
        }

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

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
    public function Get(
        int $fileId
    ): object|bool {

        /** Parametros de entrada */
        $this->fileId = $fileId;

        /** Consulta SQL. */
        $this->sql = 'select f.file_id,
                             f.company_id,
                             f.folder_id,
                             f.file_category_id,
                             f.user_id,
                             f.batch_id,
                             f.table,
                             f.name,
                             f.description,
                             f.extension,
                             f.path,
                             f.content,
                             f.tags,
                             f.date_create,
                             f.date_update,
                             f.user_id_create,
                             f.user_id_update,
                             f.status,
                             b.file_category_id as category_id
                      from files f
                      left join batchs b on f.batch_id = b.batch_id
                      where file_id = :file_id';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':file_id', $this->fileId);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchObject();
    }

    /** Consulta um arquivo */
    public function Search(
        string $search
    ): array {

        /** Parametros de entrada */
        $this->search = '%' . $search . '%';

        /** Consulta SQL. */
        $this->sql = 'SELECT * FROM files f
                      WHERE f.name like :name;';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':name', $this->search, \PDO::PARAM_STR);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /** Consulta o conteúdo de uma arquivo */
    public function SearchByContent(
        string $data
    ): array {

        /** Parametros de entrada */
        $this->data = '%' . $data . '%';

        /** Consulta SQL. */
        $this->sql = 'SELECT * FROM files f
                      WHERE f.content like :data;';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':data', $this->data, \PDO::PARAM_STR);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /** Consutla uma tag especifica de um arquivo */
    public function SearchBytag(
        string $data,
        string $search
    ): array {

        /** Parametros de entrada */
        $this->data = $data;
        $this->search = $search;

        /** Consulta SQL. */
        $this->sql = "SELECT * FROM files f
                      WHERE f.tags->'$.{$data}' 
                      like '%{$this->search}%';";

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Executa a consulta SQL. */
        $this->stmt->execute();

        /** Retorna o resultado da consulta como um array de objetos. */
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }


    /** Muda o status de um arquivo para delete */
    public function Delete(
        int $fileId,
        int $userId
    ): object|bool {

        /** Parametros de entrada */
        $this->fileId = $fileId;
        $this->userId = $userId;

        /** Consulta SQL. */
        $this->sql = 'update files set status = \'D\',
                                       user_id_update = :user_id_update,
                                       date_update = NOW()
                      where file_id = :file_id';

        /** Prepara a consulta SQL utilizando a conexão estabelecida. */
        $this->stmt = $this->connection->connect()->prepare($this->sql);

        /** Preencho os parâmetros do SQL */
        $this->stmt->bindParam(':file_id', $fileId);
        $this->stmt->bindParam(':user_id_update', $userId);

        /** Executa a consulta SQL. */
        return $this->stmt->execute();
    }


    /** Fecha uma conexão aberta 
     * anteriormente com o banco de dados */
    public function __destruct()
    {
        $this->connection = null;
    }
}
