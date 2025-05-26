<?php

/** Defino o local da classes */
namespace src\controller\logs;

/** Importação de classes */
use src\controller\main\Main;

class LogsHandling
{

    /** Parâmetros da classes */
    private $Main = null;
    private $errors = array();
    private $info = null;


    /** Método construtor */
    public function __construct()
    {

        /** Instânciamento de classes */
        $this->Main = new Main();

    }

    public function dictionary($path)
    {

        $descriptions = [

            'action/files/files_save_file.php' => 'Publicou o arquivo',
            'action/files/files_delete.php' => 'Removeu o arquivo',
            'action/files/files_to_text.php' => 'Realizou o OCR do arquivo',
            'action/files/files_to_word.php' => 'Converteu o arquivo para word',
            'view/files/files_details.php' => 'Acessou o arquivo',
            'view/files/files_index.php' => 'Acessou a tela de arquivos',
            'view/files/files_in_List.php' => 'Acessou os arquivos em lista',
            'view/files/files_in_gallery.php' => 'Acessou os arquivos em galeria',

        ];

        // Verifico a descricao que devo retornar
        return $descriptions[$path];

    }

}
