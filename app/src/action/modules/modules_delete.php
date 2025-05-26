<?php

/** Importação de classes */

use src\model\Modules;
use src\controller\modules\ModulesValidate;

try {

    /** Instânciamento de classes */
    $Modules = new Modules();
    $ModulesValidate = new ModulesValidate();

    /** Controle de resultados */
    $result = null;

    /** Parametros de entrada */
    $moduleId = isset($_POST['module_id']) ? (int)filter_input(INPUT_POST, 'module_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Validando os campos de entrada */
    $ModulesValidate->setModuleId($moduleId);

    /** Verifico a existência de erros */
    if (!empty($ModulesValidate->getErrors())) {

        /** Informo */
        throw new InvalidArgumentException($ModulesValidate->getErrors(), 0);
    } else {

        /** Efetua um novo cadastro ou salva os novos dados */
        if ($Modules->Delete(
            $ModulesValidate->getModuleId(),
            $_SESSION['MY_SAAS_USER']->user_id
        )) {

            // Result
            $result = [

                'code' => 200,
                'data' => 'Empresa removida com sucesso',
                'toast' => [
                    [
                        'background' => 'warning',
                        'data' => '<i class="bi bi-trash me-1"></i>Módulo removido'
                    ]
                ],
                'remove' => [
                    [
                        'id' => $ModulesValidate->getModuleId(),
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
