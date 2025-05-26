<?php

/** Defino o local da classes */

namespace src\controller\api;

/** Importação de classes */

use src\controller\main\Main;

class ApiHandling
{

    /** Parâmetros da classes */
    private $Main = null;
    private ?string $token = null;
    private ?string $base64 = null;
    private ?object $config = null;
    private ?string $error = null;
    private ?object $result = null;
    private ?string $response = null;
    private ?array $data = null;
    private ?string $data_json = null;
    private ?object $ch = null;
    private ?string $url = null;
    private ?string $http_code = null;

    /** Método construtor */
    public function __construct()
    {

        /** Instânciamento de classes */
        $this->Main = new Main();

        // Obtenho as configurações a api
        $this->config = $this->Main->GetConfig()->api->docverse;
    }

    /** Retorna o token de acesso */
    public function GetToken(): null|string
    {

        // Valida a URL
        if (filter_var($this->config->url . 'token', FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException('URL inválida para a API.', 0);
        }

        // Inicialize a sessão cURL
        $this->ch = curl_init($this->config->url . 'token');

        // Defina as opções do cURL
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($this->config->access));

        // Execute a requisição cURL
        $this->response = curl_exec($this->ch);

        //Pega o status da solicitação
        $this->http_code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        // Se o código HTTP estiver entre 200 e 399, a API está online
        if ($this->http_code >= 200 && $this->http_code < 400) {

            // Verifique se ocorreu algum erro durante a execução
            if ($this->response === false) {

                /** Captura possiveis erros */
                $this->error = curl_error($this->ch);

                /** Feche a sessão 
                 * cURL antes de retornar */
                curl_close($this->ch);

                /** Retorna o erro na requisição */
                throw new \RuntimeException("Erro na requisição cURL: $this->error");
            }

            // Feche a sessão cURL
            curl_close($this->ch);

            /** Carrega o json retornado */
            $this->result = (object) json_decode($this->response);

            // Verifique se o JSON é válido
            if ($this->result === null) {
                throw new \RuntimeException('Resposta inválida da API. JSON malformado.');
            }

            // Verifica o status da solicitação
            if ($this->result->status === 'error') {
                throw new \InvalidArgumentException($this->result->message, 0);
            }

            // Retorna o token
            return $this->result->token ?? null;
        } else {

            /** Captura possiveis erros */
            $this->error = curl_error($this->ch);

            /** Feche a sessão 
             * cURL antes de retornar */
            curl_close($this->ch);

            /** Retorna o erro na requisição */
            throw new \RuntimeException($this->error);
        }
    }

    /** Converte imagem para word */
    public function ImageToWord(string $token, string $base64): null|string
    {

        /** Parametros de entrada */
        $this->token = $token;
        $this->base64 = $base64;

        // Valida a URL
        if (filter_var($this->config->url . 'image_to_word', FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException('URL inválida para a API.', 0);
        }

        // Dados que serão enviados para o serviço Python
        $this->data = [

            'base64_string' => $this->base64,
            'token' => $this->token

        ];

        // Codifique os dados para JSON
        $this->data_json = json_encode($this->data);

        // Inicialize a sessão cURL
        $this->ch = curl_init($this->config->url . 'image_to_word');

        // Defina as opções do cURL
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->data_json);

        // Execute a requisição cURL
        $this->response = curl_exec($this->ch);

        //Pega o status da solicitação
        $this->http_code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        // Se o código HTTP estiver entre 200 e 399, a API está online
        if ($this->http_code >= 200 && $this->http_code < 400) {

            // Verifique se ocorreu algum erro durante a execução
            if ($this->response === false) {

                /** Captura possiveis erros */
                $this->error = curl_error($this->ch);

                /** Feche a sessão 
                 * cURL antes de retornar */
                curl_close($this->ch);

                /** Retorna o erro na requisição */
                throw new \RuntimeException("Erro na requisição cURL: $this->error");
            }

            // Feche a sessão cURL
            curl_close($this->ch);

            /** Carrega o json retornado */
            $this->result = (object) json_decode($this->response);

            // Verifique se o JSON é válido
            if ($this->result === null) {
                throw new \RuntimeException('Resposta inválida da API. JSON malformado.');
            }

            // Verifica o status da solicitação
            if ($this->result->status === 'error') {
                throw new \InvalidArgumentException($this->result->message, 0);
            }

            // Retorna o base64 com o conteúdo convertido
            return $this->result->base64_file ?? null;
        } else {

            /** Captura possiveis erros */
            $this->error = curl_error($this->ch);

            /** Feche a sessão 
             * cURL antes de retornar */
            curl_close($this->ch);

            /** Retorna o erro na requisição */
            throw new \RuntimeException($this->error);
        }
    }

    /** Converte imagem para texto */
    public function ImageToText(string $token, string $base64): null|string
    {

        /** Parametros de entrada */
        $this->token = $token;
        $this->base64 = $base64;

        // Valida a URL
        if (filter_var($this->config->url . 'image_to_text', FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException('URL inválida para a API.', 0);
        }

        // Dados que serão enviados para o serviço Python
        $this->data = [

            'base64_string' => $this->base64,
            'token' => $this->token

        ];

        // Codifique os dados para JSON
        $this->data_json = json_encode($this->data);

        // Inicialize a sessão cURL
        $this->ch = curl_init($this->config->url . 'image_to_text');

        // Defina as opções do cURL
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->data_json);

        // Execute a requisição cURL
        $this->response = curl_exec($this->ch);

        //Pega o status da solicitação
        $this->http_code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        // Se o código HTTP estiver entre 200 e 399, a API está online
        if ($this->http_code >= 200 && $this->http_code < 400) {

            // Verifique se ocorreu algum erro durante a execução
            if ($this->response === false) {

                /** Captura possiveis erros */
                $this->error = curl_error($this->ch);

                /** Feche a sessão 
                 * cURL antes de retornar */
                curl_close($this->ch);

                /** Retorna o erro na requisição */
                throw new \RuntimeException("Erro na requisição cURL: $this->error");
            }

            // Feche a sessão cURL
            curl_close($this->ch);

            /** Carrega o json retornado */
            $this->result = (object) json_decode($this->response);

            // Verifique se o JSON é válido
            if ($this->result === null) {
                throw new \RuntimeException('Resposta inválida da API. JSON malformado.');
            }

            // Verifica o status da solicitação
            if ($this->result->status === 'error') {
                throw new \InvalidArgumentException($this->result->message, 0);
            }

            // Retorna o base64 com o conteúdo convertido
            return $this->result->base64_file ?? null;
        } else {

            /** Captura possiveis erros */
            $this->error = curl_error($this->ch);

            /** Feche a sessão 
             * cURL antes de retornar */
            curl_close($this->ch);

            /** Retorna o erro na requisição */
            throw new \RuntimeException($this->error);
        }
    }

    /** Converte PDF para texto */
    public function PdfToText(string $token, string $base64): null|string
    {

        /** Parametros de entrada */
        $this->token = $token;
        $this->base64 = $base64;

        // Valida a URL
        if (filter_var($this->config->url . 'pdf_to_text', FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException('URL inválida para a API.', 0);
        }

        // Dados que serão enviados para o serviço Python
        $this->data = [

            'base64_string' => $this->base64,
            'token' => $this->token

        ];

        // Codifique os dados para JSON
        $this->data_json = json_encode($this->data);

        // Inicialize a sessão cURL
        $this->ch = curl_init($this->config->url . 'pdf_to_text');

        // Defina as opções do cURL
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->data_json);

        // Execute a requisição cURL
        $this->response = curl_exec($this->ch);

        //Pega o status da solicitação
        $this->http_code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        // Se o código HTTP estiver entre 200 e 399, a API está online
        if ($this->http_code >= 200 && $this->http_code < 400) {

            // Verifique se ocorreu algum erro durante a execução
            if ($this->response === false) {

                /** Captura possiveis erros */
                $this->error = curl_error($this->ch);

                /** Feche a sessão 
                 * cURL antes de retornar */
                curl_close($this->ch);

                /** Retorna o erro na requisição */
                throw new \RuntimeException("Erro na requisição cURL: $this->error");
            }

            // Feche a sessão cURL
            curl_close($this->ch);

            /** Carrega o json retornado */
            $this->result = (object) json_decode($this->response);

            // Verifique se o JSON é válido
            if ($this->result === null) {
                throw new \RuntimeException('Resposta inválida da API. JSON malformado.');
            }

            // Verifica o status da solicitação
            if ($this->result->status === 'error') {
                throw new \InvalidArgumentException($this->result->message, 0);
            }

            // Retorna o base64 com o conteúdo convertido
            return $this->result->base64_file ?? null;
        } else {

            /** Captura possiveis erros */
            $this->error = curl_error($this->ch);

            /** Feche a sessão 
             * cURL antes de retornar */
            curl_close($this->ch);

            /** Retorna o erro na requisição */
            throw new \RuntimeException($this->error);
        }
    }

    /** Converte PDF para Word */
    public function PdfToWord(string $token, string $base64): null|string
    {

        /** Parametros de entrada */
        $this->token = $token;
        $this->base64 = $base64;

        // Valida a URL
        if (filter_var($this->config->url . 'pdf_to_word', FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException('URL inválida para a API.', 0);
        }

        // Dados que serão enviados para o serviço Python
        $this->data = [

            'base64_string' => $this->base64,
            'token' => $this->token

        ];

        // Codifique os dados para JSON
        $this->data_json = json_encode($this->data);

        // Inicialize a sessão cURL
        $this->ch = curl_init($this->config->url . 'pdf_to_word');

        // Defina as opções do cURL
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->data_json);

        // Execute a requisição cURL
        $this->response = curl_exec($this->ch);

        //Pega o status da solicitação
        $this->http_code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

        // Se o código HTTP estiver entre 200 e 399, a API está online
        if ($this->http_code >= 200 && $this->http_code < 400) {

            // Verifique se ocorreu algum erro durante a execução
            if ($this->response === false) {

                /** Captura possiveis erros */
                $this->error = curl_error($this->ch);

                /** Feche a sessão 
                 * cURL antes de retornar */
                curl_close($this->ch);

                /** Retorna o erro na requisição */
                throw new \RuntimeException("Erro na requisição cURL: $this->error");
            }

            // Feche a sessão cURL
            curl_close($this->ch);

            /** Carrega o json retornado */
            $this->result = (object) json_decode($this->response);

            // Verifique se o JSON é válido
            if ($this->result === null) {
                throw new \RuntimeException('Resposta inválida da API. JSON malformado.');
            }

            // Verifica o status da solicitação
            if ($this->result->status === 'error') {
                throw new \InvalidArgumentException($this->result->message, 0);
            }

            // Retorna o base64 com o conteúdo convertido
            return $this->result->base64_file ?? null;
        } else {

            /** Captura possiveis erros */
            $this->error = curl_error($this->ch);

            /** Feche a sessão 
             * cURL antes de retornar */
            curl_close($this->ch);

            /** Retorna o erro na requisição */
            throw new \RuntimeException($this->error);
        }
    }
}
