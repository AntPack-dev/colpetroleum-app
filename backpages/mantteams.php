<?php

//PÁGINA PARA REGISTRAR REPORTES DE MANTENIMIENTOS

$id_user = $_SESSION['id_user'];

$mtto = new mtto();
$session = new UserFunctions();

$admin = new Admin();

$access_page = $admin->VerificPermitions($id_user, 6);
if(!$access_page == 1){ echo "<script> window.location='../pages/'; </script>"; };


if(isset($_POST['btnregistermaint']))
{
    $reference_teams = $mysqli->real_escape_string($_POST['reference_teams']);
    $analysis_data = $mysqli->real_escape_string($_POST['analysis_data']);
    $report_fails = $mysqli->real_escape_string($_POST['report_fails']);
    $type_maint = $mysqli->real_escape_string($_POST['maint']);
    $date_reg_mant = $mysqli->real_escape_string($_POST['date_reg_mant']);
    $name_machine_maint = $mysqli->real_escape_string($_POST['name_machine_maint']);
    $location_maint = $mysqli->real_escape_string($_POST['location_maint']);
    $description_maint = $mysqli->real_escape_string($_POST['description_maint']);

    $number_teams = $mtto->getValueMtto('number_teams_units', 'teams_units_rsu', 'id_teams_units', $reference_teams);
    $letter_teams = $mtto->getValueMtto('letter_units_teams', 'teams_units_rsu', 'id_teams_units', $reference_teams);
    $name_teams = $mtto->getValueMtto('name_teams_units', 'teams_units_rsu', 'id_teams_units', $reference_teams);

    $letter_analysis = $mtto->getValueMtto('letter_analysis_warehouse', 'analysis_data', 'id_analysis_data', $analysis_data);
    $number_analysis = $mtto->getValueMtto('num_analysis_data', 'analysis_data', 'id_analysis_data', $analysis_data);
    $total_analysis = $mtto->getValueMtto('total_analysis_data', 'analysis_data', 'id_analysis_data', $analysis_data);



    $num_report_fails = $mtto->getValueMtto('num_report_fails', 'report_fails', 'id_report_fails', $report_fails);

    $analysis = $letter_analysis."".$number_analysis;


    $reference = $letter_teams."-".$number_teams;

    $token_mant = $mtto->GenerateTokenMtto();

    $number = $mtto->TopReportMaint();
   
    $state_asign = 0;


    $reg = $mtto->InsertMaintReport($token_mant, $number, $type_maint, $reference, $name_teams, $description_maint, $location_maint, $name_machine_maint, $analysis, $num_report_fails, $date_reg_mant, $reference_teams, $total_analysis, $state_asign);

    if($reg > 0)
    {
      echo "<script> window.location='mantteams'; </script>";    
    }
    else
    {
      echo "<script> window.location='mantteams'; </script>";    
    }
    
}

?>

<section class="content">

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Gestión de mantenimientos</h5>    
            <div class="card-tools">
            <a href="../report/ReportF266" class="btn btn-danger btn-sm" target="_blank"><i class="fas fa-file-alt"></i> F-266</a>
            </div>         
          </div>              
            <div class="card-body"> 
              <table id="id_table_maint" class="display" style="width: 100%;">
                
                <thead>                 
                  
                  <tr style="text-align: center;">
                    <th>No. de mantenimiento</th>
                    <th>Tipo de mantenimiento</th>
                    <th>Lugar</th>                                                          
                    <th>Referencia del equipo</th>
                    <th>Nombre del equipo</th>                     
                    <th>Código de reporte de falla</th>                     
                    <th>Descripción de mantenimiento</th>                     
                    <th>Nombre del mecanico que realiza el mantenimiento</th>                     
                    <th>No. Analísis de costos</th> 
                    <th>Fecha</th>                    
                    <th>Acción</th>                    
                  </tr>
                </thead>
                <tbody style="text-align: center;">
               
                
                </tbody>
              
              </table>   

              <?php echo $session->AdminRegisterMant($tokenuser); ?>
              
              <!-- <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default">
                Registrar Mantenimiento
              </button> -->
            

            </div>            
        </div>           
      </div>          
    </div>
  </div>

</section>


<div class="modal fade" id="modal-default">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Gestionar mantenimiento</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        <div class="modal-body"> 
          
            <div class="callout callout-info">
              <h5>Pasos para registrar un reporte de mantenimiento:</h5>

              <p>
                  1) Seleccionamos la referencia del equipo, en la casilla Referencia del equipo.<br>                                                           
                  2) Seleccionamos el concepto de análisis de costos, en la casilla Análisis de Costos.<br>
                  3) Seleccionamos el concepto de reporte de falla, en la casilla Reporte de falla. <b>NOTA:</b> Este reporte aplica si el mantenimiento es <b>Correctivo</b>.<br>
                  4) Seleccionamos el tipo de mantenimiento, en la casilla Tipo de mantenimiento.<br>
                  5) Escribimos en la casilla Nombre del mecánico, el nombre completo del mismo.<br>                
                  6) Escribimos en la casilla Fecha de registro, la fecha del reporte de mantenimiento.<br>
                  7) Escribimos en la casilla Lugar, la ubicación donde se generó el mantenimiento.<br>
                  8) Escribimos en la casilla Descripción del mantenimiento, los detalles del mismo.<br>                
                  9) Presionamos sobre el botón <b>Guardar</b>.
                    
              </p>
            </div>

          <div class="row">
             
            
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Registrar mantenimiento Preventivo/Correctivo</h5>            
                </div>              
                <div class="card-body">  

                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">

                    <div class="row">

                        <div class="form-group col-6">
                            <label for="inputSuccess">Referencia del equipo:<b style="color:#B20F0F;">*</b></label>
                            <select name="reference_teams" size="10" class="form-control" required>          
                                
                                <?php echo $mtto->OptionsTeams(); ?>

                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label for="inputSuccess">Analísis de costos:</label>
                            <select name="analysis_data" size="10" class="form-control" required>                    
                                <?php echo $mtto->OptionsAnalysis(); ?>
                            </select>
                        </div>

                        <div class="form-group col-6">
                            <label for="inputSuccess">Reporte de falla:</label>
                            <select id="id_report_fails" name="report_fails" size="10" class="form-control" disabled>                    
                                <?php echo $mtto->OptionsReportFails(); ?>
                            </select>
                        </div>

                      

                        <div class="form-group col-3">
                            <label>Tipo de mantenimiento<b style="color:#B20F0F;">*</b></label>
                            <select id="id_mant" name="maint" class="form-control" required>
                                <option value="Preventivo">Preventivo</option>
                                <option value="Correctivo">Correctivo</option>
                            </select>

                            <label>Fecha de registro<b style="color:#B20F0F;">*</b></label>
                            <input type="date" class="form-control" name="date_reg_mant" required>

                            
                        </div>
                        <div class="form-group col-3">
                            <label>Nombre del mecánico<b style="color:#B20F0F;">*</b></label>
                            <input type="text" class="form-control" name="name_machine_maint" required>

                            <label>Lugar<b style="color:#B20F0F;">*</b></label>
                            <input type="text" class="form-control" name="location_maint" required>

                            
                        </div>                     

                        
                        

                    </div> 

                    <div class="row">                       
                       

                        <div class="form-group col-12">
                            <label>Descripción del mantenimiento<b style="color:#B20F0F;">*</b></label>
                            <input type="text" class="form-control" name="description_maint" required>
                        </div>
                       

                    </div>

                </div>            
              </div>           
            </div>


        </div>
          
        
          
          
    
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-success" name="btnregistermaint">Guardar</button>
        </div>

        </form>     

      
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>