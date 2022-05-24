<?php

//PÁGINA DE REGISTRO DE ACTIVOS DE INVENTARIO POR ALMACÉN



$tokenw = $mysqli->real_escape_string($_GET['warehouse']);

$mtto = new mtto();

$admin = new Admin();

$access_page = $admin->VerificPermitions($id_user, 7);

if(!$access_page == 1){ echo "<script> window.location='../pages/'; </script>"; };


$id_warehouse = $mtto->getValueMtto('id_warehouse','warehouse','token_warehouse', $tokenw);
$description_warehouse = $mtto->getValueMtto('description_warehouse','warehouse','token_warehouse', $tokenw);

$errors = array();

if(!empty($_POST))
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
    $simbol = $mtto->LetterWarehouse($description_warehouse);
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


?>
<input type="hidden" id="warehouse" value="<?php echo $id_warehouse; ?>" name="warehouse">
<section class="content">

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Información del almacén: <?php echo $description_warehouse; ?></h5>            
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

                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default">
                  Registrar Activo
                </button>

            

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
              <h4 class="modal-title">Registrar Activo</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
              <div class="modal-body">          
                
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
                <button type="input" class="btn btn-success">Guardar</button>
              </div>

            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>