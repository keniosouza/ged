<?php

/** Importação de classes  */

use src\controller\mail\Mail;
use src\model\Users;
use src\controller\users\UsersValidate;

try {

    /** Instânciamento de classes  */
    $Mail = new Mail();
    $Users = new Users();
    $UsersValidate = new UsersValidate();

    /** Controle de resultados */
    $result = null;
    $url = null;

    /** Parametros de entrada */
    $email     = isset($_POST['email'])      ? (string) filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL)             : '';
    $csrfToken = isset($_POST['csrf_token']) ? (string)filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS) : null;

    /** Verifica se o Token CSRF é válido */
    if ($csrfToken === $_SESSION['csrf_token']) {

        /** Validando os campos de entrada */
        $UsersValidate->setEmail($email);

        /** Verifico a existência de erros */
        if (!empty($UsersValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($UsersValidate->getErrors(), 0);
        } else {
            
            /** Busco o email informado */
            $UsersResult = $Users->GetByEmail($UsersValidate->getEmail());

            /** Verifico se o email foi localizado */
            if (!empty($UsersResult->email)) {

                /** Assunto da mensagem */
                $subject = 'Cadastrar nova senha';

                /** Url para renovar a senha */
                $url = str_replace('app/', '', $Main->getUrlApp()) . 'new-password/' . $Main->encryptData($UsersValidate->getEmail() . '*' . $UsersResult->user_id . '*' . date('Y-m-d H:i:s'));

                /** Legenda 
                 * <[{URL}]> => Marcação da url a ser substituída
                 */

                /** Corpo da mensagem */
                $body = str_replace("<[{URL}]>", $url, $Main->getMessageNewPassowrd());

                /** Envia a mensagem */
                if ($Mail->sendMail(
                    $Main->getHost(), # Servidor do e-mail
                    $Main->getUsername(), # Usuário do e-mail
                    $Main->getPassword(), # Senha do e-mail de envio
                    $Main->getPort(), # Porta de envio
                    $Main->getFromEmail(), # E-mail de envio
                    $Main->getFromName(), # Nome de envio
                    $UsersResult->email, # E-mai destino
                    $UsersResult->name, # Nome destino
                    $subject, # Assunto do e-mail
                    $body # Mensagem a ser enviada
                )) {

                    /** Result **/
                    $result = [

                        'cod' => 200,
                        'title' => 'Atenção',
                        'message' => '<div class="alert alert-success" role="alert">Mensagem enviada com sucesso!</div>',
                        'redirect' => '',

                    ];

                    /** Envio **/
                    echo json_encode($result);

                    /** Paro o procedimento **/
                    exit;
                } else {

                    /** Informo */
                    throw new InvalidArgumentException('Erro ao enviar a mensagem, tente novamente.', 0);
                }
            } else {

                /** Informo */
                throw new InvalidArgumentException('Usuário não localizado, verifique o e-mail informado.', 0);
            }
        }
    } else {

        /** Informo */
        throw new InvalidArgumentException('Token CSRF inválido', 0);
    }
} catch (Exception $exception) {

    /** Preparo o formulario para retorno **/
    $result = [

        'code' => 0,
        'data' => $exception->getMessage()// $url //$exception->getMessage()
    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}
