<?php

//PÁGINA DE GESTIÓN DE EQUIPO O MAQUINARIA POR RSU

$id_user = $_SESSION['id_user'];

$mtto = new mtto();
$session = new UserFunctions();
$admin = new Admin();

$access_page = $admin->VerificPermitions($id_user, 4);

if(!$access_page == 1){ echo "<script> window.location='../pages/'; </script>"; };



$token = $mysqli->real_escape_string($_GET['units']);

$id_warehouse = $mtto->getValueMtto('id_units_rsu','father_units_rsu','token_units_rsu', $token);
$letter = $mtto->getValueMtto('reference_units_rsu', 'father_units_rsu', 'token_units_rsu', $token);




if(isset($_POST['btnregisterteams']))
{
    $father_units = $mysqli->real_escape_string($_GET['units']);
    $token_teams = $mtto->GenerateTokenMtto();
    $date_teams = $mtto->DateMtto();
    $id_father_units = $mtto->getValueMtto('id_units_rsu', 'father_units_rsu', 'token_units_rsu', $father_units);
    $letter_teams = $mtto->getValueMtto('reference_units_rsu', 'father_units_rsu', 'token_units_rsu', $father_units);
    $number = $mtto->TopNumberTeams($id_father_units);

    $name_teams = $mysqli->real_escape_string($_POST['name_teams']);
    $type_teams = $mysqli->real_escape_string($_POST['type_teams']);
    $model_teams = $mysqli->real_escape_string($_POST['model_teams']);
    $serie_teams = $mysqli->real_escape_string($_POST['serie_teams']);
    $capacity_teams = $mysqli->real_escape_string($_POST['capacity_teams']);
    $mark_teams = $mysqli->real_escape_string($_POST['mark_teams']);
    $plate_teams = $mysqli->real_escape_string($_POST['plate_teams']);
    $description_teams = $mysqli->real_escape_string($_POST['description_teams']);

    $registerteams = $mtto->InsertTeamsUnits($token_teams, $date_teams, $id_father_units, $letter_teams, $number, $type_teams, $model_teams, $mark_teams, $name_teams, $serie_teams, $capacity_teams, $plate_teams, $description_teams, $id_user);

    if($registerteams > 0)
    {
        echo "<script> window.location='cpanelunits?units='".$father_units."; </script>";   
    }
    else
    {
        echo "<script> window.location='cpanelunits?units='".$father_units."; </script>";   
    }


}

$id_father = $mtto->getValueMtto('id_units_rsu', 'father_units_rsu', 'token_units_rsu', $token);


$no_contract = $mtto->getValueMtto('contract_units_rsu', 'contract_units_rsu', 'fk_id_father_units_rsu', $id_father);
$client_contract = $mtto->getValueMtto('client_contract_units_rsu', 'contract_units_rsu', 'fk_id_father_units_rsu', $id_father);



?>

<section class="content">

<input type="hidden" id="warehouse" value="<?php echo $id_warehouse; ?>" name="warehouse">

  <div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detalles: <?php echo $letter; ?></h5>            
                </div> 

                <div class="card-body"> 

                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-file-contract"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Contrato No. (Actual)</span>
                                <span class="info-box-number">
                                
                                <?php echo $no_contract; ?>
                                </span>
                            </div>
                            <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->
                        <div class="col-12 col-sm-6 col-md-3">
                            <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-user-tie"></i></span>

                            <div class="info-box-content">
                                <span class="info-box-text">Cliente (Actual)</span>
                                <span class="info-box-number" style="font-size: 12px;"><?php echo $client_contract; ?></span>
                            </div>
                            <!-- /.info-box-content -->
                            </div>
                            <!-- /.info-box -->
                        </div>
                        <!-- /.col -->

                        <!-- fix for small devices only -->
                            <div class="clearfix hidden-md-up"></div>

                            <?php echo $mtto->TotalMantsRsu($id_father); ?>
                            <?php echo $mtto->TotalNPTRsu($id_father); ?>
                        <!-- /.col -->
                            
                        <!-- /.col -->
                        </div>
                        
                    </div>            
                </div> 
        
        
            </div>          
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Detalles: </h5>            
                </div> 

                <div class="card-body"> 

                    
                        
                        <table id="id_table_units" class="display" style="width: 100%;">
                        
                        <thead>                 
                            
                            <tr style="text-align: center;">
                                <th>Referencia del equipo</th>
                                <th>Nombre</th>                                         
                                <th>Tipo Activo</th>                      
                                <th>Modelo</th>
                                <th>Serie</th>  
                                <th>Capacidad</th> 
                                <th>Marca</th> 
                                <th>Placa</th> 
                                <th>Fecha</th>                    
                                <th>Características</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody style="text-align: center;">

                        
                        
                        </tbody>
                        
                        </table> 
                        
                        <!-- <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default">
                            Registrar Equipo
                        </button>  -->
                        <?php echo $session->AdminRegisterTeams($tokenuser)?>
                    
           
                </div> 
        
        
            </div>          
        </div>


    </div>

</section>

<div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Registrar Equipo</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
              <div class="modal-body">

                <div class="callout callout-info">
                  <h5>Pasos para registrar un Equipo:</h5>

                  <p>
                      1) Escribimos en la casilla Nombre, el nombre del equipo o maquinaria.<br>                                                           
                      2) Escribimos en la casilla Tipo de activo, el tipo de maquinaria o equipos. (Ejemplo: Maquinaria, unidad).<br>            
                      3) Escribimos en la casilla Modelo, la referencia al modelo del equipo o maquinaria.<br>
                      4) Escribimos en la casilla Serie, el número o referencia de serie del equipo o maquinaria.<br>
                      5) Escribimos en la casilla Capacidad, la referencia de capacidad del equipo.<br>
                      6) Escribimos en la casilla Marca, el nombre del fabricante o marca del equipo o maquinaria.<br>
                      7) Escribimos en la casilla Placa, el número de placa del vehiculo.<br>
                      8) Escribimos en la casilla Características, breve descripción del equipo o maquinaria.<br>
                      9) Presionamos sobre el botón <b>Guardar</b>.

                        
                  </p>
                </div>

              
                <div class="row">

                  <div class="col-sm-4">                
                      <div class="form-group">
                      <label for="inputSuccess">Nombre <b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control"  name="name_teams" required>
                      </div>
                  </div>

                  <div class="col-sm-4">
                      <div class="form-group">
                      <label>Tipo de activo <b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control" name="type_teams" required>
                      </div>  
                  </div>

                  <div class="col-sm-4">
                      <div class="form-group">
                      <label>Modelo <b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control" name="model_teams" required>
                      </div>  
                  </div>

                  <div class="col-sm-4">
                      <div class="form-group">
                      <label>Serie <b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control" name="serie_teams" required>
                      </div>  
                  </div>

                  <div class="col-sm-4">
                      <div class="form-group">
                      <label>Capacidad <b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control" name="capacity_teams" required>
                      </div>  
                  </div>

                  <div class="col-sm-4">
                      <div class="form-group">
                      <label>Marca <b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control" name="mark_teams" required>
                      </div>  
                  </div>

                  <div class="col-sm-4">
                      <div class="form-group">
                      <label>Placa <b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control" name="plate_teams" required>
                      </div>  
                  </div>

                  <div class="col-sm-8">
                      <div class="form-group">
                      <label>Caracteristicas <b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control" name="description_teams" required>
                      </div>  
                  </div>                  
                       
                  
                </div>
         
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="input" name="btnregisterteams" class="btn btn-success">Guardar</button>
              </div>

            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
</div>
