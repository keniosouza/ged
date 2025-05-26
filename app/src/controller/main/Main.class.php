<?php

/**
 * Classe Main.class.php
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2016 Serenity Informatica
 * @package      model
 * @subpackage    model.class
 * @version       1.0
 * @updated       06/03/2025 
 */

/** Defino o local onde a classe esta localizada **/

namespace src\controller\main;

class Main
{

    private ?string $string = null;
    private ?string $long = null;
    private ?string $method = null;
    private ?string $firstKey = null;
    private ?string $secondKey = null;
    private ?string $hash = null;
    private ?object $config = null;
    private ?string $sessionTime = null;
    private ?string $data = null;
    private ?string $pathConfig = null;
    private ?string $dataValida = null;
    private ?string $ano = null;
    private ?string $mesNumero = null;
    private ?array $meses = null;
    private ?string $mes = null;
    private ?string $urlApplication = null;
    private ?int $numberRecords = null;
    private ?int $start = null;
    private ?int $max = null;
    private ?int $page = null;
    private ?string $queryString = null;
    private ?string $message = null;
    private ?string $form = null;
    private ?int $paginationColumns = null;
    private ?int $pagination = null;
    private ?string $nav = null;
    private ?int $rows = null;
    private ?array $jsonData = null;
    private ?string $csrfToken = null;
    private ?string $host = null;
    private ?string $username = null;
    private ?string $password = null;
    private ?int $port = null;
    private ?string $fromEmail = null;
    private ?string $fromName = null;
    private ?string $messageNewPassword = null;
    private ?string $environment = null;

    function __construct(?string $pathConfig = null)
    {

        /** Verifica se esta informando o caminho do arquivo de configuração */
        $this->pathConfig = !empty($pathConfig) ? './' . $pathConfig . '/config/config.json' : './config/config.json';

        /** Carrega as configurações gerais */
        $this->config = $this->GetConfig();

        /** Parametros para descriptografar dados */
        $this->method          = $this->config->{'security'}->{'method'};
        $this->firstKey        = $this->config->{'security'}->{'first_key'};
        $this->secondKey       = $this->config->{'security'}->{'second_key'};
        $this->hash            = $this->config->{'security'}->{'hash'};
        $this->urlApplication  = $this->config->{'url_application'};

        /** Parametro do tempo de sessão do usuário */
        $this->sessionTime = $this->config->{'security'}->{'session_time'};

        /** Paginação de registros */
        $this->rows       =  $this->config->{'tables'}->{'rows'};
        $this->pagination =  $this->config->{'tables'}->{'pagination_columns'};

        /** Retorna os parametros de envio de e-mail */
        $this->host               = $this->config->{'mail'}->{'host'};
        $this->username           = $this->config->{'mail'}->{'username'};
        $this->password           = $this->config->{'mail'}->{'password'};
        $this->port               = $this->config->{'mail'}->{'port'};
        $this->fromEmail          = $this->config->{'mail'}->{'from'}->{'email'};
        $this->fromName           = $this->config->{'mail'}->{'from'}->{'name'};
        $this->messageNewPassword = $this->config->{'mail'}->{'messages'}->{'new_password'};

        /** Retorna o ambiente da aplicação */
        $this->environment = $this->config->{'environment'};
    }

    /** Retorna a quantidade de 
     * linhas para a table */
    public function getRows(): int
    {
        return (int)$this->rows;
    }

    /** Retorna a quantidade de colunas de 
     * paginação para a table */
    public function getPagination(): int
    {
        return (int)$this->pagination;
    }

    /** Retorna o metodo de criptografia */
    private function getMethod(): string
    {

        return $this->method;
    }

    /** Retorna a primeira chave de criptografia */
    private function getFirstKey(): string
    {

        return $this->firstKey;
    }

    //** Retorna a segunda chave de criptografia */
    private function getSecondKey(): string
    {

        return $this->secondKey;
    }

    /** Retorna o tempo de sessão */
    public function getSessionTime(): int
    {

        return (int)$this->sessionTime;
    }

    /** Retorna a url da aplicaçãoo */
    public function getUrlApp(): string
    {

        return (string)$this->urlApplication;
    }

    /** Retorna o ambiente da aplicação */
    public function getEnvironment(): string
    {

        return (string)$this->environment;
    }

    /** Retorna o caminho do arquivo de configuração */
    public function getPathConfig(): string
    {
        return (string)$this->pathConfig;
    }

    /** Retorna o host do e-mail */
    public function getHost(): string
    {

        return (string)$this->host;
    }

    /** Retorna o usuário do e-mail */
    public function getUsername(): string
    {

        return (string)$this->username;
    }

    /** Retorna a senha do e-mail */
    public function getPassword(): string
    {

        return (string)$this->password;
    }

    /** Retorna a porta do e-mail */
    public function getPort(): int
    {

        return (int)$this->port;
    }

    /** Retorna a e-mail de envio */
    public function getFromEmail(): string
    {

        return (string)$this->fromEmail;
    }

    /** Retorna o nome do envio do e-mail */
    public function getFromName(): string
    {

        return (string)$this->fromName;
    }

    /** Retorna a mensagem de renovação de senha */
    public function getMessageNewPassowrd(): string
    {

        return (string)base64_decode($this->messageNewPassword);
    }

    public function GetConfig(): object
    {

        /** Verifica se o arquivo de configuração existe */
        if (is_file($this->getPathConfig())) {

            return (object)json_decode(file_get_contents($this->getPathConfig()));
        } else {

            /** Mensagem de erro */
            throw new \Exception('<div class="alert alert-warning" role="alert">Não foi possível carregar o arquivo de configuração.</div>');
        }
    }

    /** Inicializa a sessão */
    public function startSession(): void
    {
        /** Inicializo a sessão */
        session_start();
    }

    /** Finaliza a sessão */
    public function endSession(): void
    {
        /** Inicializo a sessão */
        session_destroy();
    }

    /** Verifica se a sessão do usuário foi criada */
    public function checkSession(): void
    {

        /** Verifica se a sessão do usuário existe */
        if (!isset($_SESSION['MY_SAAS_USER'])) {

            /** Destroi possiveis sessões */
            $this->endSession();

            /** Redireciona para a tela de login */
            header('Location: ../');
            throw new \Exception('Sessão não encontrada. Redirecionando para a tela de login.');
        }
    }

    /** Verifica se a sessão do usuário foi criada */
    public function checkSessionLogin(): void
    {

        /** Verifica se a sessão do usuário existe */
        if (isset($_SESSION['MY_SAAS_USER'])) {

            /** Redireciona para a tela de login */
            header('Location: app/');
        }
    }

    /** Antiinjection */
    public function antiInjectionArray($ar): array
    {

        /** Verifica se a array foi informada */
        if (is_array($ar)) {

            $str = [];

            foreach ($ar as $key => $value) {

                if (!empty($value)) {

                    if (is_string($value)) {

                        $str[$key] = $this->antiInjection(html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
                    } else {

                        $str[$key] = $this->antiInjection($value);
                    }
                }
            }
            return $str;
        } else {

            return $ar;
        }
    }

    /** Antiinjection */
    public function antiInjection($string, string $long = ''): string
    {

        /** Parâmetros de entrada */
        $this->string = $string;
        $this->long = $long;

        /** Verifico o tipo de entrada */
        if (is_array($this->string)) {

            /** Retorno o texto sem formatação */
            $this->antiInjectionArray($this->string);
        } elseif (strcmp($this->long, 'S') === 0) {

            /** Retorno a string sem tratamento */
            return $this->string;
        } else {

            /** Remoção de espaçamentos */
            $this->string = trim($this->string);

            /** Remoção de tags PHP e HTML */
            $this->string = strip_tags($this->string);

            /** Adição de barras invertidas */
            $this->string = addslashes($this->string);

            /** Evita ataque XSS */
            $this->string = htmlspecialchars($this->string);

            /** Elementos do SQL Injection */
            $elements = array(
                ' drop ',
                ' select ',
                ' delete ',
                ' update ',
                ' insert ',
                ' alert ',
                ' destroy ',
                ' * ',
                ' database ',
                ' drop ',
                ' union ',
                ' TABLE_NAME ',
                ' 1=1 ',
                ' or 1 ',
                ' exec ',
                ' INFORMATION_SCHEMA ',
                ' like ',
                ' COLUMNS ',
                ' into ',
                ' VALUES ',
                ' from ',
                ' undefined '
            );

            /** Transformo as palavras em array */
            $palavras = explode(' ', str_replace(',', '', $this->string));

            /** Percorro todas as palavras localizadas */
            foreach ($palavras as $keyPalavra => $palavra) {

                /** Percorro todos os elementos do SQL Injection */
                foreach ($elements as $keyElement => $element) {

                    /** Verifico se a palavra esta na lista negra */
                    if (strcmp(strtolower($palavra), strtolower($element)) === 0) {

                        /** Realizo a troca da marcação pela palavra qualificada */
                        $this->string = str_replace($palavra, '', $this->string);
                    }
                }
            }

            /** Retorno o texto tratado */
            return $this->string;
        }
    }

    public function RandomHash()
    {

        return md5(rand(1, 1000) . date('H:i:s'));
    }

    public function GetExtensionIcon($name)
    {

        return './assets/img/default/files/' . $name . '.png';
    }

    public function getFileExtension($filename)
    {
        return pathinfo($filename, PATHINFO_EXTENSION);
    }

    /**
     * Função substituirCaracteresEspeciais
     * Substitui caracteres especiais por seus equivalentes comuns em uma string.
     *
     * @param string $texto - A string original que contém caracteres especiais.
     *
     * @return string - A string modificada com os caracteres especiais substituídos.
     */
    public function RemoveSpecialChars(string $string)
    {

        // Array associativo com os caracteres especiais e seus substitutos
        $caracteresEspeciais = array(
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'ã' => 'a',
            'õ' => 'o',
            'ç' => 'c',
            // Adicione outros caracteres especiais e substitutos conforme necessário
        );

        // Substituir os caracteres especiais na string usando str_replace
        // array_keys($caracteresEspeciais) retorna um array com as chaves (caracteres especiais)
        // array_values($caracteresEspeciais) retorna um array com os valores (substitutos)
        $string = str_replace(array_keys($caracteresEspeciais), array_values($caracteresEspeciais), $string);

        // Retornar a string modificada
        return $string;
    }

    /**
     * Função deleteFolder
     * Exclui uma pasta e seus arquivos recursivamente.
     *
     * @param string $folderPath - Caminho da pasta a ser excluída.
     *
     * @return bool
     */
    public function deleteFolder($folderPath): bool
    {

        // Verifica se a pasta existe
        if (is_dir($folderPath)) {

            // Abre o diretório
            $directory = opendir($folderPath);

            // Loop para excluir cada arquivo dentro da pasta
            while (($file = readdir($directory)) !== false) {

                // Ignora os diretórios pai e atual
                if ($file != '.' && $file != '..') {

                    $filePath = $folderPath . '/' . $file;

                    // Se for um diretório, chama recursivamente a função para excluir seus arquivos
                    if (is_dir($filePath)) {

                        $this->deleteFolder($filePath);
                    } else {

                        // Exclui o arquivo
                        unlink($filePath);
                    }
                }
            }

            // Fecha o diretório
            closedir($directory);

            // Exclui a pasta
            rmdir($folderPath);

            return true;
        } else {

            return false;
        }
    }

    public function listarDiretorios($path)
    {

        // Verifica se o caminho fornecido é um diretório válido
        if (is_dir($path)) {

            if ($dir = opendir($path)) {

                echo "Diretórios em '$path':<br>";

                // Itera pelos arquivos e diretórios dentro do caminho especificado
                while (($file = readdir($dir)) !== false) {

                    // Verifica se o arquivo é um diretório e filtra '.' e '..'
                    if (is_dir($path . '/' . $file) && $file != '.' && $file != '..') {

                        echo "📁 " . $file . "<br>";
                    }
                }

                // Fecha o diretório após a leitura
                closedir($dir);
            } else {
                echo "Não foi possível abrir o diretório.";
            }
        } else {
            echo "'$path' não é um diretório válido.";
        }
    }

    public function GetFileSizeFormated($filePath)
    {

        // Verifica se o arquivo existe
        if (!file_exists($filePath)) {
            return 'Arquivo não encontrado';
        }

        // Obtém o tamanho do arquivo em bytes
        $fileSize = filesize($filePath);

        // Define as unidades de medida
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        // Calcula o índice da unidade apropriada
        $index = 0;
        while ($fileSize >= 1024 && $index < count($units) - 1) {
            $fileSize /= 1024;
            $index++;
        }

        // Retorna o tamanho formatado com 2 casas decimais e a unidade correta
        return round($fileSize, 2) . ' ' . $units[$index];
    }

    /**
     * Função para formatar o tamanho do arquivo em uma unidade compreensível.
     *
     * Esta função recebe o tamanho do arquivo em bytes e o converte para uma unidade
     * de medida mais adequada, como kilobytes (KB), megabytes (MB), gigabytes (GB), etc.
     *
     * @param int $size Tamanho do arquivo em bytes.
     * @return string Tamanho formatado com duas casas decimais e a unidade correspondente.
     */
    public function formatFileSize($size)
    {

        // Array de unidades de medida
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

        // Loop para dividir o tamanho por 1024 até que seja menor que 1024
        for ($i = 0; $size > 1024; $i++) {

            $size /= 1024;
        }

        // Retorna o tamanho formatado com duas casas decimais e a unidade correspondente
        return round($size, 2) . ' ' . $units[$i];
    }

    public function getRandomBgClass()
    {
        $bgClasses = ['bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-light'];
        $randomIndex = array_rand($bgClasses);
        return $bgClasses[$randomIndex];
    }

    public function GetNextDirectory($baseDir, $fileLimit)
    {
        // Verifica se o diretório base existe, se não, cria
        if (!is_dir($baseDir)) {
            mkdir($baseDir, 0777, true);
        }

        // Encontra o próximo diretório disponível
        $dirNumber = 1;
        while (true) {
            $currentDir = $baseDir . DIRECTORY_SEPARATOR . str_pad($dirNumber, 2, '0', STR_PAD_LEFT);

            // Se o diretório não existir, cria e retorna ele
            if (!is_dir($currentDir)) {
                mkdir($currentDir, 0777, true);
                return str_replace('\\', '/', $currentDir);
            }

            // Conta a quantidade de arquivos no diretório atual
            $fileCount = count(glob($currentDir . DIRECTORY_SEPARATOR . '*'));

            // Se a quantidade de arquivos for menor que o limite, retorna o diretório atual
            if ($fileCount < $fileLimit) {
                return str_replace('\\', '/', $currentDir);
            }

            // Caso contrário, avança para o próximo diretório
            $dirNumber++;
        }
    }

    /** Criptografa uma string */
    public function securedEncrypt($first_key, $second_key, $method, $str)
    {
        /** String a ser criptografada */
        $data =  $str;

        $iv_length = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($iv_length);

        $first_encrypted = openssl_encrypt($data, $method, $first_key, OPENSSL_RAW_DATA, $iv);
        $second_encrypted = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);

        $output = base64_encode($iv . $second_encrypted . $first_encrypted);

        return $output;
    }

    /** Descriptografa uma string */
    public function securedDecrypt($first_key, $method, $input)
    {
        /** String a ser descriptografada */
        $mix = base64_decode($input);

        $iv_length = openssl_cipher_iv_length($method);

        $iv = substr($mix, 0, $iv_length);
        $first_encrypted = substr($mix, $iv_length + 64);

        /** Descriptografa string */
        $output = openssl_decrypt($first_encrypted, $method, $first_key, OPENSSL_RAW_DATA, $iv);

        return $output;
    }

    /** Retorna a string descriptografada */
    public function decryptData(string $data): string
    {

        /** Parametro de entrada */
        $this->data = $data;

        /** Verifica se a string a se descriptografada foi informada */
        if (!empty($this->data)) {

            return $this->securedDecrypt($this->getFirstKey(), $this->getMethod(), $this->data);
        } else {

            return $this->data;
        }
    }

    /** Retorna a string criptografada */
    public function encryptData(string $data): string
    {

        /** Parametro de entrada */
        $this->data = $data;

        /** Verifica se a string a se criptografada foi informada */
        if (!empty($this->data)) {

            return $this->securedEncrypt($this->getFirstKey(), $this->getSecondKey(), $this->getMethod(), $this->data);
        } else {

            return $this->data;
        }
    }

    // Função para validar um base64
    public function isValidBase64($string)
    {
        return base64_encode(base64_decode($string, true)) === $string;
    }

    /** Retorna o mês por extenso */
    function obterMesPorExtenso(?string $mes): string
    {

        $this->mes = $mes;

        if ($this->mes) {

            // Lista de meses por extenso
            $this->meses = [
                "01" => "Janeiro",
                "02" => "Fevereiro",
                "03" => "Março",
                "04" => "Abril",
                "05" => "Maio",
                "06" => "Junho",
                "07" => "Julho",
                "08" => "Agosto",
                "09" => "Setembro",
                "10" => "Outubro",
                "11" => "Novembro",
                "12" => "Dezembro",
            ];

            // Retorna o mês por extenso
            return (string)$this->meses[$this->mes];
        }
    }

    /** Função que trata os nomes das class */
    public function nameClass($str)
    {

        //TRATA A VARIAVEL
        $var = explode("_", strtolower($str));


        $j = 0;
        $n = null;
        foreach ($var as $value) {

            $n .= ucwords($value);
            $j++;
        }

        return $n;

        unset($j);
        unset($n);
    }

    /** Função que trata as variaveis */
    public function trataString($str)
    {

        //TRATA A VARIAVEL
        $var = explode("_", strtolower(str_replace("-", "_", str_replace(" ", "_", $str))));


        $j = 0;
        $n = null;
        foreach ($var as $value) {

            if ($j == 0) {

                $n .= $value;
                $j++;
            } elseif ($j > 0) {

                $n .= ucwords($value);
                $j++;
            }
        }

        return $n;

        unset($j);
        unset($n);
    }

    /** paginação */
    public function pagination(
        ?int $numberRecords,
        ?int $start,
        ?int $max,
        ?int $page,
        ?array $jsonData,
        ?string $form
    ) {

        /** Quantidade de registros junto ao banco de dados */
        $this->numberRecords = $numberRecords;
        $this->start = $start;
        $this->max = $max;
        $this->page = $page;
        $this->jsonData = $jsonData;
        $this->form = $form;

        /** Define o número de colunas de acordo com a quantidade de registros */
        $this->paginationColumns = ceil($this->numberRecords / $this->max);

        $this->jsonData['params'] = [];

        /** Verifica se é para gerar a paginação */
        if ($this->paginationColumns > 1) {

            /** Prepara a paginação de registros */
            $this->nav = '<nav>';
            $this->nav .= '<ul class="pagination justify-content-center list-unstyled d-flex align-items-center gap-2 mb-0 pagination-common-style">';

            /** Verifica se o número de colunas de paginação é superior a quantidade de paginas na tela */
            if ($this->paginationColumns > $this->pagination) {

                if ($this->start > 90) {

                    $this->jsonData['params']['start'] = $this->start / $this->max;
                    $this->jsonData['params']['page']  = $this->page - 1;
                    $this->jsonData['form']  = $this->form;


                    $this->nav .= '    <li class=" ' . ($this->page == 0 ? "disabled" : "") . '">';
                    $this->nav .= '        <a class="" href="#" onclick=\'new Request(' . json_encode($this->jsonData) . ');\'><i class="bi bi-arrow-left"></i></a>';
                    $this->nav .= '    </li>';
                }
            }

            /** Lista o número de paginas e seus respectivos links */
            $i = 0;
            for ($p = ($this->page * $this->pagination); $p < $this->paginationColumns; $p++) {

                $this->jsonData['params']['start'] = $p * $this->max;
                $this->jsonData['params']['page']  = $this->page;
                $this->jsonData['form']  = $this->form;

                $this->nav .= '        <li><a class="' . (($p * $max) == $this->start ? 'active' : '') . '" href="#" onclick=\'new Request(' . json_encode($this->jsonData) . ')\'>' . ($p + 1) . '</a></li>';

                if (($i + 1) == $this->pagination) {

                    break;
                }

                $i++;
            }

            /** Verifica se o número de colunas de paginação é superior a quantidade de paginas na tela */
            if ($this->paginationColumns > $this->pagination) {

                $this->jsonData['params']['start'] = ($p * $this->max) + $this->max;
                $this->jsonData['params']['page']  = $this->page + 1;
                $this->jsonData['form']  = $this->form;

                $this->nav .= '        <li class=" ' . (($p + 1) == $this->paginationColumns ? "disabled" : "") . '">';
                $this->nav .= '             <a class="" href="#" onclick=\'new Request(' . json_encode($this->jsonData) . ' );\'><i class="bi bi-arrow-right"></i></a>';
                $this->nav .= '        </li>';
            }

            $this->nav .= '     </ul>';
            $this->nav .= '</nav>';
        }

        /** Retorna o objeto de paginação */
        return $this->nav;
    }

    public function RemoveExtension($nomeArquivo)
    {
        return pathinfo($nomeArquivo, PATHINFO_FILENAME);
    }

    /** Gera uma senha aleatoriamente */
    public function NewPassword()
    {
        /**SEQUENCIAS ALEATORIAS DE LETRAS E NUMEROS*/
        $pwdtex = substr(str_shuffle('abcdefghijklmnpqrstuvxzwy'), 0, 3);
        $pwdint = substr(str_shuffle('123456789'), 0, 3);
        $pwdcar = substr(str_shuffle('@!_'), 0, 1);

        /**PEGO A DATA E HORA ATUAL + OS MICROSEGUNDOS E CONVERTO PARA MD5*/
        $data = substr(md5(date("dmYHis") . substr(sprintf("%0.1f", microtime()), -1)), 0, 1);
        $pwd = str_shuffle($pwdtex . $pwdint . $data . $pwdcar); //Gero a nova senha aleatoriamente		

        return $pwd;
    }

    /** Gera o token CSRF */
    public function getCSRF(): string
    {
        /** Verifica se existe token gerado */
        if (isset($_SESSION['csrf_token'])) {

            /** Limpa o token anterior caso exista */
            unset($_SESSION['csrf_token']);
        }

        /** Gera o novo Token */
        $this->csrfToken = bin2hex(random_bytes(32));

        /** Armazena o Token na sessão */
        $_SESSION['csrf_token'] = $this->csrfToken;

        /** Retorna o token para utilização */
        return $this->csrfToken;
    }

    /** Retorna as extensões de imagens */
    public function getExtensionImage(): array
    {
        return ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'];
    }

    /** Retorna as extensões de tipos de arquivos */
    public function getextensionFiles(): array
    {
        return ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar', '7z', 'tar', 'gz'];
    }


    /** Destrutor da classe */
    public function __destruct() {}
}
