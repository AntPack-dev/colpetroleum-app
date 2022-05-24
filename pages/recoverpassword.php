<?php
include('../db/ConnectDB.php');
include('../functions/OperationsUser.php');

if(empty($_GET['user']))
{
    header("Location: ../");
}
if(empty($_GET['token']))
{
    header("Location: ../");
}

$valide = new RegisterUsers();
$reset = new ResetUser();

$user = $mysqli->real_escape_string($_GET['user']);
$token = $mysqli->real_escape_string($_GET['token']);

$email = $valide->getValue('email_user','users','token',$user);

$lastpassword = $valide->DateUsers();

$errors = array();


if(!$reset->ValideTokenUser($user))
{
    header("Location: ../");

    exit;

    // echo "ERROR 1";
}

if(!$reset->VerificTokenRestart($token))
{
    header("Location: ../");

    exit;

    // echo "ERROR 2";
}

if(!empty($_POST))
{
    $password = $mysqli->real_escape_string($_POST['password']);
    $conpassword = $mysqli->real_escape_string($_POST['conpassword']);

    if($valide->IsNullPassword($password, $conpassword))
    {
        $errors[] = "Debe completar el formulario para validar sus credenciales.";
    }

    if(!$valide->ValidePassword($password, $conpassword))
    {
        $errors[] = "Las contraseñas diligenciadas, no coinciden.";
    }

    if(!$reset->VerificUserActive($user, $token))
    {
        $errors[] = "Su cuenta no está activada, por favor realiza la activación de su cuenta.";
    }

    if(count($errors) == 0)
    {
        $passhash = $valide->HashPassword($password); 

        $updatepass = $reset->UpdatePassword($passhash, $lastpassword, $user, $token);
        
    }

}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Colpetroleum Tools</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../styles/plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../styles/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../styles/dist/css/adminlte.min.css">

   

  
  </head>
<body class="hold-transition login-page bg-dark">

<div class="login-box" >
  <!-- /.login-logo -->
  <div class="card card-outline card-danger" >
    <div class="card-header text-center text-dark">
      <h3><b>Colpetroleum</b>Tools</h3>
      <!-- <img src="styles/dist/img/logodos.png" style="width: 250px;"> -->
    </div>

    <?php 

     

      if(!$reset->VerificResetUser($user))
      {     

      ?>

          <div class="card-body">

          <div class="callout callout-success text-dark">
              <h5>Su cuenta ha sido restaurada.</h5>

              <p>
                  Al parecer se realizó con exito o su cuenta ya fue restaurada anteriormente.
                  Por favor dar click en el botón INICIAR para ingresar a la plataforma.
                  Si presenta algún error al momento de restaurar su cuenta; notificarlo al área de sistemas.
              </p>
              <a style="text-decoration: none; color: white;" class="btn btn-success btn-block" href="../">INICIAR</a>
          </div>   

          </div>

      <?php
      }
      else
      {
    ?>


    <div class="card-body">       

    <?php echo $valide->ResultBlockError($errors);?> 


        <div class="callout callout-danger text-dark">
            <h5>Restauración de su cuenta.</h5>

            <p>
                Ingrese su nueva contraseña para restablecer la cuenta. Se recomienda aplicar combinación de números y simbolos . (*, -, +, ., @, _, 0-9).                                
                
            </p>
        </div>
       
      
     
      <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
      <div class="input-group mb-3">
          <input type="text" class="form-control" value="<?php echo $email; ?>" readonly=readonly>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Contraseña">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="conpassword" placeholder="Confirmar contraseña">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
         
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-danger btn-block">Restaurar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>     
      
    </div>    
    
    <!-- /.card-body -->
  </div>

  <!-- /.card -->
</div>


<!-- jQuery -->
<script src="../styles/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../styles/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../styles/dist/js/adminlte.min.js"></script>

</body>
</html>
<?php } ?>