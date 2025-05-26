<?php

/** Importação de classes */

use src\model\Modules;
use src\model\ModulesAcls;
use src\controller\modules_acls\ModulesAclsValidate;

try {

    /** Instânciamento de classes */
    $Modules = new Modules();
    $ModulesAcls = new ModulesAcls();
    $ModulesAclsValidate = new ModulesAclsValidate();

    /** Parametros de entrada */
    $moduleId    = isset($_POST['module_id'])     ? (int)filter_input(INPUT_POST, 'module_id', FILTER_SANITIZE_NUMBER_INT)     : 0;
    $moduleAclId = isset($_POST['module_acl_id']) ? (int)filter_input(INPUT_POST, 'module_acl_id', FILTER_SANITIZE_NUMBER_INT) : 0;

    /** Busco o registro desejado */
    $ModulesGetResult = $Modules->Get($moduleId);

    /** Tratamento dos dados de entrada */
    $ModulesAclsValidate->setModuleId($moduleId);
    $ModulesAclsValidate->setModuleAclId($moduleAclId);

    /** Verifico se existe registro */
    if ($ModulesAclsValidate->getModuleAclId() > 0) {

        /** Busca de registro */
        $ModulesAclsGetResult = $ModulesAcls->get($ModulesAclsValidate->getModuleAclId());
    }

?>

    <div class="d-flex justify-content-between align-items-start mb-2">

        <div class="text-body">

            <h5 class="fw-light">

                Gerenciar níveis de acesso

            </h5>

        </div>

    </div>

    <div class="card shadow-sm border animate slideIn">

        <form class="card-body" role="form" id="ModulesAclsForm">

            <div class="row row-dynamic-input g-1">

                <div class="col-md-12">

                    <div class="form-group">

                        <label for="description">

                            Descrição

                        </label>

                        <input type="text" class="form-control form-control-solid" id="description" name="description" value="<?php echo @(string)$ModulesAclsGetResult->description ?>">

                    </div>

                </div>

                <div class="col-md-12">

                    <div class="alert alert-warning" role="alert">

                        <h5 class="alert-heading">

                            É recomendado cadastrar as seguintes níveis de acesso:

                        </h5>

                        <p>
                            criar, editar, remover e ler

                        </p>

                    </div>

                </div>

                <?php

                /** Pego o histórico existente */
                $preferences = json_decode($ModulesAclsGetResult->preferences, true);

                /** Verifica se já exitem itens cadastrados */
                if (!empty($preference)) {

                    /** Consulta os usuário cadastrados*/
                    foreach ($preferences as $key => $result) { ?>

                        <div class="col-md-3" id="col-<?php echo $key ?>">

                            <div class="form-group">

                                <label for="preferences<?php echo $key ?>">

                                    Nome permissão

                                </label>

                                <input id="preferences<?php echo $key ?>" type="text" class="form-control form-control-solid" name="preferences[]" value="<?php echo $result ?>">
                                <a class="link" style="cursor: pointer;" onclick="removeItem('col-<?php echo $key ?>')"><i class="bi bi-trash me-1"></i>Remover</a>

                            </div>

                        </div>

                    <?php

                    }
                } else {
                    /** Se não houver itens cadastrados, habilito o cadastro dos itens padronizados */

                    ?>

                    <div class="col-md-3" id="col-criar">

                        <div class="form-group">

                            <label for="preferences-criar">

                                Nome permissão

                            </label>

                            <input id="preferences-criar" type="text" class="form-control form-control-solid" name="preferences[]" value="criar">
                            <a class="link" style="cursor: pointer;" onclick="removeItem('col-criar')"><i class="bi bi-trash me-1"></i>Remover</a>

                        </div>

                    </div>

                    <div class="col-md-3" id="col-editar">

                        <div class="form-group">

                            <label for="preferences-editar">

                                Nome permissão

                            </label>

                            <input id="preferences-editar" type="text" class="form-control form-control-solid" name="preferences[]" value="editar">
                            <a class="link" style="cursor: pointer;" onclick="removeItem('col-editar')"><i class="bi bi-trash me-1"></i>Remover</a>

                        </div>

                    </div>

                    <div class="col-md-3" id="col-remover">

                        <div class="form-group">

                            <label for="preferences-remover">

                                Nome permissão

                            </label>

                            <input id="preferences-remover" type="text" class="form-control form-control-solid" name="preferences[]" value="remover">
                            <a class="link" style="cursor: pointer;" onclick="removeItem('col-remover')"><i class="bi bi-trash me-1"></i>Remover</a>

                        </div>

                    </div>

                    <div class="col-md-3" id="col-ler">

                        <div class="form-group">

                            <label for="preferences-ler">

                                Nome permissão

                            </label>

                            <input id="preferences-ler" type="text" class="form-control form-control-solid" name="preferences[]" value="ler">
                            <a class="link" style="cursor: pointer;" onclick="removeItem('col-ler')"><i class="bi bi-trash me-1"></i>Remover</a>

                        </div>

                    </div>

                <?php
                }

                ?>

            </div>

            <div class="row mt-4">

                <div class="col-md-12 text-end">

                    <button type="button" class="btn btn-light btn-sm" onclick="addItem()">

                        <i class="bi bi-plus me-1"></i>Adicionar Campo

                    </button>

                    <button type="button" id="btnNivelAcesso" class="btn btn-secondary btn-sm" onclick='new Request({"request" : {"path" : "view/modules/modules_details"}, 
                                                                                                                  "loader" : {"type" : 1, "target" : "btnNivelAcesso"},
                                                                                                                  "params" : {"module_id" : "<?php echo $ModulesAclsValidate->getModuleId() ?>"}})'>

                        <i class="bi bi-arrow-return-left me-1"></i>Retornar

                    </button>

                    <button class="btn btn-primary btn-sm" id="btnModulesSave" onclick='new Request({"request" : {"path" : "action/modules_acls/modules_acls_save"}, 
                                                                                                     "loader" : {"type" : 1, 
                                                                                                     "target" : "btnModulesSave"}, 
                                                                                                     "response" : {"target" : "ModulesFormResponse"}, 
                                                                                                     "form" : "ModulesAclsForm"})' type="button">

                        <i class="bi bi-check me-1"></i>Salvar

                </div>

            </div>

            <span id="ModulesFormResponse"></span>

            <input type="hidden" name="FOLDER" value="ACTION" />
            <input type="hidden" name="TABLE" value="modules_acls" />
            <input type="hidden" name="ACTION" value="modules_acls_save" />
            <input type="hidden" name="module_id" value="<?php echo @(int)$ModulesAclsValidate->getModuleId() ?>" />
            <input type="hidden" name="module_acl_id" value="<?php echo @(int)$ModulesAclsGetResult->module_acl_id ?>" />

        </form>

    </div>

    <script type="text/javascript">
        function addItem() {

            /** Id aleatorio */
            let key = Math.random();

            /** Defino a estrutura HTML */
            let html = '<div class="col-md-3 animate slideIn" id="col-' + key + '">';
            html += '	<div class="form-group">';
            html += '		<label for="preferences' + key + '">';
            html += '			Nome permissão';
            html += '		</label>';
            html += '		<input id="preferences' + key + '" type="text" class="form-control form-control-solid" name="preferences[]">';
            html += '		<a class="link" style="cursor: pointer;" onclick="removeItem(\'col-' + key + '\')"><i class="bi bi-trash me-1"></i>Remover</a>';
            html += '	</div>';
            html += '</div>';

            /** Preencho o HTML dentro da DIV desejad **/
            $('.row-dynamic-input').append(html);

            /** Defino o foco */
            $('preferences' + key).focus();

        }
    </script>

<?php

    /** Prego a estrutura do arquivo */
    $data = ob_get_contents();

    /** Removo o arquivo incluido */
    ob_clean();

    // Result
    $result = array(

        'code' => 200,
        'modal' => [
            [
                'title' => 'Módulo / ' . $ModulesGetResult->name,
                'data' => $data,
                'size' => 'lg',
                'type' => null,
                'procedure' => null,
            ]
        ],

    );

    /** Envio **/
    echo json_encode($result);

    /** Paro o procedimento **/
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
