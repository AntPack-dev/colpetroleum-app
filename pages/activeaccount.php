<?php
include('../db/ConnectDB.php');
include('../functions/OperationsUser.php');

if(empty($_GET['user']))
{
    header("Location: ../");
}
if(empty($_GET['val']))
{
    header("Location: ../");
}

$register = $mysqli->real_escape_string($_GET['user']);
$token = $mysqli->real_escape_string($_GET['val']);

$errors = array();
$message = "";


$verific = new RegisterUsers();

if(!$verific->VerificRegPassword($register, $token))
{
    header("Location: ../");

    exit;
}
if(!$verific->VerificTokenUser($token))
{
    header("Location: ../");

    exit;
}

if(!empty($_POST))
{
  $username = $mysqli->real_escape_string($_POST['username']);
  $password = $mysqli->real_escape_String($_POST['password']);
  $conpassword = $mysqli->real_escape_string($_POST['conpassword']);

  if($verific->UserExist($username))
  {
    $errors[] = "El usuario $username ya se encuentra registrado.";
  }

  if($verific->IsNullUserName($username, $password, $conpassword))
  {
    $errors[] = "Debe diligenciar el formulario completamente.";
  }

  if(!$verific->ValidePassword($password, $conpassword))
  {
    $errors[] = "Las contraseñas diligenciadas, no coinciden.";
  }

  if(count($errors) == 0)
  {
    $tokenuser = $verific->GenerateToken();

    $passhash = $verific->HashPassword($password);    

    $verific->RegisterPassword($username,$passhash,$tokenuser,$register,$token);
    
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
    <link rel="shortcut icon" href="../styles/dist/img/icono.png">
  </head>
<body class="hold-transition login-page">

<div class="login-box" >
  <!-- /.login-logo -->
  <div class="card card-outline card-danger" >
    <div class="card-header text-center text-dark">
      
      <img src="../styles/dist/img/logo.png" style="width: 150px;">
    </div>

    <?php

    if($verific->VerificActiveUser($tokenuser))
    {

    ?>  

    <div class="card-body">

      <div class="callout callout-success text-dark">
          <h5>Activación de su cuenta.</h5>

          <p>
              Su cuenta se encuentra en estado <b>ACTIVO</b>. Por favor dar clic en el botón iniciar para ingresar a 
              la plataforma.      
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

        <?php echo $verific->ResultBlockError($errors);?> 


        <div class="callout callout-danger text-dark">
            <h5>Activación de su cuenta.</h5>

            <p>
                Ingrese un nombre de usuario y la contraseña para realizar la activación de su cuenta.
                Se recomienda aplicar a su contraseña la combinación de números y simbolos . (*, -, +, ., @, _, 0-9).                                      
                
            </p>
        </div>
       
      
     
      <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
      <div class="input-group mb-3">
          <input type="text" class="form-control" value="<?php echo $username;?>" name="username" placeholder="Nombre de usuario">
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
          <input type="password" class="form-control" name="conpassword" placeholder="Confirma contraseña">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
         
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-danger btn-block">Activar</button>
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

<?php  } ?>