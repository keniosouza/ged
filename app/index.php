<?php

/** Importação do Autoload lado servidor */
require_once('./autoload.php');

// IMportação de classes
use src\controller\main\Main;

// Instânciamento de classes
$Main = new Main();

/** Inicializa a sessão */
$Main->startSession();

/** Verifica se o usuário está logado */
$Main->checkSession();


?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <base href="<?php echo $Main->getUrlApp(); ?>" />
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keyword" content="" />
    <meta name="author" content="WRAPCODERS" />

    <title>DOCVERSO 1.0</title>

    <link rel="shortcut icon" type="image/x-icon" href="./assets/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="./assets/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="./assets/vendors/css/vendors.min.css" />
    <link rel="stylesheet" type="text/css" href="./assets/vendors/css/daterangepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="./assets/css/theme.min.css" />
    <link rel="stylesheet" type="text/css" href="./assets/css/main.css" />

</head>

<body>

    <!-- Modal -->
    <div id="wrapper-modal"></div>

    <!-- Modal -->
    <div id="wrapper-toast"></div>

    <?php include('./assets/inc/nav.php'); ?>
    <?php include('./assets/inc/header.php'); ?>
    <?php include('./assets/inc/main.php'); ?>

    <script src="./assets/vendors/js/vendors.min.js"></script>
    <script src="./assets/js/jquery.mask.min.js"></script>
    <script src="./assets/js/jquery.price.format.js"></script>
    <script src="./assets/src/utils/loader.js"></script>
    <script src="./assets/src/utils/modal.js"></script>
    <script src="./assets/src/utils/toast.js"></script>
    <script src="./assets/src/services/file.js"></script>
    <script src="./assets/src/services/response.js"></script>
    <script src="./assets/src/services/router.js"></script>
    <script src="./assets/src/utils/offcanva.js"></script>
    <script src="./assets/src/utils/main.js"></script>

    <!-- Carrega o conteúdo inicial -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            new Request({
                "request": {
                    "path": "view/files/files_index"
                },
                "loader": {
                    "type": 3
                },
                "active": {
                    "btn": "btn-files"
                },
                "response": {
                    "target": "app-main"
                },
                "form": null,
                "params": {
                    "csrf_token": "<?php echo $Main->getCSRF(); ?>"
                }
            })
        })
    </script>

    <script src="./assets/js/theme-customizer-init.min.js"></script>
    <script src="./assets/js/common-init.min.js"></script>

</body>

</html>