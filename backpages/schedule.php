<?php

//PÁGINA CON LA TABLA DE LISTADO DE CRONOGRAMAS REGISTRADOS ANUALMENTE

$mtto = new mtto();
$session = new UserFunctions();
$admin = new Admin();

$access_page = $admin->VerificPermitions($id_user, 8);

if(!$access_page == 1){ echo "<script> window.location='../pages/'; </script>"; };


$year = date('Y');


?>

<section class="content">

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Historial cronograma</h5>
            <?php echo $session->AdminSchedule($tokenuser); ?>
                                
          </div>              
            <div class="card-body"> 

            <table id="id_table_schedule" class="display responsive nowrap">
                <thead style="text-align: center;">
                    <th>Cronograma</th>
                    <th>Fecha de registro</th>
                    <th>Año</th>
                    <th>Acción</th>
                </thead>
                <tbody style="text-align: center;">
                 
                </tbody>

            </table>
            
      

            </div>            
        </div>           
      </div>          
    </div>
  </div>

</section>


      
