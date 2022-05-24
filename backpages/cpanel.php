<?php

//PÁGINA DE GESTIÓN DE INVENTARIO POR ALMACÉN

$token = $mysqli->real_escape_string($_GET['warehouse']);

$mtto = new mtto();
$session = new UserFunctions();
$admin = new Admin();

$access_page = $admin->VerificPermitions($id_user, 7);

if(!$access_page == 1){ echo "<script> window.location='../pages/'; </script>"; };

$id_warehouse = $mtto->getValueMtto('id_warehouse','warehouse','token_warehouse', $token);
$description_warehouse = $mtto->getValueMtto('description_warehouse','warehouse','token_warehouse', $token);


$errors = array();

if(isset($_POST['btnregisteractive']))
{

    $facturer = $mysqli->real_escape_string($_POST['facturer']);
    $serie = $mysqli->real_escape_string($_POST['serie']);
    $unity = $mysqli->real_escape_string($_POST['unity']);
    $nameactive = $mysqli->real_escape_string($_POST['nameactive']);
    $model = $mysqli->real_escape_string($_POST['model']);
    $alarm = $mysqli->real_escape_string($_POST['alarm']);
    $unityvalue = $mysqli->real_escape_string($_POST['unityvalue']);
    $typeactive = $mysqli->real_escape_string($_POST['typeactive']);
    
    $token = $mtto->GenerateTokenMtto();
    $datereg = $mtto->DateMtto();
    $simbols = $mtto->LetterWarehouse($description_warehouse);

    $simbol = "R-".$simbols;
    $num = $mtto->AfterNumWarehouse($id_warehouse);
    $stock = 0;

    if($mtto->IsNullActive($facturer, $serie, $unity, $nameactive, $model, $alarm, $unityvalue, $typeactive))
    {
        $errors[] = "Debe diligenciar el formulario completamente.";
    }

    if(count($errors) == 0)
    {

        $regs = $mtto->RegisterActivesWarehouse($token, $datereg, $simbol, $num, $id_warehouse, $nameactive, $typeactive, $unity, $unityvalue, $facturer, $model, $serie, $alarm, $stock);

        if($regs > 0)
        {
          $message = "<div class='alert alert-success alert-dismissible'>
          <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
          <h5><i class='icon fas fa-check'></i>Exito</h5>Se ha registrado el activo correctamente.</div>";

               
          
        }
        else
        {
          $message = "<div class='alert alert-info alert-dismissible'>
          <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
          <h5><i class='icon fas fa-check'></i>Error</h5>El activo no se registró correctamente.</div>";

          

       
        }

        
    }
 

}

if(isset($_POST['btnregister_ea']))
{
  $date_ea = $mtto->DateMtto();
  $active_ea = $mysqli->real_escape_string($_POST['active_ea']);
  $pay_ea = $mysqli->real_escape_string($_POST['pay_ea']);
  $cant_ea = $mysqli->real_escape_string($_POST['cant_ea']);
  $descrip_active_ea = $mtto->getValueMtto('description_element_spares','spares_parts','id_spares', $active_ea);

  $insert_ea = $mtto->RegisterActiveEA($pay_ea, $date_ea, $id_warehouse, $active_ea, $cant_ea);

  if($insert_ea > 0)
  {
    $message = "<div class='alert alert-success alert-dismissible'>
    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
    <h5><i class='icon fas fa-check'></i>Exito</h5>Se ha registrado la entrada del siguiente activo: ".$descrip_active_ea."</div>";

    $mtto->SumStockActive($active_ea, $cant_ea);     

  }
  else
  {
    $message = "<div class='alert alert-info alert-dismissible'>
    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
    <h5><i class='icon fas fa-check'></i>Error</h5>No se ha podido registrar la entrada del activo.</div>";

  }


}

if(isset($_POST['btnregister_sa']))
{
  $date_sa = $mtto->DateMtto();
  $active_sa = $mysqli->real_escape_string($_POST['active_sa']);
  $pay_sa = $mysqli->real_escape_string($_POST['pay_sa']);
  $cant_sa = $mysqli->real_escape_string($_POST['cant_sa']);
  $descrip_active_sa = $mtto->getValueMtto('description_element_spares','spares_parts','id_spares', $active_sa);
  $stock = $mtto->getValueMtto('stock_spares', 'spares_parts','id_spares', $active_sa);

  if($cant_sa <= $stock)
  {

    $insert_sa = $mtto->RegisterActiveSA($pay_sa, $date_sa, $id_warehouse, $active_sa, $cant_sa);

    if($insert_sa > 0)
    {
      $message = "<div class='alert alert-success alert-dismissible'>
      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
      <h5><i class='icon fas fa-check'></i>Exito</h5>Se ha registrado la salida del siguiente activo: ".$descrip_active_sa."</div>";

      $mtto->SubtractStockActive($active_sa, $cant_sa);   
 
    }
    else
    {
      $message = "<div class='alert alert-info alert-dismissible'>
      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
      <h5><i class='icon fas fa-check'></i>Error</h5>No se ha podido registrar la salida del activo.</div>";

      
      

    }
  }
  else
  {
    $message = "<div class='alert alert-warning alert-dismissible'>
    <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
    <h5><i class='icon fas fa-exclamation-triangle'></i>Espera</h5>Error al retirar ".$cant_sa." cantidades del almacén. Por favor
    verfica el stock del siguiente activo: ".$descrip_active_sa."</div>";      

  }
  
}

?>

<section class="content">

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Información del almacén: <?php echo $description_warehouse; ?></h5>         
            <div class="card-tools">                  
                  
                  <a href="../functions/Reload/ReloadCpanel?warehouse=<?php echo $token; ?>" class="btn btn-info btn-sm"><i class="fas fa-sync-alt"></i></a>
                  <a href="../report/ReportF262?warehouse=<?php echo $token; ?>" class="btn btn-primary btn-sm" target="_blank"><i class="fas fa-file-alt"></i> F-262</a>
                  <a href="../report/ReportF263?warehouse=<?php echo $token; ?>" class="btn btn-success btn-sm" target="_blank"><i class="fas fa-file-alt"></i> F-263</a>
                  <a href="../report/ReportF264?warehouse=<?php echo $token; ?>" class="btn btn-danger btn-sm" target="_blank"><i class="fas fa-file-alt"></i> F-264</a>
                </div>   
          </div>              
            <div class="card-body">

            <?php echo $mtto->WidgetCpanel($id_warehouse); ?>

            

            </div>            
        </div>           
      </div>          
    </div>

    <input type="hidden" id="warehouse" value="<?php echo $id_warehouse; ?>" name="warehouse">


  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Activos registrados</h5>            
          </div>              
            <div class="card-body">  
                
            <?php echo $message; echo $mtto->ResultBlockError($errors);?>

            <table id="id_table_actives_warehouse" class="display" style="width: 100%;">
                
                  <thead>                 
                    
                    <tr style="text-align: center;">
                      <th>Referencia almacén</th>
                      <th>Nombre elemento</th>                                         
                      <th>Tipo</th>                      
                      <th>unidad</th>
                      <th>Alarma requisición</th>  
                      <th>Valor unitario</th> 
                      <th>Fabricante</th> 
                      <th>Modelo</th> 
                      <th>Serie</th>                    
                      <th>Stock</th>                    
                    </tr>
                  </thead>
                  <tbody style="text-align: center;">

                  
                  
                  </tbody>
                
            </table>

            <?php echo $session->AdminActiviesWarehouse($tokenuser); ?>

               

            </div>            
        </div>           
      </div>          
    </div>
  </div>
  </div>

  


<!-- Modal de Registrar Activo -->

  <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Registrar Activo</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
              <div class="modal-body">     
                
                <div class="callout callout-info">
                  <h5>Pasos para registrar un Activo de inventario:</h5>

                  <p>
                      1) Escribimos en la casilla Fabricante, el nombre del fabricante.<br>                                                           
                      2) Escribimos en la casilla Serie, el número de serie o referencia del activo.<br>
                      3) Seleccionamos el tipo de activo, en la casilla Tipo de activo.<br>
                      4) Escribimos en la casilla Unidad, el tipo de medida der unidad.<br>
                      5) Escribimos en la casilla Nombre de activo, la descripción del activo.<br>
                      6) Escribimos en la casilla Modelo, el número o referencia del activo.<br>
                      7) Escribimos en la casilla Alarma de requisición, el número de cantidad que va avisar la plataforma para requisición.<br>
                      8) Escribimos en la casilla Valor unitario, el costo del activo a registrar. <b>NOTA:</b> El número del costo, <b>NO</b> debe ir separado por puntos ni comas.<br>
                      9) Presionamos sobre el botón <b>Guardar</b>.
     
                        
                  </p>
                </div>
                
                <div class="row">

                  <div class="col-sm-6">                
                      <div class="form-group">
                      <label for="inputSuccess">Fabricante<b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control"  name="facturer" required>
                      </div>
                  </div>

                  <div class="col-sm-6">
                      <div class="form-group">
                      <label>Serie<b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control" name="serie" required>
                      </div>  
                  </div>

                  <div class="col-sm-6">
                      <div class="form-group">
                      <label>Tipo de activo<b style="color:#B20F0F;">*</b></label>
                      <select class="form-control" name="typeactive">
                          <option value="">Seleccione Típo de actívo:</option>
                          <?php

                          $active = $mtto->OptionActive();

                          echo $active;

                          ?>
                      </select>
                      </div>
                  </div>

                  <div class="col-sm-6">
                      <div class="form-group">
                      <label>Unidad<b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control" name="unity" required>
                      </div>
                  </div>  

                  <div class="col-sm-6">
                      <div class="form-group">
                      <label>Nombre activo<b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control" name="nameactive" required>
                      </div>
                  </div> 

                  <div class="col-sm-6">
                      <div class="form-group">
                      <label>Modelo<b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control" name="model" required>
                      </div>
                  </div> 

                  <div class="col-sm-6">
                      <div class="form-group">
                      <label>Alarma de requisición<b style="color:#B20F0F;">*</b></label>
                      <input type="number" class="form-control" name="alarm" required>
                      </div>
                  </div> 

                  <div class="col-sm-6">
                      <div class="form-group">
                      <label>Valor unitario<b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control" name="unityvalue" required>
                      </div>
                  </div> 

                    
                </div>
         
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="input" class="btn btn-success" name="btnregisteractive">Guardar</button>
              </div>

            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

</section>


<!-- Modal de Registrar Entrada Almacén -->



<div class="modal fade" id="modal-default-ea">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Gestionar Entrada Almacén</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      
        <div class="modal-body">   

          <div class="callout callout-info">
            <h5>Pasos para registrar una Entrada Almacén:</h5>

            <p>
                1) Seleccionamos el activo, en la casilla Activo de inventario.<br>                                                           
                2) Escribimos en la casilla No. Factura / Remisión, el número de serie o referencia de la factura o remisión.<br>            
                3) Escribimos en la casilla Cantidad a ingresar, el número de stock a ingresar.<br>
                4) Presionamos sobre el botón <b>Guardar</b>.

                  
            </p>
          </div>

          <div class="row">
            <div class="col-md-7">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Activos (Entrada) Registrados</h5>            
                </div>              
                <div class="card-body">  

                  <table id="datatable_modal_ea" class="display" style="width: 100%;">
                  
                    <thead>                 
                      
                      <tr style="text-align: center;">
                        <th>No. Remisión</th>
                        <th>Fecha Ingreso</th>                                         
                        <th>Referencia Almacén</th>                      
                        <th>Nombre del Elemento</th>
                        <th>Cantidad Ingresada</th>                                           
                      </tr>
                    </thead>
                    <tbody style="text-align: center;">

                    
                    
                    </tbody>
                  
                  </table>                                                  

                </div>            
              </div>           
            </div>  
            
            <div class="col-md-5">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title">Realizar entrada</h5>            
                </div>              
                <div class="card-body">  

                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">

                  <div class="form-group">
                  <label for="inputSuccess">Activo de inventario:<b style="color:#B20F0F;">*</b></label>
                    <select name="active_ea" size="17" class="form-control">
                      <?php

                        $actives = $mtto->OptionActiveA($id_warehouse);

                        echo $actives;

                      ?>

                    </select>
                  </div>

                  <div class="form-group">
                    <label>No. Factura / Remisión:<b style="color:#B20F0F;">*</b></label>
                    <input type="text" class="form-control" name="pay_ea" required>
                  </div>  
                  <div class="form-group">
                    <label>Cantidad a ingresar:<b style="color:#B20F0F;">*</b></label>
                    <input type="number" class="form-control" name="cant_ea" required>
                  </div>                    

                </div>            
              </div>           
            </div>


        </div>
          
        
          
          
    
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-success" name="btnregister_ea">Guardar</button>
        </div>

        </form>     

      
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


<!-- Modal de Gestionar Salida -->


<div class="modal fade" id="modal-default-sa">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Gestionar Salida Almacén</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            
              <div class="modal-body">   
                <div class="callout callout-info">
                  <h5>Pasos para registrar una Salida Almacén:</h5>

                  <p>
                      1) Seleccionamos el activo, en la casilla Activo de inventario.<br>                                                           
                      2) Escribimos en la casilla No. Factura / Remisión, el número de serie o referencia de la factura o remisión.<br>            
                      3) Escribimos en la casilla Cantidad a ingresar, el número de stock a retirar.<br>
                      4) Presionamos sobre el botón <b>Guardar</b>.

                        
                  </p>
                </div>

              <div class="row">
                <div class="col-md-7">
                  <div class="card">
                    <div class="card-header">
                      <h5 class="card-title">Activos (Salida) Registrados</h5>            
                    </div>              
                    <div class="card-body">  

                      <table id="datatable_modal_sa" class="display" style="width: 100%;">
                      
                        <thead>                 
                          
                          <tr style="text-align: center;">
                            <th>No. Remisión</th>
                            <th>Fecha Retiro</th>                                         
                            <th>Referencia Almacén</th>                      
                            <th>Nombre del Elemento</th>
                            <th>Cantidad Retirada</th>                                           
                          </tr>
                        </thead>
                        <tbody style="text-align: center;">

                        
                        
                        </tbody>
                      
                      </table>                                                  

                    </div>            
                  </div>           
                </div>  
                
                <div class="col-md-5">
                  <div class="card">
                    <div class="card-header">
                      <h5 class="card-title">Realizar salida</h5>            
                    </div>              
                    <div class="card-body">  

                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">

                      <div class="form-group">
                      <label for="inputSuccess">Activo de inventario:<b style="color:#B20F0F;">*</b></label>
                        <select name="active_sa" size="17" class="form-control">
                          <?php

                            $actives = $mtto->OptionActiveA($id_warehouse);

                            echo $actives;

                          ?>

                        </select>
                      </div>

                      <div class="form-group">
                        <label>No. Factura / Remisión:<b style="color:#B20F0F;">*</b></label>
                        <input type="text" class="form-control" name="pay_sa" required>
                      </div>  
                      <div class="form-group">
                        <label>Cantidad a retirar:<b style="color:#B20F0F;">*</b></label>
                        <input type="number" class="form-control" name="cant_sa" required>
                      </div>                    

                    </div>            
                  </div>           
                </div>


              </div>
                
              
                
                
         
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-success" name="btnregister_sa">Guardar</button>
              </div>

            </form>

            
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
</div>


