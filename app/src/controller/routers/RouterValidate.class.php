<?php

/** Defino o local da classes */
namespace src\controller\Routers;

/** Importação de classes */
use src\controller\main\Main;

class RouterValidate
{

    /** Parâmetros da classes */
    private ?object $Main = null;
    private ?object $RouterAuth = null;
    private ?array $errors = array();
    private ?string $info = null;

    private ?string $path = null;

    /** Método construtor */
    public function __construct()
    {

        /** Instânciamento de classes */
        $this->Main = new Main();
        $this->RouterAuth = new RouterAuth();

    }

    public function setPath(string $path): void
    {

        /** Tratamento da informação */
        $this->path = strtolower($this->Main->antiInjection($path));

        /** Validação da informação */
        if (empty($this->path)) {

            /** Adiciono um elemento a array */
            array_push($this->errors, 'O endereço da requisição deve ser informado');

        }

    }

    public function getPath(): string
    {

        /** Retorno da informação */
        return (string)$this->path;

    }

    public function getFullPath(): string
    {

        /** Retorno da informação */
        return './src/' . (string)$this->path; // . '.php'

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
