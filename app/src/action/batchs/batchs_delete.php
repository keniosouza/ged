<?php

/** Importação de classes */

use src\model\Batchs;
use src\controller\batchs\BatchsValidate;

try {

    /** Instânciamento de classes */
    $Batchs = new Batchs();
    $BatchsValidate = new BatchsValidate();

    /** Controle de resultados */
    $result = null;

    /** Parametros de entrada */
    $batchId = isset($_POST['batch_id']) ? (int)filter_input(INPUT_POST, 'batch_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Id do usuário logado */
    $userId = (int)$_SESSION['MY_SAAS_USER']->user_id;

    /** Validando os campos de entrada */
    $BatchsValidate->setbatchId($batchId);
    $BatchsValidate->setUserId($userId);

    /** Verifico a existência de erros */
    if (!empty($BatchsValidate->getErrors())) {

        /** Informo */
        throw new InvalidArgumentException($BatchsValidate->getErrors(), 0);
    } else {

        /** Efetua um novo cadastro ou salva os novos dados */
        if ($Batchs->Delete(
            $BatchsValidate->getbatchId(),
            $BatchsValidate->getUserId()
        )) {

            // Result
            $result = [

                'code' => 200,
                'data' => 'Lote de arquivos removido com sucesso',
                'toast' => [
                    [
                        'background' => 'warning',
                        'data' => '<i class="bi bi-trash me-1"></i>Lote de arquivos removido'
                    ]
                ],
                'remove' => [
                    [
                        'id' => $BatchsValidate->getbatchId(),
                    ]
                ]

            ];
        } else {

            /** Retorno mensagem de erro */
            throw new InvalidArgumentException('Não foi possivel remover o lote de arquivos', 0);
        }
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
