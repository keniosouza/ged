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
    $moduleAclId = isset($_POST['module_acl_id']) ? (int)filter_input(INPUT_POST, 'module_acl_id', FILTER_SANITIZE_NUMBER_INT)     : 0;
    $moduleId    = isset($_POST['module_id'])     ? (int)filter_input(INPUT_POST, 'module_id', FILTER_SANITIZE_NUMBER_INT)         : 0;
    $description = isset($_POST['description'])   ? (string)filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $preferences = [];

    /** Verifica se as preferências foram informadas */
    if ((isset($_POST['preferences'])) && (count($_POST['preferences'])) > 0) {

        /** Lista todos os itens  */
        foreach ($_POST['preferences'] as $value) {

            /** Verifica se o item não está vazio */
            if (!empty($value)) {

                array_push($preferences, $value);
            }
        }

        /** Verifica se as preferências foram informadas */
        if (count($preferences) > 0) {

            /** Paramentros de entrada, 
             * sanitizando a array 
             * preferences */
            /** Sanitiza os campos */
            $preferences = array_map(function ($value) {
                return filter_var($value, FILTER_SANITIZE_SPECIAL_CHARS); // Limpa tags HTML e scripts
            }, $preferences);

            /** Validando os campos de entrada */
            $ModulesAclsValidate->setModuleAclId($moduleAclId);
            $ModulesAclsValidate->setModuleId($moduleId);
            $ModulesAclsValidate->setDescription($description);
            $ModulesAclsValidate->setPreferences(json_encode($preferences));

            /** Verifico a existência de erros */
            if (!empty($ModulesAclsValidate->getErrors())) {

                /** Informo */
                throw new InvalidArgumentException($ModulesAclsValidate->getErrors(), 0);
            } else {

                /** Efetua um novo cadastro ou salva os novos dados */
                if ($ModulesAcls->Save(
                    $ModulesAclsValidate->getModuleAclId(),
                    $ModulesAclsValidate->getModuleId(),
                    $ModulesAclsValidate->getDescription(),
                    $ModulesAclsValidate->getPreferences()
                )) {

                    // Result
                    $result = [

                        'code' => 200,
                        'data' => 'Permissão do módulo atualizado com sucesso',
                        'toast' => [
                            [
                                'background' => 'success',
                                'data' => 'Permissão do módulo atualizado com sucesso'
                            ]
                        ],
                        'redirect' => [
                            [
                                'request' => 'view/modules/modules_index',
                            ]
                        ]

                    ];
                } else {

                    /** Retorno mensagem de erro */
                    throw new InvalidArgumentException('Não foi possivel salvar o registro', 0);
                }
            }
        } else {

            /** Retorno mensagem de erro */
            throw new InvalidArgumentException('Pelo menos uma permissão deve ser informada', 0);
        }
    } else {

        /** Retorno mensagem de erro */
        throw new InvalidArgumentException('Pelo menos uma permissão deve ser informada', 0);
    }

    /** Envio */
    echo json_encode($result);

    /** Paro o procedimento */
    exit;
} catch (Exception $exception) {

    /** Preparo o formulario para retorno **/
    $result = [

        'code' => 0,
        'data' => '<div class="alert alert-warning mt-2" role="alert"><i class="bi bi-exclamation-triangle me-2"></i>' . $exception->getMessage() . '</div>'
    ];

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
    exit;
}
