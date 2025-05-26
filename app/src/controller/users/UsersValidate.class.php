<?php

/**
 * Classe UsersValidade.class.php
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2022 - Souza Consultoria Tecnológica
 * @package      vendor
 * @subpackage   controller
 * @version      1.0
 * @date         06/05/2022
 */

/** Defino o local da classes */

namespace src\controller\users;

/** Importação de classes */

use src\controller\main\Main;

class UsersValidate
{

    /** Parâmetros da classes */
    private $Main = null;
    private $errors = array();
    private $info = null;

    /** Variaveis privadas */
    private ?int $userId = null;
    private ?int $companyId = null;
    private ?int $start = null;
    private ?int $page = null;
    private ?int $max = null;
    private ?string $search = null;
    private ?string $name = null;
    private ?string $email = null;
    private ?string $password = null;
    private ?string $position = null;
    private ?string $team = null;
    private ?string $status = null;
    private ?string $passwordTemp = null;
    private ?string $passwordInform = null;
    private ?string $passwordConfirm = null;
    private ?string $passwordTempConfirm = null;

    /** Método construtor */
    public function __construct()
    {

        /** Instânciamento de classes */
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

    /** Trata o campo search */
    public function setSearch(?string $search): void
    {
        /** Tratamento da informação */
        $this->search = isset($search) ? (string)$this->Main->antiInjection($search) : null;
    }

    /** Retorna o campo search */
    public function getSearch(): ?string
    {
        return (string)$this->search;
    }

    /** Trata o campo user_id */
    public function setUserId(int $userId): void
    {

        /** Tratamento da informação */
        $this->userId = (int)$this->Main->antiInjection($userId) > 0 ? $this->Main->antiInjection($userId) : 0;

        // /** Validação da informação */
        // if ($this->userId < 0) {

        //     /** Adição de elemento */
        //     array_push($this->errors, 'O campo "Usuário ID", deve ser válido');
        // }
    }

    /** Retorna o campo user_id */
    public function getUserId(): ?int
    {
        return (int)$this->userId;
    }

    /** Trata o campo company_id */
    public function setCompanyId(int $companyId): void
    {

        /** Tratamento da informação */
        $this->companyId = (int)$this->Main->antiInjection($companyId);

        /** Validação da informação */
        if ($this->companyId < 0) {

            /** Adição de elemento */
            array_push($this->errors, 'Informe a empresa');
        }
    }

    /** Retorna o campo company_id */
    public function getCompanyId(): ?int
    {
        return (int)$this->companyId;
    }

    /** Trata o campo nome */
    public function setName(string $name): void
    {

        /** Tratamento da informação */
        $this->name = (string)$this->Main->antiInjection($name);

        /** Validação da informação */
        if (empty($this->name)) {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Nome", deve ser válido');
        }
    }

    /** Retorna o campo nome */
    public function getName(): ?string
    {

        return (string)$this->name;
    }


    /** Trata o campo email */
    public function setEmail(string $email): void
    {

        /** Tratamento da informação */
        $this->email = (string)$this->Main->antiInjection($email);

        /** Validação da informação */
        if (empty($this->email)) {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Email", deve ser informado');
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {

            /** Adição de elemento */
            array_push($this->errors, 'Informe um e-mail válido');
        }
    }

    /** Retorna o campo email */
    public function getEmail(): ?string
    {

        return (string)$this->email;
    }

    /** Trata o campo password */
    public function setPassword(string $password): void
    {

        /** Tratamento da informação */
        $this->password = (string)$this->Main->antiInjection($password);

        /** Validação da informação */
        if (empty($this->password)) {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Senha", deve ser válido');
        }
    }

    /** Retorna o campo password */
    public function getPassword(): ?string
    {

        return (string)$this->password;
    }

    public function setPosition(string $position): void
    {

        /** Tratamento da informação */
        $this->position = (string)$this->Main->antiInjection($position);

        /** Validação da informação */
        if (empty($this->position)) {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Cargo", deve ser preenchido');
        }
    }

    /** Retorna o cargo do usuário */
    public function getPosition(): ?string
    {

        return (string)$this->position;
    }

    /** Valida o campo status */
    public function setStatus(string $status): void
    {

        /** Tratamento da informação */
        $this->status = (string)$this->Main->antiInjection($status);

        /** Validação da informação */
        if (empty($this->status)) {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Status", deve ser preenchido');
        }
    }

    /** Retorna o campo status */
    public function getStatus(): ?string
    {

        return (string)$this->status;
    }

    /** Valida o campo senha temporaria a confirmar */
    public function setPasswordTempConfirm(string $passwordTempConfirm): void
    {

        /** Tratamento da informação */
        $this->passwordTempConfirm = (string)$this->Main->antiInjection($passwordTempConfirm);

        // /** Validação da informação */
        // if (empty($this->passwordTempConfirm)) {

        //     /** Adição de elemento */
        //     array_push($this->errors, 'O campo "Status", deve ser preenchido');
        // }
    }

    /** Retorna a senha temporária a confirmar */
    public function getPasswordTempConfirm(): ?string
    {

        return (string)$this->passwordTempConfirm;
    }

    /** Valida o campo senha temporaria */
    public function setPasswordTemp(string $passwordTemp): void
    {

        /** Tratamento da informação */
        $this->passwordTemp = (string)$this->Main->antiInjection($passwordTemp);

        // /** Validação da informação */
        // if (empty($this->passwordTempConfirm)) {

        //     /** Adição de elemento */
        //     array_push($this->errors, 'O campo "Status", deve ser preenchido');
        // }
    }

    /** Retorna a senha temporária */
    public function getPasswordTemp(): ?string
    {

        return (string)$this->passwordTemp;
    }

    /** Retorna o time do usuário */
    public function setTeam(string $team): void
    {

        /** Tratamento da informação */
        $this->team = (string)$this->Main->antiInjection($team);

        /** Validação da informação */
        if (empty($this->team)) {

            /** Adição de elemento */
            array_push($this->errors, 'O campo "Time", deve ser preenchido');
        }
    }

    /** Retorna o time */
    public function getTeam(): ?string
    {

        return (string)$this->team;
    }


    /** Trata o campo password-inform */
    public function setPasswordInform(string $passwordInform): void
    {

        /** Tratamento da informação */
        $this->passwordInform = (string)$this->Main->antiInjection($passwordInform);

        /** Validação da informação */
        if (empty($this->passwordInform)) {

            /** Adição de elemento */
            array_push($this->errors, 'Informe a nova senha');
        } else {

            /**  Verifica o comprimento da senha */
            if (strlen($this->passwordInform) < 8 || strlen($this->passwordInform) > 10) {
                array_push($this->errors, "Deve ter entre 8 e 10 caracteres.");
            }

            /** Verifica se a senha possui letras e números */
            if (!preg_match('/[a-zA-Z]/', $this->passwordInform) || !preg_match('/\d/', $this->passwordInform)) {
                array_push($this->errors, "Deve conter pelo menos uma letra (A-Z ou a-z) e um número (0-9).");
            }

            /** Verifica se a senha possui pelo menos uma letra maiuscula */
            if (!preg_match('/[A-Z]/', $this->passwordInform)) {
                array_push($this->errors, "Deve conter pelo menos uma letra maiúscula (A-Z).");
            }

            /** Verifica se a senha possui pelo menos um caracter especial */
            if (!preg_match('/[@!#$]/', $this->passwordInform)) {
                array_push($this->errors, "Deve conter pelo menos um dos seguintes caracteres especiais: @, !, # ou $.");
            }
        }
    }

    /** Retorna o campo password-inform */
    public function getPasswordInform(): ?string
    {
        return (string)$this->passwordHash($this->passwordInform);
    }

    /** Trata o campo password-confirm */
    public function setPasswordConfirm(string $passwordConfirm): void
    {

        /** Tratamento da informação */
        $this->passwordConfirm = (string)$this->Main->antiInjection($passwordConfirm);

        /** Validação da informação */
        if (empty($this->passwordConfirm)) {

            /** Adição de elemento */
            array_push($this->errors, 'Informe novamente a nova senha');
        } else {

            /** Verifica se as senhas informadas não são identicas */
            if (!password_verify($this->passwordConfirm, ($this->getPasswordInform()))) {

                /** Adição de elemento */
                array_push($this->errors, 'As senhas não conferem');
            }
        }
    }

    /** Retorna o campo password-inform */
    public function getPasswordConfirm(): ?string
    {
        return (string)$this->passwordConfirm;
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

    /** Retorna os erros */
    public function getErrors(): ?string
    {

        /** Verifico se deve informar os erros */
        if (count($this->errors)) {

            /** Verifica a quantidade de erros para informar a legenda */
            $this->info = count($this->errors) > 1 ? '<b>Os seguintes erros foram encontrados:</b><hr/>' : '<b>O seguinte erro foi encontrado:</b><hr/>';

            /** Lista os erros  */
            foreach ($this->errors as $keyError => $error) {

                /** Monto a mensagem de erro */
                $this->info .= ($keyError + 1) . ' - ' . $error . '<br/>';
            }

            /** Retorno os erros encontrados */
            return (string)'<div class="alert alert-warning mt-2" role="alert">' . $this->info . '</div>';
        } else {

            return false;
        }
    }

    /** destrutor da classe */
    public function __destruct() {}
}
