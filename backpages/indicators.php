<?php

//ESTE ARCHIVO NO SE ENCUENTRA EN SERVICIO
//PÁGINA PARA REGISTRAR INDICADORES*

$mtto = new mtto();
$session = new UserFunctions();

$admin = new Admin();

$access_page = $admin->VerificPermitions($id_user, 9);

if(!$access_page == 1){ echo "<script> window.location='../pages/'; </script>"; };


$year = date('Y');

if(isset($_POST['btnregitserindicator'])) 
{
  $des_ind = $mysqli->real_escape_string($_POST['description_indicator']);
  $tk_ind_anual = $mtto->GenerateTokenMtto();
  $datereg_ind = $mtto->DateMtto();

  $id_reg = $mtto->RegisterIndicatorAnual($tk_ind_anual, $datereg_ind, $des_ind, $year);
  $reg_Indicators = $mtto->RegisterIndicatorsFrequency($id_reg);

  if($reg > 0)
  {
    echo "<script> window.location='indicators'; </script>";
  }
  else
  {
    echo "<script> window.location='indicators'; </script>";
  }
    
}




?>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Historial Indicadores</h5> 
            <?php echo $session->AdminIndicators($tokenuser); ?>
            <!-- <div class="card-tools">
          
                  <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#modal-default">
                  <i class="far fa-clipboard"></i> Generar Nuevo Indicador Anual
                  </button>                  
                  
            </div>    -->
                      
          </div>              
            <div class="card-body"> 

            <table id="id_table_indicadors" class="display responsive nowrap">
                <thead style="text-align: center;">
                    <th>Indicador</th>
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

<div class="modal fade" id="modal-default">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Generar indicador anual</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="" method="POST">
              <div class="modal-body">

                  <div class="callout callout-info">
                    <h5>Pasos para registrar un nuevo esquema de indicadores:</h5>

                    <p>                                                                             
                        1) Escribimos la siguiente descripción: Indicador -. Y el año del indicador. <b>Ejemplo:</b> Indicador - 2023<br>            
                        2) Presionamos sobre el botón Generar.<br>
                        <b>NOTA: </b>Solo puede registrar un esquema de indicadores al año, si registra más de un (1) esquema de indicadores, generará
                        una confusión al momento de mostrar los indicadores.
                          
                    </p>
                  </div>

                <div class="row">
                  
                  <div class="input-group mb-2 col-sm-12">
                      <div class="input-group-prepend">
                          <span style="background-color: #F8F9F9;"  class="input-group-text"><i class="far fa-clipboard"></i></span>
                      </div>
                      <input type="text" class="form-control" name="description_indicator" placeholder="Ej: Indicador - 2022" required>
                  </div>

                    
                </div>
         
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="input" name="btnregitserindicator" class="btn btn-success">Generar</button>
              </div>

            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>


      
