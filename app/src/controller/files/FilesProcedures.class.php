<?php

/** Defino o local onde esta a classe */

namespace src\controller\files;

/** importação de classe */

use src\controller\main\Main;

class FilesProcedures
{

    /** Declaro as variavéis da classe */
    private $path = null;
    private $destiny = null;
    private $dir = null;
    private $name = null;
    private $base64 = null;
    private $preferences = null;

    /**
     * Gera um arquivo a partir de uma string em formato base64 e o salva no caminho especificado.
     *
     * @param string $path O caminho onde o arquivo será salvo.
     * @param string $name O nome do arquivo a ser gerado.
     * @param string $base64 A string em formato base64 a ser convertida em arquivo.
     * @return void
     */

    /** Gera um novo arquivo enviado */
    public function generate(string $path, string $name, string $base64)
    {
        /** Define os parâmetros de entrada como 
         * propriedades da classe. */
        $this->path = $path;
        $this->name = $name;
        $this->base64 = str_replace(' ', '+', $base64);

        /** Verifica se a pasta especificada existe. */
        if (!is_dir($this->path)) {

            /** Cria a pasta recursivamente se ela não existir. */
            mkdir($this->path, 0777, true);
        }

        /** Gera um arquivo temporário com a permissão necessária. */
        file_put_contents($this->path . '/' . $this->name, $this->base64);

        /** Verifica se o arquivo foi 
         * gerado na pasta especificada */
        return file_exists($this->path . '/' . $this->name) ? true : false;
    }

    /**
     * Remove um arquivo.
     *
     * Esta função recebe o caminho e o nome do arquivo a ser removido e verifica se o arquivo existe.
     * Se o arquivo existir, ele é removido.
     *
     * @param string $file O caminho onde o arquivo está localizado.
     * @return void
     */
    public function remove(string $file): void
    {

        /** Verifico se existe o arquivo e se existir, remove o mesmo */
        if (file_exists($file)) {

            /** Remoção do arquivo */
            unlink($file);
        }
    }

    /**
     * Cria um diretório se ele não existir.
     *
     * Esta função verifica se o diretório especificado já existe. Se não existir,
     * o diretório é criado recursivamente.
     *
     * @param string $dir O caminho do diretório a ser criado.
     * @return void
     */
    public function createDir(string $dir): void
    {

        /** Verifica se a pasta especificada existe. */
        if (!is_dir($dir)) {

            /** Cria a pasta recursivamente se ela não existir. */
            mkdir($dir, 0777, true);
        }
    }

    /**
     * Combina o conteúdo de todos os arquivos em um diretório e salva em um novo arquivo.
     *
     * @param string $path O caminho do diretório onde os arquivos estão localizados.
     * @param string $name O nome do arquivo resultante da combinação.
     * @return object
     */
    public function merge(string $path, string $name, string $destiny): object
    {

        /** Define os parâmetros de entrada como propriedades da classe. */
        $result = null;
        $this->path = $path;
        $this->name = $name;
        $this->destiny = $destiny;

        /** Verifica se a pasta especificada existe. */
        $this->createDir($this->destiny);

        /** Verifico se o arquivo exist, se sim remove o mesmo */
        $this->remove($this->destiny . $this->name);

        /** Lista todos os arquivos existentes dentro do diretório. */
        $this->dir = scandir($this->path, SCANDIR_SORT_ASCENDING);

        /** Ordeno os valores presente dentro da array de forma alfabética */
        natcasesort($this->dir);

        /** Remoção das informações localizadas na primeira segunda posição da array '.' e '..'. */
        unset($this->dir[0]);
        unset($this->dir[1]);

        /** Reorganizo os indices da array */
        $this->dir = array_values($this->dir);

        /** Percorre todos os arquivos no diretório. */
        foreach ($this->dir as $key => $content) {

            // Abre o arquivo de destino em modo de escrita e leitura.
            $fp = fopen($this->destiny . $this->name, 'a+');

            // Escreve no arquivo aberto o conteúdo do arquivo atual.
            fwrite($fp, file_get_contents($this->path . '/' . $content));

            // Fecha o arquivo.
            fclose($fp);

            /** Verifico se devo decodificar o arquivo enviado */
            if (($key + 1) === count($this->dir)) {

                /** Obtenho os dados codificados do arquivo */
                $base64 = file_get_contents($this->destiny . $this->name);

                /** Realizo a quebra de linha para ficar dentro do padrão RFC 1421 */
                $base64 = chunk_split($base64, 64, "\r\n");

                /** Decodificação do base 64 */
                $base64 = base64_decode($base64, true);

                // Abre o arquivo de destino em modo de escrita e leitura.
                $fp = fopen($this->destiny . $this->name, 'w+');

                // Escreve no arquivo aberto o conteúdo do arquivo atual.
                fwrite($fp, $base64);

                // Fecha o arquivo.
                fclose($fp);

                /** Verifico se o arquivo foi gerado e se 
                 * devo remover a pasta temporária*/
                if (is_file($this->destiny . $this->name)) {

                    /** Instânciamento de classe */
                    $Main = new Main();

                    /** Remoção da pasta temporária */
                    $Main->deleteFolder($this->path);

                    // Result
                    $result = [

                        'code' => 200,
                        'data' => 'Arquivo gerado com sucesso',

                    ];
                } else {

                    // Result
                    $result = [

                        'code' => 0,
                        'data' => 'Não foi possível gerar o arquivo',

                    ];
                }
            }
        }

        /** Retorno da informação em formato de objeto */
        return (object)$result;
    }

    public function ConvertPngToJpg(string $path, string $name)
    {

        /** Carregar a imagem PNG */
        $png = imagecreatefrompng($path . '/' . $name);

        /** Crie uma nova imagem em branco com as mesmas dimensões */
        $pngToJpg = imagecreatetruecolor(imagesx($png), imagesy($png));

        /** Copiar a imagem PNG para a nova imagem em branco (isso converte para JPEG) */
        imagecopy($pngToJpg, $png, 0, 0, 0, 0, imagesx($png), imagesy($png));

        /** Caminho para a nova imagem JPEG */
        $newPath = str_replace('png', 'jpg', $path . '/' . $name);

        /** Salvar a nova imagem JPEG */
        imagejpeg($pngToJpg, $newPath, 100);

        /** Liberar memória */
        imagedestroy($png);
        imagedestroy($pngToJpg);
    }

    public function ResizeJPG(string $path, string $name, object $preferences)
    {

        // Carregar a imagem original
        $jpg = imagecreatefromjpeg($path . '/crop/' . $name);

        // Obter as dimensões da imagem original
        $jpgX = imagesx($jpg);
        $jpgY = imagesy($jpg);

        // Criar uma imagem vazia com as novas dimensões
        $jpgResized = imagecreatetruecolor($preferences->width, $preferences->height);

        // Redimensionar a imagem original para a nova imagem
        imagecopyresampled($jpgResized, $jpg, 0, 0, 0, 0, $preferences->width, $preferences->height, $jpgX, $jpgY);

        /** Verifica se a pasta especificada existe. */
        $this->createDir($path . '/' . $preferences->name);

        /** Verifica se o arquivo especificada existe. */
        $this->remove($path . '/' . $preferences->name . '/' . $name);

        // Salvar a nova imagem redimensionada
        imagejpeg($jpgResized, $path . '/' . $preferences->name . '/' . $name, $preferences->jpg);

        // Liberar a memória
        imagedestroy($jpg);
        imagedestroy($jpgResized);
    }

    public function CropJPG(string $path, string $name, object $preferences)
    {

        // Carrega a imagem original
        $jpg = imagecreatefromjpeg($path . '/' . $name);

        // Cria uma imagem vazia com o tamanho do recorte
        $jpgCropped = imagecreatetruecolor($preferences->width, $preferences->height);

        // Recorta a parte da imagem original especificada pelas coordenadas e tamanho
        imagecopyresampled($jpgCropped, $jpg, 0, 0, $preferences->x, $preferences->y, $preferences->width, $preferences->height, $preferences->width, $preferences->height);

        /** Verifica se a pasta especificada existe. */
        $this->createDir($path . '/crop/');

        /** Verifica se o arquivo especificada existe. */
        $this->remove($path . '/crop/' . $name);

        // Salva a imagem recortada
        imagejpeg($jpgCropped, $path . '/crop/' . $name);

        // Libera a memória
        imagedestroy($jpg);
        imagedestroy($jpgCropped);
    }
}
