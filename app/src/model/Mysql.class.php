<?php

namespace src\model;

use src\model\Host;
use PDO;
use PDOException;

/**
 * Classe Mysql
 * Responsável por instanciar uma conexão PDO com o banco de dados MySQL
 * utilizando os dados da classe Host.
 */
class Mysql extends PDO
{
    /**
     * Construtor da classe Mysql.
     * Ao ser instanciada, já cria uma conexão válida com o banco.
     */
    public function __construct()
    {
        // Instancia a classe Host para obter as configurações do banco
        $host = new Host();

        try {
            // Chama o construtor da classe PDO (superclasse) com as credenciais
            parent::__construct(
                $host->getDsn(),
                $host->getUser(),
                $host->getPassword()
            );

            // Define o modo de erro para exceção (obrigatório para tratamento via try/catch)
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            // Registra o erro no log (para segurança, não exibe diretamente)
            error_log('Erro ao conectar com o banco de dados: ' . $e->getMessage());

            // Relança a exceção para tratamento no código que chama
            throw $e;
        }
    }
}
