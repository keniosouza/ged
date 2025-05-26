    <!-- ! ================================================================ !-->
    <!--! [Start] Navigation Manu !-->
    <!--! ================================================================ !-->
    <nav class="nxl-navigation">
        <div class="navbar-wrapper">
            <div class="m-header">
                <a href="home" class="b-brand">
                    <!-- ========   change your logo hear   ============ -->
                    <img src="./assets/images/logo-full.png" alt="" class="logo logo-lg" />
                    <img src="./assets/images/logo-abbr.png" alt="" class="logo logo-sm" />
                </a>
            </div>
            <div class="navbar-content">
                <ul class="nxl-navbar">
                    <li class="nxl-item nxl-caption">
                        <label>Menu</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-archive"></i></span>
                            <span class="nxl-mtext">Arquivos</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item" id="btn-batchs"><a class="nxl-link" href="#" onclick='new Request({"request" : {"path" : "view/batchs/batchs_index"}, 
                                                                                                                    "loader" : {"type" : 3},
                                                                                                                    "active" : {"btn" : "btn-batchs"},                                                                                                          
                                                                                                                    "response" : {"target" : "app-main"}, 
                                                                                                                    "form" : null})'>Lotes

                                </a>
                            </li>
                            <li class="nxl-item" id="btn-files"><a class="nxl-link" href="#" onclick='new Request({"request" : {"path" : "view/files/files_index"}, 
                                                                                                                                "loader" : {"type" : 3},
                                                                                                                                "active" : {"btn" : "btn-files"},                                                                                                          
                                                                                                                                "response" : {"target" : "app-main"}, 
                                                                                                                                "form" : null})'>Arquivos
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-bookmark"></i></span>
                            <span class="nxl-mtext">Indexação</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item" id="btn-types"><a class="nxl-link" href="#" onclick='new Request({"request" : {"path" : "view/files_types/files_types_index"}, 
                                                                                                                   "loader" : {"type" : 3}, 
                                                                                                                   "active" : {"btn" : "btn-types"},                                                                                                         
                                                                                                                   "response" : {"target" : "app-main"}, 
                                                                                                                   "form" : null})'>Tipos
                                </a>
                            </li>
                            <li class="nxl-item" id="btn-categories"><a class="nxl-link" href="#" onclick='new Request({"request" : {"path" : "view/files_categories/files_categories_index"}, 
                                                                                                                        "loader" : {"type" : 3},  
                                                                                                                        "active" : {"btn" : "btn-categories"},                                                                                                        
                                                                                                                        "response" : {"target" : "app-main"}, 
                                                                                                                        "form" : null})'>Categorias
                                </a>
                            </li>
                            <li class="nxl-item" id="btn-tags"><a class="nxl-link" href="#" onclick='new Request({"request" : {"path" : "view/files_categories_tags/files_categories_tags_index"}, 
                                                                                                                  "loader" : {"type" : 3},                                                                                                          
                                                                                                                  "active" : {"btn" : "btn-tags"},
                                                                                                                  "response" : {"target" : "app-main"}, 
                                                                                                                  "form" : null})'>Marcações
                                </a>
                            </li>
                            <li class="nxl-item" id="btn-batchs2"><a class="nxl-link" href="#" onclick='new Request({"request" : {"path" : "view/batchs/batchs_index"}, 
                                                                                                                     "loader" : {"type" : 3},
                                                                                                                     "active" : {"btn" : "btn-batchs2"},                                                                                                          
                                                                                                                     "response" : {"target" : "app-main"}, 
                                                                                                                     "form" : null})'>Lotes
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-sliders"></i></span>
                            <span class="nxl-mtext">Administração</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item" id="btn-companies"><a class="nxl-link" href="#" onclick='new Request({"request" : {"path" : "view/companies/companies_index"}, 
                                                                                                                       "loader" : {"type" : 3},
                                                                                                                       "active" : {"btn" : "btn-companies"},                                                                                                          
                                                                                                                       "response" : {"target" : "app-main"}, 
                                                                                                                       "form" : null})'>Empresas
                                </a>
                            </li>
                            <li class="nxl-item" id="btn-modules"><a class="nxl-link" href="#" onclick='new Request({"request" : {"path" : "view/modules/modules_index"}, 
                                                                                                                     "loader" : {"type" : 3},  
                                                                                                                     "active" : {"btn" : "btn-modules"},                                                                                                        
                                                                                                                     "response" : {"target" : "app-main"}, 
                                                                                                                     "form" : null})'>Módulos
                                </a>
                            </li>
                            <li class="nxl-item" id="btn-users"><a class="nxl-link" href="#" onclick='new Request({"request" : {"path" : "view/users/users_index"}, 
                                                                                                                   "loader" : {"type" : 3}, 
                                                                                                                   "active" : {"btn" : "btn-users"},
                                                                                                                   "response" : {"target" : "app-main"}, 
                                                                                                                   "form" : null})'>Usuários
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-life-buoy"></i></span>
                            <span class="nxl-mtext">Help Center</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" href="#">Suporte</a></li>
                            <li class="nxl-item"><a class="nxl-link" href="#">Documentação</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!--! ================================================================ !-->
    <!--! [End]  Navigation Manu !-->
    <!--! ================================================================ !-->