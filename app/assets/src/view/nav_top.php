<nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom">

    <div class="container">

    <a class="navbar-brand" href="#">
    
        <img src="assets/img/logo3.png" class="rounded border" alt="" width="38px">

    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">

        <span class="navbar-toggler-icon"></span>

    </button>

    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">

        <div class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">

            <div class="input-group">

                <input type="text" class="form-control" placeholder="Buscar no MyGed">

                <button class="btn btn-outline-secondary" type="button" id="button-addon2">

                    <i class="bi bi-search"></i>

                </button>

            </div>

        </div>

        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

            <li class="nav-item">

                <a class="nav-link" onclick="changeTheme()">
                
                    <i class="bi bi-moon-fill"></i>

                </a>

            </li>

            <li class="nav-item dropdown" style="z-index:9999;">

                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                    <?php echo $UserSessionResult->name; ?>

                </a>

                <ul class="dropdown-menu">

                    <li>

                        <a class="dropdown-item" onclick='new Request({"request" : {"path" : "view/users/users_profile"}})'>

                            <i class="bi bi-person me-1"></i>Perfil

                        </a>

                    </li>

                    <li>

                        <a class="dropdown-item" onclick='new Request({"request" : {"path" : "action/users/users_logout"}})'>

                            <i class="bi bi-box-arrow-left me-1"></i>Sair

                        </a>

                    </li>

                </ul>

            </li>

        </ul>

    </div>

    </div>

</nav>

<nav class="navbar navbar-expand-lg bg-nav-primary sticky-top shadow-sm">

    <div class="container">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item me-2">

                    <a class="nav-link" onclick='new Request({"request" : {"path" : "view/users/users_index"}, "loader" : {"type" : 2}})'>

                        <i class="bi bi-people-fill me-2"></i>Usuários

                    </a>

                </li>

                <li class="nav-item me-2">

                    <a class="nav-link" onclick='new Request({"request" : {"path" : "view/companies/companies_index"}, "loader" : {"type" : 2}})'>

                        <i class="bi bi-building me-2"></i>Empresas

                    </a>

                </li>

                <li class="nav-item me-2">

                    <a class="nav-link" onclick='new Request({"request" : {"path" : "view/modules/modules_index"}, "loader" : {"type" : 2}})'>

                        <i class="bi bi-archive me-2"></i>Módulos

                    </a>

                </li>

                <li class="nav-item me-2">

                    <a class="nav-link" onclick='new Request({"request" : {"path" : "view/files/files_index"}, "loader" : {"type" : 2}})'>

                        <i class="bi bi-file-earmark me-2"></i>Arquivos

                    </a>

                </li>

                <li class="nav-item dropdown">

                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">

                    <i class="bi bi-tags-fill me-2"></i>Indexação

                    </a>

                    <ul class="dropdown-menu shadow-sm">

                        <li>

                            <a class="dropdown-item" onclick='new Request({"request" : {"path" : "view/files_types/files_types_index"}, "loader" : {"type" : 2}})'>

                                Tipos

                            </a>

                        </li>

                        <li>

                            <a class="dropdown-item" onclick='new Request({"request" : {"path" : "view/files_categories/files_categories_index"}, "loader" : {"type" : 2}})'>

                                Categoria

                            </a>

                        </li>

                        <li>

                            <a class="dropdown-item" onclick='new Request({"request" : {"path" : "view/files_categories_tags/files_categories_tags_index"}, "loader" : {"type" : 2}})'>

                                Marcações

                            </a>

                        </li>

                    </ul>

                </li>

            </ul>

        </div>

    </div>

</nav>