<?php

//ESTE ARCHIVO NO SE ENCUENTRA EN SERVICIO
//VISUALIZACIONES DE INDICADORES

$mtto = new mtto();
$admin = new Admin();

$access_page = $admin->VerificPermitions($id_user, 9);

if(!$access_page == 1){ echo "<script> window.location='../pages/'; </script>"; };

$indicator = $mysqli->real_escape_string($_GET['indicators']);

$name_indicator = $mtto->getValueMtto('description_history_indicators', 'history_indicators', 'token_history_indicators', $indicator);
$id_history_indicator = $mtto->getValueMtto('id_history_indicators', 'history_indicators', 'token_history_indicators', $indicator);


?>

<section class="content">

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title"><?php echo $name_indicator; ?></h5> 
            
                      
          </div>              
            <div class="card-body"> 

                <div class="table-responsive">

                    <?php echo $mtto->SearchMeta($id_history_indicator, $indicator); ?>
                    
                </div>

                <div class="table-responsive">

                    <?php echo $mtto->SearchIndicatorValues($id_history_indicator, $indicator); ?>                  

                </div>

            </div>            
        </div>           
      </div>          
    </div>
  </div>

</section>

