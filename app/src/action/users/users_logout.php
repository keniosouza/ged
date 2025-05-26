<?php

try {

    // Verifica se a sessão está ativa
    if (isset($_SESSION['MY_SAAS_USER'])) {

        // Limpa todas as variáveis de sessão
        session_unset();

        // Destroi a sessão
        session_destroy();

        // Opcional: Remove o cookie da sessão, se quiser garantir que ele foi excluído
        if (ini_get('session.use_cookies')) {

            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        // Result
        $result = [

            'code' => 200,
            'toast' => [
                [
                    'background' => 'primary',
                    'data' => 'Sessão encerrada!'
                ]
            ],
            'reload' => [
                [
                    'url' => str_replace('app/', '', $Main->getUrlApp()),
                ]
            ],

        ];

        /** Caso a sessão não esteja ativa, 
         * retorna para a tela de login */
    } else {

        // Result
        $result = [

            'code' => 200,
            'toast' => [
                [
                    'background' => 'primary',
                    'data' => 'Sessão encerrada!'
                ]
            ],
            'reload' => [
                [
                    'url' => str_replace('app/', '', $Main->getUrlApp()),
                ]
            ],

        ];
    }

    /** Envio */
    echo json_encode($result);

    /** Paro o procedimento */
    exit;
} catch (Exception $exception) {

    /** Preparo o formulario para retorno **/
    $result = [

        'code' => 0,
        'data' => $exception->getMessage()
    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}
