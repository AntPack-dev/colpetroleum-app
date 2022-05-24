<?php

include('db/ConnectDB.php');
include('functions/OperationsUser.php');

session_start();

$log = new LoginUser();

$errors = array();

if(!empty($_POST))
{
    $user = $mysqli->real_escape_string($_POST['username']);
    $password = $mysqli->real_escape_string($_POST['password']);

    if($log->IsNullLogin($user, $password))
    {
        $errors[] = "Debe diligenciar sus credenciales";
    }
    

    $errors[] = $log->Login($user, $password);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CPS MTTO</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="styles/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="styles/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="styles/dist/css/adminlte.min.css">
  <link rel="shortcut icon" href="styles/dist/img/icono.png">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-danger">
    <div class="card-header text-center">
    <img src="styles/dist/img/logo.png" style="width: 200px;">
    </div>
    
    <div class="card-body">
      

      <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">

      <?php echo $log->ResultBlockLogin($errors);?>

        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" placeholder="Correo corporativo o Usuario">
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
        <div class="row">
          
          <!-- /.col -->
          <div class="col-12">
            <button type="submit" class="btn btn-dark btn-block">Iniciar Sesión</button>
          </div>          
          <!-- /.col -->
        </div>
      </form>

      
      <!-- /.social-auth-links -->

    
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="styles/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="styles/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="styles/dist/js/adminlte.min.js"></script>
</body>
</html>
