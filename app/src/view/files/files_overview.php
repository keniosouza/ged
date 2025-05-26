<?php

/** Importação de classes */
use vendor\model\Files;

/** Instânciamento de classes */
$Files = new Files();

/** Consulta os usuário cadastrados*/
$FilesAllNoFilteredResult = $Files->AllLast(150);

/**
 * Lista todas as extensões de arquivos em um diretório e seus subdiretórios, junto com a contagem de arquivos para cada extensão.
 *
 * @param string $diretorio Caminho do diretório base para iniciar a busca.
 * @return array Um array associativo com as extensões dos arquivos como chaves e a quantidade de arquivos como valores.
 */
function listarExtensoesDeArquivosComContagem($diretorio) {
    $diretorio = realpath($diretorio);
    if ($diretorio === false) {
        throw new Exception("Diretório não encontrado: $diretorio");
    }

    $diretorioIterator = new RecursiveDirectoryIterator($diretorio, RecursiveDirectoryIterator::SKIP_DOTS);
    $iterator = new RecursiveIteratorIterator($diretorioIterator);

    $extensoes = [];

    foreach ($iterator as $fileinfo) {
        if ($fileinfo->isFile()) {
            $extensao = strtolower(pathinfo($fileinfo->getFilename(), PATHINFO_EXTENSION));
            if (!empty($extensao)) {
                if (!isset($extensoes[$extensao])) {
                    $extensoes[$extensao] = ['contagem' => 0, 'tamanho' => 0];
                }
                $extensoes[$extensao]['contagem']++;
                $extensoes[$extensao]['tamanho'] += $fileinfo->getSize();  // Adiciona o tamanho do arquivo atual ao total
            }
        }
    }

    return $extensoes;
}

// Uso da função
try {

    $extensoes = listarExtensoesDeArquivosComContagem("document/");

    $total = 0;

    foreach ($extensoes as $extensao => $dados) {

        $total = $total+$dados['tamanho'];
    }


} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}

?>

<div class="col-md-3 custom-scrollbar" style="max-height: 631px">

    <div class="card">

        <div class="card-body">

            <h3 class="card-title">

                <?php echo $Main->formatFileSize($total);?>

            </h3>

            <?php

            foreach ($extensoes as $extensao => $dados) {?>

                <div class="d-flex align-items-center mb-1">

                    <div class="flex-shrink-0">

                        <div class="bg-light p-1 border rounded">

                            <img src="image/default/files/<?php echo $extensao?>.png" width="40px">

                        </div>

                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h6 class="mb-0 fw-bold">

                            <?php echo $extensao?>

                        </h6>

                        <?php echo $dados['contagem']?> Arquivos

                    </div>

                    <div class="flex-shrink-0">

                        <h5 class="mb-0">

                            <span class="badge bg-primary">

                                <?php echo $Main->formatFileSize($dados['tamanho'])?>

                            </span>

                        </h5>

                    </div>

                </div>

            <?php }?>

        </div>

    </div>

</div>

<div class="col-md-9">

   <div class="row g-1">

       <div class="col-md-6">

           <div class="card">

               <div class="card-body">

                   <img src="image/default/ticket.png" class="img-fluid" width="40px">

                   <h4 class="card-title mb-0">

                       Chamados

                   </h4>

                   <h5 class="mb-0 fw-normal">

                       <?php

                       $total = 0;

                       foreach (listarExtensoesDeArquivosComContagem("document/calls/") as $extensao => $dados) {

                           $total = $total+$dados['tamanho'];
                       }

                       echo $Main->formatFileSize($total);

                       ?>

                   </h5>

               </div>

           </div>

       </div>

       <div class="col-md-6">

           <div class="card">

               <div class="card-body">

                   <img src="image/default/product.png" class="img-fluid" width="40px">

                   <h4 class="card-title mb-0">

                       Produtos

                   </h4>

                   <h5 class="mb-0 fw-normal">

                       <?php

                       $total = 0;

                       foreach (listarExtensoesDeArquivosComContagem("document/products/") as $extensao => $dados) {

                           $total = $total+$dados['tamanho'];
                       }

                       echo $Main->formatFileSize($total);

                       ?>

                   </h5>

               </div>

           </div>

       </div>

       <div class="col-md-12 custom-scrollbar" style="max-height: 500px">

           <table class="table border rounded">

               <thead>

                   <tr>

                       <th scope="col" class="text-center">

                           #

                       </th>

                       <th scope="col">

                           Extensão

                       </th>

                       <th scope="col">

                           Nome

                       </th>

                       <th scope="col" class="text-center">

                           Operações

                       </th>

                   </tr>

               </thead>

               <tbody>

               <?php

               /** Listo todos os registros de produtos */
               foreach ($FilesAllNoFilteredResult as $key => $result) {?>

                   <tr>

                       <td class="text-center">

                           <?php echo $result->file_id?>

                       </td>

                       <td>

                           <img src="image/default/files/<?php echo pathinfo($result->path.'/'.$result->name, PATHINFO_EXTENSION)?>.png" width="30px">

                       </td>

                       <td>

                           <?php echo $result->name?>

                       </td>

                       <td class="text-center">

                           <div class="btn-group" role="group" aria-label="Basic example">

                               <a href="<?php echo $result->path . '/' . $result->name ?>" class="btn btn-primary" download>

                                   <i class="bi bi-download"></i>

                               </a>

                               <button type="button" class="btn btn-primary" onclick="new Preview({size : 'lg', title : '<?php echo $result->name?>', path : '<?php echo $result->path . '/' . $result->name ?>'})">

                                   <i class="bi bi-eye"></i>

                               </button>

                           </div>

                       </td>

                   </tr>

               <?php }?>

               </tbody>

           </table>

       </div>

   </div>

</div>