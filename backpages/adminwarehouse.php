<?php
 
 //PÁGINA DE REGISTRO DE ALMACÉNES

 $errors = array();
 $message = "";

 $house = new mtto();
 $session = new UserFunctions();

 $admin = new Admin();

  $access_page = $admin->VerificPermitions($id_user, 7);
  if(!$access_page == 1){ echo "<script> window.location='../pages/'; </script>"; };


 if(!empty($_POST))
 {
  $namewarehouse = $mysqli->real_escape_string($_POST['namewarehouse']);

  $statewarehouse = 0;
  $datewarehouse = $house->DateMtto();

  $tokenwarehouse = $house->GenerateTokenMtto();

  $register = $house->RegisterWarehouse($tokenwarehouse, $namewarehouse, $datewarehouse, $statewarehouse);

  if($register > 0)
  {
    $message = "<div class='alert alert-success alert-dismissible'>
          <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
          <h5><i class='icon fas fa-check'></i>Exito</h5>Se ha registrado correctamente el almacén.</div>";
  }
  else
  {
    $message = "<div class='alert alert-info alert-dismissible'>
          <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
          <h5><i class='icon fas fa-check'></i>Error</h5>Error al registrar el almacén.</div>";
  }
   
 }

?>

<section class="content">

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Gestión de Almacenes</h5>            
          </div>              
              <div class="card-body">
              <?php echo $message; ?>
                <table id="id_table_warehouse" class="display" style="width: 100%;">
                
                  <thead>                 
                    
                    <tr style="text-align: center;">
                      <th>Descripción</th>
                      <th>Fecha de Registro</th>                                                          
                      <th>Estado</th>
                      <th>Acciones</th>                     
                    </tr>
                  </thead>
                  <tbody style="text-align: center;">
                 
                  
                  </tbody>
                
                </table>
                
                <!-- <a class="btn btn-success" href="" style="text-align: center;">Registrar Usuario</a>   -->
                <!-- <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default">
                  Registrar almacén
                </button> -->

                <?php echo $session->AdminRegisterWarehouse($tokenuser); ?>

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
              <h4 class="modal-title">Registrar almacen</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
              <div class="modal-body">

              <div class="callout callout-info">
                  <h5>Sugerencias antes de Registrar un almacen:</h5>

                  <p>
                        1) Debe poner el nombre del almacén de la siguiente manera: Almacén - 'Nombre del lugar'. Ejemplo: Almacén - Barrancabermeja<br>                                              
                        
                  </p>
              </div>
                
                               

                <div class="row">

                  <div class="col-sm-12">                
                      <div class="form-group">
                      <label for="inputSuccess">Descripción Almacén<b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control" name="namewarehouse" required>
                      </div>
                  </div>            

                    
                </div>
         
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="input" class="btn btn-success">Guardar</button>
              </div>

            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>