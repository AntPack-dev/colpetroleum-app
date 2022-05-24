<?php

//PÁGINA DE REGISTRO DE REPORTE DE FALLAS

$id_user = $_SESSION['id_user'];

$mtto = new mtto();
$session = new UserFunctions();

$admin = new Admin();

$access_page = $admin->VerificPermitions($id_user, 5);

if(!$access_page == 1){ echo "<script> window.location='../pages/'; </script>"; };

$optionone = "SI";
$optiontwo = "NO";


if(isset($_POST['btnregisterfails']))
{
    $reference_teams = $mysqli->real_escape_string($_POST['reference_teams']);
    $analysis_data = $mysqli->real_escape_string($_POST['analysis_data']);
    $name_fails = $mysqli->real_escape_string($_POST['name_fails']);
    $date_fails = $mysqli->real_escape_string($_POST['date_fails']);
    $time_fails = $mysqli->real_escape_string($_POST['time_fails']);
    $cost_npt = $mysqli->real_escape_string($_POST['cost_npt']);
    $description_fails = $mysqli->real_escape_string($_POST['description_fails']);
    $impact_trab = $mysqli->real_escape_string($_POST['impact_trab']);
    $impact_ambiental = $mysqli->real_escape_string($_POST['impact_ambiental']);
    $state = 0;
    
    $token_report = $mtto->GenerateTokenMtto();

    //Consulta la referencia del equipo

    $letter_ref_teams = $mtto->getValueMtto('letter_units_teams','teams_units_rsu','id_teams_units', $reference_teams);
    $num_ref_teams = $mtto->getValueMtto('number_teams_units','teams_units_rsu','id_teams_units', $reference_teams);
    $name_teams = $mtto->getValueMtto('name_teams_units','teams_units_rsu','id_teams_units', $reference_teams);
    $id_unity = $mtto->getValueMtto('fk_id_father_teams_units','teams_units_rsu','id_teams_units', $reference_teams);

    $reference_teams_end = $letter_ref_teams."-".$num_ref_teams;

    //Consulta la referencia del analísis de costos

    $letter_analysis = $mtto->getValueMtto('letter_analysis_warehouse','analysis_data','id_analysis_data',$analysis_data);
    $number_analysis = $mtto->getValueMtto('num_analysis_data','analysis_data','id_analysis_data',$analysis_data);

    $num_analysis = $letter_analysis.$number_analysis;

    $num_report = $mtto->TopReportFails();


    $regreport = $mtto->InsertReportFails($num_report, $reference_teams_end, $num_analysis, $name_teams, $name_fails, $description_fails, $date_fails, $cost_npt, $time_fails, $impact_trab, $impact_ambiental, $id_user, $token_report, $id_unity);

    if($regreport > 0)
    {
        echo "<script> window.location='failsteams'; </script>";    
    }
    else
    {
        echo "<script> window.location='failsteams'; </script>";    
    }

    
}

?>

<section class="content">

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Gestión de fallas o avería</h5> 
            <div class="card-tools">
            <a href="../report/ReportF265" class="btn btn-danger btn-sm" target="_blank"><i class="fas fa-file-alt"></i> F-265</a>
            </div>           
          </div>              
            <div class="card-body">
                
                <table id="id_table_fails" class="display" style="width: 100%;">
                
                  <thead>                 
                    
                    <tr style="text-align: center;">
                      <th>Cod. Falla o Avería</th>
                      <th>Equipo</th>
                      <th>Referencia del equipo</th>                                                          
                      <th>Descripción</th>
                      <th>Promedio de parada</th>                     
                      <th>Riesgo a los trabajadores</th>                     
                      <th>Impacto al medio ambiente</th>                     
                      <th>Fecha de la falla</th>                     
                      <th>Analísis de costos relacionado</th> 
                      <th>Acción</th>                    
                    </tr>
                  </thead>
                  <tbody style="text-align: center;">
                 
                  
                  </tbody>
                
                </table>   
            
                <?php echo $session->AdminRegisterFails($tokenuser); ?>

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
        <h4 class="modal-title">Gestionar reporte de fallas</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        <div class="modal-body"> 
            <div class="callout callout-info">
              <h5>Pasos para registrar un reporte de fallas:</h5>

              <p>
                  1) Seleccionamos la referencia del equipo, en la casilla Referencia del equipo.<br>                                                           
                  2) Seleccionamos el concepto de análisis de costos, en la casilla Análisis de Costos.<br>
                  3) Escribimos en la casilla Componente que fallo, el nombre especifico del componente.<br>
                  4) Ingresamos en la casilla Fecha de la falla o avería, la fecha cuando se presento el daño.<br>
                  5) Escribimos en la casilla Tiempo de parada, las horas relacionadas a la falla.<br>
                  6) Escribimos en la casilla Costo NPT, el costo por número de tiempo parado. <b>NOTA:</b> El número debe <b>NO</b> debe ir separado por puntos ni comas.<br>
                  7) Escribimos en la casilla Descripción de la falla, los detalles de la falla.<br>
                  8) Seleccionamos SI o NO, en la casilla ¿Presenta riesgo a los trabajadores?.<br>
                  9) Seleccionamos SI o NO, en la casilla ¿Presenta impacto ambiental?.<br>
                  10) Presionamos sobre el botón <b>Guardar</b>.
                    
              </p>
            </div>  

          <div class="row">
             
            
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Registrar fallo o avería</h5>            
                </div>              
                <div class="card-body">  

                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">

                    <div class="row">

                        <div class="form-group col-6">
                            <label for="inputSuccess">Referencia del equipo:<b style="color:#B20F0F;">*</b></label>
                            <select name="reference_teams" size="10" class="form-control">          
                                
                                <?php echo $mtto->OptionsTeams(); ?>

                            </select>
                        </div>
                        <div class="form-group col-6">
                            <label for="inputSuccess">Analísis de costos:<b style="color:#B20F0F;">*</b></label>
                            <select name="analysis_data" size="10" class="form-control">                    
                                <?php echo $mtto->OptionsAnalysis(); ?>
                            </select>
                        </div>

                    </div>

                    <div class="row">

                        <div class="form-group col-3">
                            <label>Componente que falló<b style="color:#B20F0F;">*</b></label>
                            <input type="text" class="form-control" name="name_fails" required>
                        </div>
                        <div class="form-group col-3">
                            <label>Fecha de la falla o avería<b style="color:#B20F0F;">*</b></label>
                            <input type="date" class="form-control" name="date_fails" required>
                        </div>  
                        
                        <div class="form-group col-3">
                            <label>Tiempo de parada (Hrs)<b style="color:#B20F0F;">*</b></label>
                            <input type="number" class="form-control" name="time_fails" required>
                        </div> 
                        <div class="form-group col-3">
                            <label>Costo NPT<b style="color:#B20F0F;">*</b></label>
                            <input type="number" class="form-control" name="cost_npt" required>
                        </div> 

                        <div class="form-group col-12">
                            <label>Descripción de la falla<b style="color:#B20F0F;">*</b></label>
                            <input type="text" class="form-control" name="description_fails" required>
                        </div>
                        <div class="form-group col-3">
                            <label for="inputSuccess">¿Presenta riesgo a los trabajdores?<b style="color:#B20F0F;">*</b></label>
                            <select name="impact_trab" class="form-control">   
                                <option value="<?php echo $optionone; ?>">Sí</option>                 
                                <option value="<?php echo $optiontwo; ?>">No</option>                 

                            </select>
                        </div>

                        <div class="form-group col-3">
                            <label for="inputSuccess">¿Presenta impacto ambiental?<b style="color:#B20F0F;">*</b></label>
                            <select name="impact_ambiental" class="form-control">                    
                                <option value="<?php echo $optionone; ?>">Sí</option>                 
                                <option value="<?php echo $optiontwo; ?>">No</option> 
                            </select>
                        </div>

                    </div>

                </div>            
              </div>           
            </div>


        </div>
          
        
          
          
    
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-success" name="btnregisterfails">Guardar</button>
        </div>

        </form>     

      
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>