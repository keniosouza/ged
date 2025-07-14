<?php

/** Importação de classes */

use src\model\Files;
use src\controller\Files\FilesValidate;

/** Instânciamento de classes */
$Files = new Files();
$FilesValidate = new FilesValidate();

/** Controle de resultados */
$result = null;

/** Validando os campos de entrada */
$FilesValidate->setFileId((int) filter_input(INPUT_POST, 'file_id', FILTER_SANITIZE_SPECIAL_CHARS));
$FilesValidate->setUserId((int) $_SESSION['MY_SAAS_USER']->user_id);

/** Verifico a existência de erros */
if (!empty($FilesValidate->getErrors())) {

    /** Caso ocorra algum erro, lança uma exceção com a mensagem de erro. */
    throw new InvalidArgumentException($FilesValidate->getErrors(), 0);
} else {

    /** Efetua um novo cadastro ou salva os novos dados */
    if ($Files->Delete($FilesValidate->getFileId(), $FilesValidate->getUserId())) {

        // Result
        $result = [

            'code' => 200,
            'data' => 'Perfil Atualizado',
            'toast' => [
                [
                    'background' => 'alert-primary',
                    'data' => '<i class="bi bi-trash me-1"></i>Registro removido'
                ]
            ],
            'remove' => [
                [
                    'id' => $FilesValidate->getFileId(),
                ]
            ]

        ];
    } else {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException('Não foi possivel remover o registro', 0);
    }
}

/** Envio */
echo json_encode($result);

/** Paro o procedimento */
exit;
