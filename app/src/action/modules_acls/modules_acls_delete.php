<?php

/** Importação de classes */

use src\model\ModulesAcls;
use src\controller\modules_acls\ModulesAclsValidate;

try {

    /** Instânciamento de classes */
    $ModulesAcls = new ModulesAcls();
    $ModulesAclsValidate = new ModulesAclsValidate();

    /** Controle de resultados */
    $result = null;

    /** Parametros de entrada */
    $moduleAclId = isset($_POST['module_acl_id']) ? (int)filter_input(INPUT_POST, 'module_acl_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Validando os campos de entrada */
    $ModulesAclsValidate->setModuleAclId($moduleAclId);

    /** Verifico a existência de erros */
    if (!empty($ModulesAclsValidate->getErrors())) {

        /** Informo */
        throw new InvalidArgumentException($ModulesAclsValidate->getErrors(), 0);
    } else {

        /** Efetua um novo cadastro ou salva os novos dados */
        if ($ModulesAcls->Delete($ModulesAclsValidate->getModuleAclId())) {

            // Result
            $result = [

                'code' => 200,
                'data' => 'Perfil Atualizado',
                'toast' => [
                    [
                        'background' => 'primary',
                        'data' => 'Registro removido'
                    ]
                ],
                'remove' => [
                    [
                        'id' => $ModulesAclsValidate->getModuleAclId(),
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
