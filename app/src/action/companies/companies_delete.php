<?php

/** Importação de classes */

use src\model\Companies;
use src\controller\Companies\CompaniesValidate;

try {

    /** Instânciamento de classes */
    $Companies = new Companies();
    $CompaniesValidate = new CompaniesValidate();

    /** Controle de resultados */
    $result = null;

    /** Parametros de entrada */
    $companyId = isset($_POST['company_id']) ? (int)filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Validando os campos de entrada */
    $CompaniesValidate->setCompanyId($companyId);

    /** Verifico a existência de erros */
    if (!empty($CompaniesValidate->getErrors())) {

        /** Informo */
        throw new InvalidArgumentException($CompaniesValidate->getErrors(), 0);
    } else {

        /** Efetua um novo cadastro ou salva os novos dados */
        if ($Companies->Delete($CompaniesValidate->getCompanyId())) {

            // Result
            $result = [

                'code' => 200,
                'data' => 'Empresa removida com sucesso',
                'toast' => [
                    [
                        'background' => 'primary',
                        'data' => '<i class="bi bi-trash me-1"></i>Empresa removida'
                    ]
                ],
                'remove' => [
                    [
                        'id' => $CompaniesValidate->getCompanyId(),
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
