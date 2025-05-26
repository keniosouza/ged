<?php

/** Importação de classes */

use src\model\Companies;
use src\controller\companies\CompaniesValidate;

try {

    /** Instânciamento de classes */
    $Companies = new Companies();
    $CompaniesValidate = new CompaniesValidate();

    /** Controle de resultados */
    $result = null;

    /** Parametros de entrada */
    $companyId    = isset($_POST['company_id'])    ? (int)filter_input(INPUT_POST, 'company_id', FILTER_SANITIZE_NUMBER_INT)          : 0;
    $nameFantasy  = isset($_POST['name_fantasy'])  ? (string)filter_input(INPUT_POST, 'name_fantasy', FILTER_SANITIZE_SPECIAL_CHARS)  : '';
    $nameBusiness = isset($_POST['name_business']) ? (string)filter_input(INPUT_POST, 'name_business', FILTER_SANITIZE_SPECIAL_CHARS) : '';
    $status       = isset($_POST['status'])        ? (string)filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS)        : '';
    $csrfToken    = isset($_POST['csrf_token'])    ? (string)filter_input(INPUT_POST, 'csrf_token', FILTER_SANITIZE_SPECIAL_CHARS)    : null;

    /** Id do usuário logado */
    $userId = $_SESSION['MY_SAAS_USER']->user_id;

    /** Verifica se o Token CSRF é válido */
    if ($csrfToken === $_SESSION['csrf_token']) {

        /** Validando os campos de entrada */
        $CompaniesValidate->setCompanyId($companyId);
        $CompaniesValidate->setNameFantasy($nameFantasy);
        $CompaniesValidate->setNameBusiness($nameBusiness);
        $CompaniesValidate->setStatus($status);

        /** Verifico a existência de erros */
        if (!empty($CompaniesValidate->getErrors())) {

            /** Informo */
            throw new InvalidArgumentException($CompaniesValidate->getErrors(), 0);
        } else {

            /** Efetua um novo cadastro ou salva os novos dados */
            if ($Companies->Save(
                $CompaniesValidate->getCompanyId(),
                $CompaniesValidate->getNameBusiness(),
                $CompaniesValidate->getNameFantasy(),
                $CompaniesValidate->getStatus()
            )) {

                /** Prepara a mensagem de retorno - sucesso */
                $result = [

                    'code' => 200,
                    'data' => ($CompaniesValidate->getCompanyId() > 0 ? 'Cadastro atualizado com sucesso' : 'Cadastro efetuado com sucesso'),
                    'toast' => [
                        [
                            'background' => ($CompaniesValidate->getStatus() == 'D' ? 'warning' : 'success'),
                            'data' => ($CompaniesValidate->getStatus() == 'D' ? '<i class="bi bi-trash3 me-1"></i>Empresa removida com sucesso' : '<i class="bi bi-check me-1"></i>Dados salvos com sucesso')
                        ]
                    ]
                ];

                if ($CompaniesValidate->getStatus() == 'D') {

                    $result["remove"] = [
                        [
                            'id' => $CompaniesValidate->getStatus() == 'D' ? $CompaniesValidate->getCompanyId() : null
                        ]
                    ];
                } else {

                    $result["redirect"] = [
                        [
                            'path' => 'view/companies/companies_index',
                            'type' => 3,
                            'target' => 'app-main'
                        ]
                    ];
                }
            } else {

                /** Retorno mensagem de erro */
                throw new InvalidArgumentException(($CompaniesValidate->getCompanyId() > 0 ? 'Não foi possível atualizar o cadastro' : 'Não foi possível efetuar o cadastro'), 0);
            }
        }

        /** Envio */
        echo json_encode($result);

        /** Paro o procedimento */
        exit;
    } else {

        /** Informo */
        throw new InvalidArgumentException('Token CSRF inválido', 0);
    }
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
