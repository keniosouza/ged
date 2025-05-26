<?php

/**
 * Classe UsersValidade.class.php
 * @filesource
 * @autor        Kenio de Souza
 * @copyright    Copyright 2022 - Souza Consultoria Tecnológica
 * @package      vendor
 * @subpackage   controller
 * @version      1.0
 * @date         06/05/2022
 */

/** Defino o local da classes */

namespace src\controller\files_categories_tags;

/** Importação de classes */

use src\controller\main\Main;

class FilesCategoriesTagsHandling
{

    /** Parâmetros da classes */
    private $Main = null;

    private ?string $data = null;

    /** Método construtor */
    public function __construct()
    {

        /** Instânciamento de classes */
        $this->Main = new Main();
    }

    public function dictionary(int $format): null|string
    {

        switch ($format) {

            case 2:

                $this->data = 'number';
                break;

            case 3:

                $this->data = 'date';
                break;

            case 4:

                $this->data = 'price';
                break;

            case 5:

                $this->data = 'cpf';
                break;

            case 6:

                $this->data = 'cnpj';
                break;

            case 7:

                $this->data = 'cep';
                break;

            case 8:

                $this->data = 'phone_with_ddd';
                break;

            default:

                $this->data = null;
                break;
        }

        return $this->data;
    }
}
