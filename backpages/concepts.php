<?php

//PÁGINA DE GESTIÓN DE CONCEPTOS

$mtto = new mtto();
$admin = new Admin();

$access_page = $admin->VerificPermitions($id_user, 3);
if(!$access_page == 1){ echo "<script> window.location='../pages/'; </script>"; };


if(isset($_POST['workforce']))
{ 

  $errors = array();

  $nameconcept = $mysqli->real_escape_string($_POST['wknameconcept']);
  $value = $mysqli->real_escape_string($_POST['valuewknameconcept']);
  $token = $mtto->GenerateTokenMtto();
  $state = 0;

  if($mtto->IsNullWorforce($nameconcept, $value))
  {
    $errors[] = "Debe diligenciar el formulario correctamente.";
  }

  if(count($errors) == 0)
  {
      $regitser = $mtto->RegisterWorkforce($token, $nameconcept, $state, $value);

      if($register > 0)
      {
        echo "<script> window.location='concepts'; </script>"; 
      }
      else
      {
        echo "<script> window.location='concepts'; </script>"; 
      }
  }

}

if(isset($_POST['actives']))
{
  $nameactive = $mysqli->real_escape_string($_POST['active']);
  $states = 0;
  $tokens = $mtto->GenerateTokenMtto();

  $regs = $mtto->RegisterActive($tokens, $nameactive, $states);

  if($regs > 0)
  {
    echo "<script> window.location='concepts'; </script>"; 
  }
  else
  {
    echo "<script> window.location='concepts'; </script>"; 
  }
}

if(isset($_POST['btnupdateconcept']))
{
  $concept = $mysqli->real_escape_string($_POST['concept']);
  $value = $mysqli->real_escape_string($_POST['value']);
  $tkconcept = $mysqli->real_escape_string($_POST['tkconcept']);

  $upconcept = $mtto->UpdateConceptMan($concept, $value, $tkconcept);

  if($upconcept > 0)
  {
    echo "<script> window.location='concepts'; </script>"; 
  }
  else
  {
    echo "<script> window.location='concepts'; </script>"; 
  }
 
  
}


?>

<section class="content">

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Control de conceptos</h5>            
          </div>              
            <div class="card-body"> 

            <div class="row">

            <?php echo $mes; ?>

              <div class="col-md-6">
                  <div class="card">

                      <div class="card-header">
                      <h3 class="card-title">Registrar mano de obra</h3>
                      </div>

                      

                      <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                          <div class="card-body">
                            <div class="callout callout-info">
                              <h5>Pasos a registrar concepto de mano de obra:</h5>

                              <p>
                                    1) Escribimos en la casilla Nombre del concepto, la descripción de la mano de obra.<br>
                                    2) Escribimos en la casilla Valor por hora, el costo de la mano de obra.<br>
                                    3) Luego presionamos sobre el botón <b>Registrar.</b><br>
                                    <b>NOTA: </b>El valor por hora <b>NO</b> debe ir separada ni por puntos ni comas.<br>
                                                            
                                    
                              </p>
                            </div>

                              <div class="form-group">
                                  <label for="exampleInputEmail1">Nombre del concepto</label>
                                  <input type="text" name="wknameconcept" class="form-control" placeholder="Eje: Auxiliar operativo">
                              </div>
                              <div class="form-group">
                                  <label for="exampleInputEmail1">Valor por hora</label>
                                  <input type="text" name="valuewknameconcept" class="form-control" placeholder="Eje: 3000 / 50000 / 91000">
                              </div>

                              <div class="form-group">
                                  <button type="submit" class="btn btn-danger" name="workforce" >Registrar</button>
                              </div>

                              <?php echo $mtto->ResultBlockError($errors); ?>

                          </div>

                      </form>
                  </div>
              </div>

              <div class="col-md-6">
                  <div class="card">
                      <div class="card-header">
                      <h3 class="card-title">Registrar tipo de activo</h3>
                      </div>

                      <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                          <div class="card-body">

                            <div class="callout callout-info">
                              <h5>Pasos a registrar tipo de activo:</h5>

                              <p>
                                  1) Escribimos en la casilla Nombre del tipo, la descripción del tipo de activo. (Ejemplo: Herramienta, Servicio, Repuesto, Etc.)<br>                                                           
                                  2) Luego presionamos sobre el botón <b>Registrar.</b>
                                    
                              </p>
                            </div>

                              <div class="form-group">
                                  <label for="exampleInputEmail1">Nombre del tipo</label>
                                  <input type="text" class="form-control" name="active" placeholder="Eje: Herramienta / Repuesto">
                              </div>                              

                              <div class="form-group">
                                  <button type="submit" class="btn btn-danger" name="actives">Registrar</button>
                              </div>

                          </div>
                      </form>
                  </div>
              </div>

              

              <div class="col-md-6">
                  <div class="card">
                      <div class="card-header">
                      <h3 class="card-title">Registrado de concepto</h3>
                      </div>
                      
                      <div class="card-body box-profile">

                       <?php echo $mtto->SearchManConcept(); ?>

                      </div>

                  </div>
              </div>

              

              <div class="col-md-6">
                  <div class="card">
                      <div class="card-header">
                      <h3 class="card-title">Registrado de concepto</h3>
                      </div>
                      
                      <div class="card-body box-profile">

                        <table id="id_table_active" class="display" style="width: 100%;">
                          <thead>
                            <tr style="text-align: center;">
                              <th>Tipo</th>
                              <th>Acción</th>
                            </tr>
                          </thead>
                          <tbody style="text-align: center;">
                            
                           
                          </tbody>

                        </table>

                      </div>

                  </div>
              </div>

                
            </div>
                
            </div>            
        </div>           
      </div>          
    </div>
  </div>

</section>