<?php

/** Defino o local da classes */
namespace src\controller\logs;

/** Importação de classes */
use src\controller\main\Main;

class LogsValidate
{

    /** Parâmetros da classes */
    private $Main = null;
    private $errors = array();
    private $info = null;

    private $logId;
    private $logTypeId;
    private $companyId;
    private $userId;
    private $parentId;
    private $registerId;
    private $request;
    private $data;
    private $dateRegister;
    private $status;

    /** Método construtor */
    public function __construct()
    {

        /** Instânciamento de classes */
        $this->Main = new Main();

    }

    public function setLogId(int $logId): void
    {

        /** Tratamento da informação */
        $this->logId = isset($logId) ? $this->Main->antiInjection($logId) : 0;

    }

    public function getLogId(): int
    {

        /** Retorno da informação */
        return (int)$this->logId;

    }

    public function setLogTypeId(int $logTypeId): void
    {

        /** Tratamento da informação */
        $this->logTypeId = isset($logTypeId) ? $this->Main->antiInjection($logTypeId) : 0;

    }

    public function getLogTypeId(): int
    {

        /** Retorno da informação */
        return (int)$this->logTypeId;

    }

    
    public function setCompanyId(int $companyId): void
    {

        /** Tratamento da informação */
        $this->companyId = isset($companyId) ? $this->Main->antiInjection($companyId) : 0;

    }

    public function getCompanyId(): int
    {

        /** Retorno da informação */
        return (int)$this->companyId;

    }

    public function setUserId(int $userId): void
    {

        /** Tratamento da informação */
        $this->userId = isset($userId) ? $this->Main->antiInjection($userId) : 0;

    }

    public function getUserId(): int
    {

        /** Retorno da informação */
        return (int)$this->userId;

    }

    public function setParentId(int $parentId): void
    {

        /** Tratamento da informação */
        $this->parentId = isset($parentId) ? $this->Main->antiInjection($parentId) : 0;

    }

    public function getParentId(): int
    {

        /** Retorno da informação */
        return (int)$this->parentId;

    }

    public function setRegisterId(int $registerId): void
    {

        /** Tratamento da informação */
        $this->registerId = isset($registerId) ? $this->Main->antiInjection($registerId) : 0;

    }

    public function getRegisterId(): int
    {

        /** Retorno da informação */
        return (int)$this->registerId;

    }

    public function setRequest(string $request): void
    {

        /** Tratamento da informação */
        $this->request = isset($request) ? $this->Main->antiInjection($request) : null;

    }

    public function getrequest(): string
    {

        /** Retorno da informação */
        return (string)$this->request;

    }

    public function setData(string $data): void
    {

        /** Tratamento da informação */
        $this->data = isset($data) ? $this->Main->antiInjection($data, 'S') : null;

    }

    public function getData(): string
    {

        /** Retorno da informação */
        return (string)$this->data;

    }

    public function setDateRegister(string $dateRegister): void
    {

        /** Tratamento da informação */
        $this->dateRegister = isset($dateRegister) ? $this->Main->antiInjection($dateRegister) : null;

    }

    public function getDateRegister(): string
    {

        /** Retorno da informação */
        return (string)$this->dateRegister;

    }

    public function checkException($path)
    {

        $result = [
            'action/files/files_save_chunk.php',
        ];

        /** Verifico se existeo valor na array */
        if (in_array($path, $result)) {

            /** Informo que a sessão esta ativa */
            $this->status = false;

        } else {

            /** Informo que a sessão esta ativa */
            $this->status = true;

        }

        /** Retorno da informação */
        return $this->status;

    }

    public function getErrors(): ?string
    {

        /** Verifico se deve informar os erros */
        if (count($this->errors)) {

            /** Verifica a quantidade de erros para informar a legenda */
            $this->info = count($this->errors) > 1 ? '<center>Os seguintes erros foram encontrados</center>' : '<center>O seguinte erro foi encontrado</center>';

            /** Lista os erros  */
            foreach ($this->errors as $keyError => $error) {

                /** Monto a mensagem de erro */
                $this->info .= '</br>' . ($keyError + 1) . ' - ' . $error;

            }

            /** Retorno os erros encontrados */
            return (string)$this->info;

        } else {

            return false;

        }

    }

}
