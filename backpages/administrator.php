<?php
 //PÁGINA DE REGISTRO DE USUARIOS


$errors = array();

$message = "";

$regusu = new RegisterUsers();
$admin = new Admin();

$access_page = $admin->VerificPermitions($id_user, 1);

if(!$access_page == 1){ echo "<script> window.location='../pages/'; </script>"; };

if(!empty($_POST))
{
  $names = $mysqli->real_escape_string($_POST['firstname']);
  $secondnames = $mysqli->real_escape_string($_POST['secondname']);
  $phoneuser = $mysqli->real_escape_string($_POST['phoneuser']);
  $emailuser = $mysqli->real_escape_string($_POST['emailuser']);

  $activation = 0;
  $registerpass = 0;

  $date = $regusu->DateUsers();
  $dates = $regusu->DateUsers();

  if($regusu->IsNullUser($names,$secondnames, $phoneuser, $emailuser))
  {
    $errors[] = "Debe completar todo el fomulario";
  }
  if(!$regusu->IsEmail($emailuser))
  {
    $errors[] = "El correo electrónico no es valido";
  }
  if($regusu->EmailExist($emailuser))
  {
    $errors[] = "El correo $emailuser ya se encuentra registrado.";
  }

  if(count($errors) == 0)
  {
    $token = $regusu->GenerateToken();

    $registeruser = $regusu->RegisterUser($names, $secondnames, $phoneuser, $emailuser, $date, $dates, $activation, $registerpass, $token);
    

    if($registeruser > 0)
    {

      $url = 'http://'.$_SERVER["SERVER_NAME"].'/pages/activeaccount?user='.$registeruser.'&val='.$token;

      $affair = "ACTIVACIÓN DE SU CUENTA - CPS MTTO";

      $nombre = $names." ".$secondnames; 

      if($regusu->SendEmail($emailuser, $names, $affair, $nombre, $url))
      {
        $message = "<div class='alert alert-success alert-dismissible'>
          <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
          <h5><i class='icon fas fa-check'></i>Exito</h5>Se ha registrado correctamente.<br>
          Se ha enviado al correo <b>".$emailuser."</b> para la confirmación de
          la cuenta.</div>";
      }
      else
      {
        $message = "<div class='alert alert-info alert-dismissible'>
          <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
          <h5><i class='icon fas fa-check'></i>Error</h5>Se ha registrado correctamente; pero no se envío el correo.
          Por favor notificarlo al area de sistemas.</div>";
      }

      

    }
  }
}


?>

<section class="content">

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5 class="card-title">Gestión de Usuarios</h5>            
          </div>              
              <div class="card-body">
              <?php echo $message; echo $regusu->ResultBlockError($errors);?>

                          
                <table id="id_table_users" class="display responsive nowrap" style="width:100%" >
                
                  <thead>                 
                    
                    <tr style="text-align: center;">
                      <th>Nombre</th>
                      <th>Correo Corporativo</th>                                         
                      <th>Ult. Conexión</th>                      
                      <th>Estado</th>
                      <th>Acciones</th>                     
                    </tr>
                  </thead>
                  <tbody style="text-align: center;">
                 
                  
                  </tbody>
                
                </table>
                
                <!-- <a class="btn btn-success" href="" style="text-align: center;">Registrar Usuario</a>   -->
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-default">
                  Registrar Usuario
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
              <h4 class="modal-title">Registrar usuario </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
              <div class="modal-body">

              <div class="callout callout-info">
                  <h5>Sugerencias antes de Registrar el Usuario:</h5>

                  <p>
                        1) Verifique que el usuario tenga correo corporativo registrado.<br>
                        2) Por temas de seguridad, se recomienda el uso de correo corporativo para el registro de usuarios a esta plataforma.<br>
                        3) Si se genera error al momento de registrar el usuario, ser notificado a soporte técnico.<br>                        
                        
                  </p>
              </div>
                
                               

                <div class="row">

                  <div class="col-sm-6">                
                      <div class="form-group">
                      <label for="inputSuccess">Nombres <b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control"  name="firstname" required>
                      </div>
                  </div>

                  <div class="col-sm-6">
                      <div class="form-group">
                      <label>Apellidos <b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control" name="secondname" required>
                      </div>  
                  </div>

                  <div class="col-sm-6">
                      <div class="form-group">
                      <label>Número telefónico / Celular <b style="color:#B20F0F;">*</b></label>
                      <input type="text" class="form-control" name="phoneuser" required>
                      </div>
                  </div>

                  <div class="col-sm-6">
                      <div class="form-group">
                      <label>Correo Corporativo <b style="color:#B20F0F;">*</b></label>
                      <input type="email" class="form-control" name="emailuser" required>
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