<?php

/** Ativo a exibição de erros */
error_reporting(E_ALL);
ini_set('display_errors', 'On');

?>
<!-- <div class="d-flex justify-content-between align-items-start mb-2">

    <div class="text-body">

        <h6 class="text-body-secondary fw-medium">

            Arquivos /

        </h6>

        <h5 class="fw-bold text-body">

            Envio

        </h5>

    </div>

    <button class="btn btn-primary btn-sm" onclick='new Request({"request" : {"path" : "view/files/files_index"}})'>

        <i class="bi bi-x"></i>Fechar

    </button>

</div> -->

<!-- Espaço reservado para construção do formulário de arquivo -->
<!-- <div id="FilesFormWrapper">

    <script type="text/javascript">
        /** Envio de Requisição */
        new Request({
            "request": {
                "path": "view/files/files_form_component"
            },
            "response": {
                "target": "FilesFormWrapper"
            }
        })
    </script>

</div> -->

<form id="FilesFormHeader">

    <input type="hidden" name="path" value="action/files/files_save_file.php" />

</form>

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
            'title' => 'Categoria de Arquivos / Visão Geral / Formulário',
            'data' => $data,
            'size' => 'lg',
            'type' => null,
            'procedure' => null,
        ]
    ],

);
