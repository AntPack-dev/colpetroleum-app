<?php

//PÁGINA MOSTRAR CRONOGRAMA DE MANTENIMIENTO ANUAL

$mtto = new mtto();
$admin = new Admin();

$access_page = $admin->VerificPermitions($id_user, 9);

if(!$access_page == 1){ echo "<script> window.location='../pages/'; </script>"; };


$year = date('Y');

$schedule = $mysqli->real_escape_string($_GET['schedule']);

$id_schedule = $mtto->getValueMtto('id_schedule_mant', 'maintenance_schedule', 'token_echedule_mant', $schedule);
$name_schedule = $mtto->getValueMtto('description_schedule_mant', 'maintenance_schedule', 'token_echedule_mant', $schedule);
$year_schedule = $mtto->getValueMtto('year_echedule_mant', 'maintenance_schedule', 'token_echedule_mant', $schedule);
?>

<section class="content">

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title"><?php echo $name_schedule; ?></h5> 
       
                      
          </div>              
            <div class="card-body"> 

                <div class="table-responsive">
                    
                <?php echo $mtto->SearchScheduleLast($id_schedule, $year_schedule, $schedule); ?>              
                
                    
                </div>

                

            </div>            
        </div>           
      </div>          
    </div>
  </div>

</section>

