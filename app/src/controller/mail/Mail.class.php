<?php

/** Defino o local onde esta a classe */

namespace src\controller\mail;

/** Importar classes PHPMailer para o namespace global */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/** Carregar o autoloader do Composer/PHPMailer */
require 'vendor/vendor/autoload.php';

class Mail
{
    /** Declaro as vaiavéis da classe */
    private ?string $messageType = null;
    private ?string $html = null;
    private ?string $preferences = null;

    private $data = [];
    private $mail = null;
    private ?string $host = null;
    private ?string $username = null;
    private ?string $password = null;
    private ?int $port = null;
    private ?string $fromEmail = null;
    private ?string $fromName = null;
    private ?string $destinyEmail = null;
    private ?string $destinyName = null;
    private ?string $subject = null;
    private ?string $body = null;


    /** Construtor da classe */
    public function __construct()
    {
        /** Crie uma instância da biblioteca PHPMailer; passar `true` habilita exceções */
        $this->mail = new PHPMailer(true);
        $this->mail->CharSet = "UTF-8";
    }

    /** Envia o e-mail a partir do seu tipo informado */
    public function sendMail(
        string $host,
        string $username,
        string $password,
        int $port,
        string $fromEmail,
        string $fromName,
        string $destinyEmail,
        string $destinyName,
        string $subject,
        string $body
    ) {

        /** Parametros de entrada */
        $this->host         = $host;
        $this->username     = $username;
        $this->password     = $password;
        $this->port         = $port;
        $this->fromEmail    = $fromEmail;
        $this->fromName     = $fromName;
        $this->destinyEmail = $destinyEmail;
        $this->destinyName  = $destinyName;
        $this->subject      = $subject;
        $this->body         = $body;


        try {

            /** Parâmetros de entrada */
            $this->body = $body;
            $this->subject = $subject;
            $this->port = $port;

            /** Configurações do servidor */
            $this->mail->isSMTP();
            $this->mail->Host = $this->host;
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $this->username;
            $this->mail->Password = $this->password;
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $this->mail->Port = $this->port;

            /** Destinatários */
            $this->mail->setFrom($this->username, 'Teste');
            $this->mail->addAddress($this->destinyEmail, $this->destinyName);

            /** Conteúdo */
            $this->mail->isHTML(true);
            $this->mail->Subject = $this->subject;
            $this->mail->Body = $this->body;
            $this->mail->AltBody = 'Para visualizar essa mensagem acesse';

            /** Envio do email */
            $this->mail->Send();

            //Server settings
            //$this->mail->SMTPDebug = SMTP::DEBUG_SERVER;         //Enable verbose debug output
            //$this->mail->SMTPDebug = 2;
            // $this->mail->isSMTP();                                 //Send using SMTP
            // $this->mail->Host       = $this->host;                 //Set the SMTP server to send through
            // $this->mail->SMTPAuth   = true;                        //Enable SMTP authentication
            // $this->mail->Username   = $this->username;             //SMTP username
            // $this->mail->Password   = $this->password;             //SMTP password
            // $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
            // $this->mail->Port       = $this->port;                 //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            // /**  Destinatário */
            // $this->mail->setFrom($this->fromEmail, $this->fromName);
            // $this->mail->addAddress($this->destinyEmail, $this->destinyName);

            // /** Conteúdo a ser enviado */
            // $this->mail->isHTML(true); # Habilita o envio da mensagem no formato HTML
            // $this->mail->Subject = $this->subject; # Assunto da mensagem enviada
            // $this->mail->Body    = $this->body; # Corpo da mensagem enviada               

            // /** Envio da mensagem */
            // $this->mail->send();

            return true;
        } catch (\Exception $e) {

            throw new \InvalidArgumentException("Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}.", 0);
        }
    }


    /** Finaliza a classe instanciada */
    public function __destruct() {}
}
