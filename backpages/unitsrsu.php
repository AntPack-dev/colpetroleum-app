<?php

//PÁGINA DE GESTIÓN DE UNIDADES RSU


$id_user = $_SESSION['id_user'];

$mtto = new mtto();
$session = new UserFunctions();
$admin = new Admin();

$access_page = $admin->VerificPermitions($id_user, 4);

if(!$access_page == 1){ echo "<script> window.location='../pages/'; </script>"; };



if(isset($_POST['btnregisterunits']))
{
    $reference_units = $mysqli->real_escape_string($_POST['reference_units']);
    $state_units = $mysqli->real_escape_string($_POST['state_units']);
    $costnpt_units = 0;
    $costmant_units = 0;

    $token_units = $mtto->GenerateTokenMtto();
    $date_units = $mtto->DateMtto();

    $regunits = $mtto->InsertUnits($token_units, $date_units, $reference_units, $state_units, $costmant_units, $costnpt_units, $id_user);

    if($regunits > 0)
    {
        echo "<script> window.location='unitsrsu'; </script>"; 
    }
    else
    {
        echo "<script> window.location='unitsrsu'; </script>"; 
    }

}

if(isset($_POST['btnregistercontractunits']))
{
    $id_units_rsu = $mysqli->real_escape_string($_POST['units']);
    $no_contract = $mysqli->real_escape_string($_POST['no_contract']);
    $ubication_contract = $mysqli->real_escape_string($_POST['ubication_contract']);
    $client_contract = $mysqli->real_escape_string($_POST['client_contract']);
    $dateini_contract = $mysqli->real_escape_string($_POST['dateini_contract']);
    $datefini_contract = $mysqli->real_escape_string($_POST['datefini_contract']);
    $token_units_contract = $mtto->GenerateTokenMtto();
    $date_units_contract = $mtto->DateMtto();

    $regcontract = $mtto->AssignContract($token_units_contract, $id_units_rsu, $date_units_contract, $no_contract, $ubication_contract, $client_contract, $dateini_contract, $datefini_contract, $id_user);

    if($regcontract > 0)
    {
      echo "<script> window.location='unitsrsu'; </script>";   
    }
    else
    {
      echo "<script> window.location='unitsrsu'; </script>";   
    }
}

if (!empty($_POST['action']) && $_POST['action'] == 'edit') {
    $mtto->updateUnitRsu($mysqli->real_escape_string($_POST['id']), $mysqli->real_escape_string($_POST['reference_units_rsu']), $mysqli->real_escape_string($_POST['state_units_rsu']));
    echo "<script> window.location='unitsrsu'; </script>";
}

?>

<section class="content">

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Unidades RSU</h5>
            <div class="card-tools">
            <a href="../report/ReportF270" class="btn btn-danger btn-sm" target="_blank"><i class="fas fa-file-alt"></i> F-270</a>
            </div>         
          </div>              
            <div class="card-body"> 

                <table id="id_table_units_rsu" class="display" style="width:100%">
                <thead>
                    <tr style="text-align: center;">
                        <th>Referencia del equipo</th>
                        <th>Fecha de registro</th>
                        <th>Estado actual</th>
                        <th>Ubicación actual</th>
                        <th>cliente</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">

                                       

                </tbody>
                
                </table> 

                <?php echo $session->AdminRegisterRsu($tokenuser); ?>
                

            </div>            
        </div>           
      </div>          
    </div>
  </div>

</section>


<div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Registrar Unidad</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
              <div class="modal-body">

                <div class="callout callout-info">
                  <h5>Pasos para registrar Unidad RSU:</h5>

                  <p>
                      1) Escribimos en la casilla Referencia del equipo, la descripción del equipo (Ejemplo: RSU 01, RSU 02, etc.)<br>                                                           
                      2) Escribimos en la casilla Estado Actual, Unidad Asignada.<br>
                      3) Presionamos sobre el botón <b>Guardar</b>.<br>
                      <b>NOTA:</b> El registro no aparecerá en la tabla hasta que le asigne un contrato.
                        
                  </p>
                </div>

              
                <div class="row">

                  <div class="col-sm-6">                
                      <div class="form-group">
                      <label for="inputSuccess">Referencia del equipo <b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control"  name="reference_units" required>
                      </div>
                  </div>

                  <div class="col-sm-6">
                      <div class="form-group">
                      <label>Estado actual <b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control" name="state_units" required>
                      </div>  
                  </div>

                </div>
         
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="input" name="btnregisterunits" class="btn btn-success">Guardar</button>
              </div>

            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
</div>

<div class="modal fade" id="modal-edit-unidad-rsu">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Registrar Unidad</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" id="unidad-rsu-id-edit" name="id">
              <div class="modal-body">

                <div class="callout callout-info">
                  <h5>Pasos para registrar Unidad RSU:</h5>

                  <p>
                      1) Escribimos en la casilla Referencia del equipo, la descripción del equipo (Ejemplo: RSU 01, RSU 02, etc.)<br>
                      2) Escribimos en la casilla Estado Actual, Unidad Asignada.<br>
                      3) Presionamos sobre el botón <b>Guardar</b>.<br>
                      <b>NOTA:</b> El registro no aparecerá en la tabla hasta que le asigne un contrato.

                  </p>
                </div>


                <div class="row">

                  <div class="col-sm-6">
                      <div class="form-group">
                      <label for="inputSuccess">Referencia del equipo <b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control" id="unidad-rsu-reference_units_rsu-edit" name="reference_units_rsu" required>
                      </div>
                  </div>

                  <div class="col-sm-6">
                      <div class="form-group">
                      <label>Estado actual <b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control" id="unidad-rsu-state_units_rsu-edit" name="state_units_rsu" required>
                      </div>
                  </div>

                </div>

              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="submit" name="btnregisterunits" class="btn btn-success">Actualizar</button>
              </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
</div>


      <div class="modal fade" id="modal-default-contract">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Asignar contrato</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
              <div class="modal-body"> 

              <div class="callout callout-info">
                  <h5>Pasos para asignar contrato a Unidad RSU:</h5>

                  <p>
                      1) Seleccionamos la Unidad RSU en la casilla Unidades Registradas.<br>                                                           
                      2) Escribimos en la casilla No. Contrato/Remisión, el número correspondiente.<br>
                      3) Escribimos en la casilla Lugar de ejecución, el nombre del lugar correspondiente.<br>
                      4) Escribimos en la casilla Cliente, nombre del proveedor.<br>
                      5) Escribimos en la casilla Fecha de inicio, la fecha de inicio del contrato o remisión.<br>
                      6) Escribimos en la casilla Fecha de finalización, la fecha de final del contrato o remisión.<br>
                      7) Presionamos sobre el botón <b>Asignar.</b>
                        
                  </p>
                </div>

              <div class="row">
                <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                    <h5 class="card-title">Unidades registradas</h5>            
                    </div>              
                    <div class="card-body"> 
                      

                        <div class="form-group">
                            
                                <select name="units" size="19" class="form-control">
                                    
                                <?php

                                    $units = $mtto->OptionsUnits();

                                    echo $units;

                                ?>

                                </select>
                        </div>

                                                                  

                    </div>            
                </div>           
                </div>  
                
                <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                    <h5 class="card-title">Detalles asignación de contratos</h5>            
                    </div>              
                    <div class="card-body">
                    

                    <div class="form-group">
                        <label>No. Contrato / Remisión<b style="color:#B20F0F;">*</b></label>
                        <input type="text" class="form-control" name="no_contract" required>
                    </div>  
                    <div class="form-group">
                        <label>Lugar de ejecución<b style="color:#B20F0F;">*</b></label>
                        <input type="text" class="form-control" name="ubication_contract" required>
                    </div>  
                    <div class="form-group">
                        <label>Cliente<b style="color:#B20F0F;">*</b></label>
                        <input type="text" class="form-control" name="client_contract" required>
                    </div> 
                    <div class="form-group">
                        <label>Fecha de inicio<b style="color:#B20F0F;">*</b></label>
                        <input type="date" class="form-control" name="dateini_contract" required>
                    </div> 
                    <div class="form-group">
                        <label>Fecha de finalización<b style="color:#B20F0F;">*</b></label>
                        <input type="date" class="form-control" name="datefini_contract" required>
                    </div>                   

                    </div>            
                </div>           
            </div>


          </div>

                
         
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="input" name="btnregistercontractunits" class="btn btn-success">Asignar</button>
              </div>

            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
