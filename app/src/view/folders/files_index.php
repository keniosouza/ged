<?php

/** Importação de classes */
use src\controller\main\Main;
use src\model\Files;
use src\model\Folders;

/** Instânciamento de classes */
$Main = new Main();
$Files = new Files();
$Folders = new Folders();

/** Busco todos os usuários */
$FilesAllResult = $Files->All();
$FilesCountResult = $Files->Count();
$FoldersAlResult = $Folders->All();

?>

<div class="d-flex justify-content-between align-items-start mb-2">

  <div class="text-body">

    <h6 class="text-body-secondary fw-medium">

      Arquivos /

    </h6>

    <h5 class="fw-bold text-body">

      Visão Geral

    </h5>

  </div>

</div>

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button"
      role="tab" aria-controls="home-tab-pane" aria-selected="true">Home</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button"
      role="tab" aria-controls="profile-tab-pane" aria-selected="false">Arquivos</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button"
      role="tab" aria-controls="contact-tab-pane" aria-selected="false">Atividade</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#disabled-tab-pane" type="button"
      role="tab" aria-controls="disabled-tab-pane" aria-selected="false" disabled>Disabled</button>
  </li>
</ul>
<div class="tab-content" id="myTabContent">

  <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">

    <div class="row g-2 my-3">

      <div class="col-md-12">

        <div class="card">

          <div class="card-body">

            <div class="progress-stacked" style="height:7px;">

              <?php

              /** Percorro todos os itens localizados */
              foreach ($Files->AllExtensions() as $key => $result) { ?>

                <div class="progress" role="progressbar" aria-label="Segment one" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($result->count / $FilesCountResult->count) * 100 ?>%;">

                  <div class="progress-bar <?php echo $Main->getRandomBgClass(); ?>"></div>

                </div>

              <?php }?>

            </div>

          </div>

        </div>
      
      </div>

      <div class="card">

        <div class="card-body">

         <!-- Prepare a DOM with a defined width and height for ECharts -->
    <div id="main" style="width: 100%;height:400px;"></div>
    <script type="text/javascript">
var chartDom = document.getElementById('main');
var myChart = echarts.init(chartDom);
var option;

option = {
  xAxis: {
    type: 'category',
    data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
  },
  yAxis: {
    type: 'value'
  },
  series: [
    {
      data: [820, 932, 901, 934, 1290, 1330, 1320],
      type: 'line',
      smooth: true
    }
  ]
};

      // Display the chart using the configuration items and data just specified.
      myChart.setOption(option);
    </script>

        </div>

      </div>

    </div>

  </div>

  <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">

    <div class="row g-2 my-3">

      <div class="col-md-2">

        <div class="card cursor-pointer card-hover border-dashed shadow-sm" onclick='new Request({"request" : {"path" : "view/files/files_form_2"}})'>

          <div class="card-body">

            <i class="bi bi-upload"></i>

            <br>

            Enviar

          </div>

        </div>

      </div>

      <div class="col-md-2">

        <div class="card cursor-pointer card-hover border-dashed shadow-sm" onclick='new Request({"request" : {"path" : "view/folders/folders_form"}})'>

          <div class="card-body">

            <i class="bi bi-folder-plus"></i>

            <br>

            Criar Pasta

          </div>

        </div>

      </div>

    </div>

    <div class="row g-2 mb-3">

      <div class="col-md-12">

        <div class="fs-5 fw-bold">

          Pastas

        </div>

      </div>

      <?php

      /** Percorro todos os itens localizados */
      foreach ($FoldersAlResult as $key => $result) { ?>

        <div class="col-md-2 d-flex">

          <div class="card w-100 card-hover">

            <div class="card-header bg-transparent border-0">

              <div class="d-flex justify-content-between align-items-start">

                <img src="assets/img/default/folder.png" width="25px" class="img-fluid">

                <span class="cursor-pointer">

                  <i class="bi bi-three-dots-vertical"></i>

                </span>

              </div>

            </div>

            <div class="card-body cursor-pointer" onclick='new Request({"request" : {"path" : "view/folders/folders_details"}, "params" : {"folder_id" : <?php echo $result->folder_id?>}});'>

              <div class="fs-5 fw-normal">

                <?php echo $result->name; ?>

              </div>

            </div>

          </div>

        </div>

      <?php }?>

    </div>

    <div class="col-md-12">

      <div class="fs-5 fw-bold">

        Arquivos

      </div>

    </div>

    <table class="table table-borderless border align-items-center shadow-sm">

      <thead>

        <tr>

          <th scope="col" class="text-center">

            #

          </th>

          <th scope="col">

            Nome/Email

          </th>

          <th scope="col" class="text-center">

            Operações

          </th>

        </tr>

      </thead>

      <tbody>

        <?php

        /** Percorro todos os itens localizados */
        foreach ($FilesAllResult as $key => $result) { ?>

          <tr class="align-middle border-top">

            <th scope="row" class="text-center">

              <?php echo $result->file_id ?>

            </th>

            <td>

              <div class="d-flex align-items-center">

                <div class="flex-shrink-0">

                  <img src="<?php echo $Main->GetExtensionIcon($Main->getFileExtension($result->name)); ?>" width="40px" class="img-fluid">

                </div>

                <div class="flex-grow-1 ms-3">

                  <div class="fs-6 fw-bold">

                    <?php echo $result->name ?>

                  </div>

                  <div class="fs-6 fw-normal">

                    01/01/2021 / <?php echo $Main->GetFileSizeFormated($result->path . '/' . $result->name)?>

                  </div>

                </div>

              </div>

            </td>

            <td class="text-center">

              <div class="dropdown">

                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">

                  <i class="bi bi-three-dots"></i>
                  
                </button>

                <ul class="dropdown-menu">

                  <li>
                    
                    <a class="dropdown-item" href="#">
                      
                      Editar
                    
                    </a>
                  
                  </li>

                  <li>
                    
                    <a class="dropdown-item" onclick='new OffCanva({"title" : "<?php echo $result->name ?>", "request" : "view/files/files_details", "params" : {"file_id" : <?php echo $result->file_id ?>}})'>
                      
                      Detalhes
                    
                    </a>
                  
                  </li>

                  <li>
                    
                    <a class="dropdown-item" onclick='new Request({"request" : {"path" : "view/files/files_form_folder"}, "params" : {"file_id" : <?php echo $result->file_id ?>}})'>
                      
                      Pasta
                    
                    </a>
                  
                  </li>

                </ul>

              </div>

            </td>

          </tr>

        <?php } ?>

      </tbody>

    </table>
  </div>
  <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
    
          <div class="card">  

            <div class="card-body">

            <ul class="step">
      <!-- Step Item -->
      <li class="step-item">
        <div class="step-content-wrapper">
          <small class="step-divider">Today</small>
        </div>
      </li>
      <!-- End Step Item -->

      <!-- Step Item -->
      <li class="step-item">
        <div class="step-content-wrapper">
          <div class="step-avatar">
            <img class="step-avatar" src="./assets/img/160x160/img9.jpg" alt="Image Description">
          </div>

          <div class="step-content">
            <h5 class="mb-1">Iana Robinson</h5>

            <p class="fs-5 mb-1">Uploaded weekly reports to the task <a class="text-uppercase" href="#"><i
                  class="bi-journal-bookmark-fill"></i> Fd-7</a></p>

            <ul class="list-group list-group-sm">
              <!-- List Item -->
              <li class="list-group-item list-group-item-light">
                <div class="row gx-1">
                  <div class="col-sm-4">
                    <!-- Media -->
                    <div class="d-flex">
                      <div class="flex-shrink-0">
                        <img class="avatar avatar-xs" src="./assets/svg/brands/excel-icon.svg" alt="Image Description">
                      </div>
                      <div class="flex-grow-1 text-truncate ms-2">
                        <span class="d-block fs-6 text-dark text-truncate"
                          title="weekly-reports.xls">weekly-reports.xls</span>
                        <span class="d-block small text-muted">12kb</span>
                      </div>
                    </div>
                    <!-- End Media -->
                  </div>
                  <!-- End Col -->

                  <div class="col-sm-4">
                    <!-- Media -->
                    <div class="d-flex">
                      <div class="flex-shrink-0">
                        <img class="avatar avatar-xs" src="./assets/svg/brands/word-icon.svg" alt="Image Description">
                      </div>
                      <div class="flex-grow-1 text-truncate ms-2">
                        <span class="d-block fs-6 text-dark text-truncate"
                          title="weekly-reports.xls">weekly-reports.xls</span>
                        <span class="d-block small text-muted">4kb</span>
                      </div>
                    </div>
                    <!-- End Media -->
                  </div>
                  <!-- End Col -->

                  <div class="col-sm-4">
                    <!-- Media -->
                    <div class="d-flex">
                      <div class="flex-shrink-0">
                        <img class="avatar avatar-xs" src="./assets/svg/brands/word-icon.svg" alt="Image Description">
                      </div>
                      <div class="flex-grow-1 text-truncate ms-2">
                        <span class="d-block fs-6 text-dark text-truncate"
                          title="monthly-reports.xls">monthly-reports.xls</span>
                        <span class="d-block small text-muted">8kb</span>
                      </div>
                    </div>
                    <!-- End Media -->
                  </div>
                  <!-- End Col -->
                </div>
                <!-- End Row -->
              </li>
              <!-- End List Item -->
            </ul>
          </div>
        </div>
      </li>
      <!-- End Step Item -->

      <!-- Step Item -->
      <li class="step-item">
        <div class="step-content-wrapper">
          <span class="step-icon step-icon-soft-dark">B</span>

          <div class="step-content">
            <h5 class="mb-1">Bob Dean</h5>

            <p class="fs-5 mb-1">Marked project status as <span
                class="badge bg-soft-primary text-primary rounded-pill"><span
                  class="legend-indicator bg-primary"></span>"In progress"</span></p>
          </div>
        </div>
      </li>
      <!-- End Step Item -->

      <!-- Step Item -->
      <li class="step-item">
        <div class="step-content-wrapper">
          <small class="step-divider">Yesterday</small>
        </div>
      </li>
      <!-- End Step Item -->

      <!-- Step Item -->
      <li class="step-item">
        <div class="step-content-wrapper">
          <div class="step-avatar">
            <img class="step-avatar-img" src="./assets/img/160x160/img3.jpg" alt="Image Description">
          </div>

          <div class="step-content">
            <h5 class="h5 mb-1">Crane</h5>

            <p class="fs-5 mb-1">Added 5 card to <a href="#">Payments</a></p>

            <ul class="list-group list-group-sm">
              <li class="list-group-item list-group-item-light">
                <div class="row gx-1">
                  <div class="col">
                    <img class="img-fluid rounded" src="./assets/svg/components/card-1.svg" alt="Image Description">
                  </div>
                  <div class="col">
                    <img class="img-fluid rounded" src="./assets/svg/components/card-2.svg" alt="Image Description">
                  </div>
                  <div class="col">
                    <img class="img-fluid rounded" src="./assets/svg/components/card-3.svg" alt="Image Description">
                  </div>
                  <div class="col-auto align-self-center">
                    <div class="text-center">
                      <a href="#">+2</a>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </li>
      <!-- End Step Item -->

      <!-- Step Item -->
      <li class="step-item">
        <div class="step-content-wrapper">
          <span class="step-icon step-icon-soft-info">D</span>

          <div class="step-content">
            <h5 class="mb-1">David Lidell</h5>

            <p class="fs-5 mb-1">Added a new member to Front Dashboard</p>
          </div>
        </div>
      </li>
      <!-- End Step Item -->

      <!-- Step Item -->
      <li class="step-item">
        <div class="step-content-wrapper">
          <div class="step-avatar">
            <img class="step-avatar-img" src="./assets/img/160x160/img7.jpg" alt="Image Description">
          </div>

          <div class="step-content">
            <h5 class="mb-1">Rachel King</h5>

            <p class="fs-5 mb-1">Earned a "Top endorsed" <i class="bi-patch-check-fill text-primary"></i> badge</p>
          </div>
        </div>
      </li>
      <!-- End Step Item -->

      <!-- Step Item -->
      <li class="step-item">
        <div class="step-content-wrapper">
          <small class="step-divider">Last week</small>
        </div>
      </li>
      <!-- End Step Item -->

      <!-- Step Item -->
      <li class="step-item">
        <div class="step-content-wrapper">
          <div class="step-avatar">
            <img class="step-avatar-img" src="./assets/img/160x160/img6.jpg" alt="Image Description">
          </div>

          <div class="step-content">
            <h5 class="mb-1">
              <a class="text-dark" href="#">Mark Williams</a>
            </h5>

            <p class="fs-5">Attached two files.</p>

            <ul class="list-group list-group-sm">
              <!-- List Item -->
              <li class="list-group-item list-group-item-light">
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <i class="bi-paperclip"></i>
                  </div>
                  <div class="flex-grow-1 text-truncate ms-2">
                    <span class="d-block text-dark text-truncate">Requirements.figma</span>
                    <span class="d-block small">8mb</span>
                  </div>
                </div>
              </li>
              <!-- End List Item -->

              <!-- List Item -->
              <li class="list-group-item list-group-item-light">
                <div class="d-flex">
                  <div class="flex-shrink-0">
                    <i class="bi-paperclip"></i>
                  </div>
                  <div class="flex-grow-1 text-truncate ms-2">
                    <span class="d-block text-dark text-truncate">Requirements.sketch</span>
                    <span class="d-block small">4mb</span>
                  </div>
                </div>
              </li>
              <!-- End List Item -->
            </ul>
          </div>
        </div>
      </li>
      <!-- End Step Item -->

      <!-- Step Item -->
      <li class="step-item">
        <div class="step-content-wrapper">
          <span class="step-icon step-icon-soft-primary">C</span>

          <div class="step-content">
            <h5 class="mb-1">
              <a class="text-dark" href="#">Costa Quinn</a>
            </h5>

            <p class="fs-5">Marked project status as <span class="badge bg-soft-primary text-primary rounded-pill"><span
                  class="legend-indicator bg-primary"></span>"In progress"</span></p>
          </div>
        </div>
      </li>
      <!-- End Step Item -->
    </ul>

            </div>

          </div>

  </div>
  <div class="tab-pane fade" id="disabled-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">...
  </div>

</div>