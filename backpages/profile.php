<?php

$id_user = $_SESSION['id_user'];
$token_user = $_SESSION['token'];

$consult = new RegisterUsers();
$update = new UserFunctions();
$time = $consult->DateUsers();

$errors = array();
$message = "";

$first_name = $consult->getValue('first_name','users','token',$token_user);
$second_name = $consult->getValue('second_name','users','token',$token_user);
$user_name = $consult->getValue('user_name','users','token',$token_user);



if(isset($_POST['DetailsUser']))
{
    $firstname = $mysqli->real_escape_string($_POST['firstname']);
    $secondname = $mysqli->real_escape_string($_POST['secondname']);
    $username = $mysqli->real_escape_string($_POST['username']);

    if($update->IsNullDetailsUser($firstname, $secondname, $username))
    {
        $errors[] = "No debe quedar el formulario nulo.";
    }
    
    if(count($errors) == 0)
    {
        $updates = $update->UpdateUserDetails($username, $firstname, $secondname, $time, $token_user);

        if($updates > 0)
        {
            $message = "<div class='alert alert-danger alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h5><i class='icon fas fa-check'></i>Exito</h5>Error al actualizar sus datos.</div>";
        }
        else
        {
            $message = "<div class='alert alert-success alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h5><i class='icon fas fa-check'></i>Exito</h5>Se ha actualizado sus datos correctamente. Por favor recargue la página.</div>";
        }
    }

    
}

$error = array();

$messa = "";


if(isset($_POST['Password']))
{
    $password = $mysqli->real_escape_string($_POST['password']);
    $conpassword = $mysqli->real_escape_string($_POST['conpassword']);


    if($consult->IsNullPassword($password, $conpassword))
    {
        $error[] = "Por favor, diligenciar el formulario.";
    }

    if(!$consult->ValidePassword($password, $conpassword))
    {
        $error[] = "Las contraseñas diligenciadas, no coinciden.";
    }

    if(count($error) == 0)
    {
        $passhash = $consult->HashPassword($password);

        $updapass = $update->UpdatePassword($passhash, $time, $token_user);

        if($updapass > 0)
        {
            $messa = "<div class='alert alert-danger alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h5><i class='icon fas fa-check'></i>Exito</h5>Error al actualizar sus datos.</div>";
        }
        else
        {
            $messa = "<div class='alert alert-success alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h5><i class='icon fas fa-check'></i>Exito</h5>Se ha actualizado sus datos correctamente. Por favor recargue la página.</div>";
        }
    }


}



?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Datos del Usuario</h5>            
                </div>
                            
                
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                        <div class="card-body">

                            <div class="callout callout-info">
                                <h5>Sugerencias de actualización de usuario:</h5>

                                <p>
                                    1) Sobre escribimos la información del usuario.<br>   
                                    2) Presionamos sobre el botón <b>Actualizar</b>.                                                          
                                    3) <b>NOTA:</b> Debe recargar la página despues de la actualización de datos.<br>                                   
                                                                          
                                </p>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Nombre</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" name="firstname" value="<?php echo $first_name; ?>">

                                <label for="exampleInputEmail1">Apellido</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" name="secondname" value="<?php echo $second_name; ?>">

                                <label for="exampleInputEmail1">Usuario</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" name="username" value="<?php echo $user_name; ?>">

                            </div>

                        </div>
                        <?php
                        echo $consult->ResultBlockError($errors);
                         echo $message; 
                         ?>
                        
                        <div class="card-footer">
                            <input type="submit" class="btn btn-danger" value="Actualizar" name="DetailsUser">
                            <!-- <button type="submit" class="btn btn-danger">Actualizar</button> -->
                        </div>
                    </form>
                    
            
                </div>           
            </div>  
            
            <div class="col-md-6">
                <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Actualizar Contraseña</h5>            
                </div>            
                
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                        <div class="card-body">

                            <div class="callout callout-info">
                                <h5>Sugerencias de actualización de usuario:</h5>

                                <p>
                                    1) Sobre escribimos la nueva contraseña.<br>                                                           
                                    2) Confirmamos la nueva contraseña.<br>   
                                    3) Presionamos sobre el botón <b>Actualizar</b>.<br>                                                        
                                    4) <b>NOTA:</b> Debe recargar la página despues de la actualización de datos.<br>                                   
                                    5) <b>NOTA:</b> Recuerde combinar con simbolos, números y letras en la nueva contrase. (Ejemplo: .-*_+@,0-9, A-Z, a-z)<br>
                                                                          
                                </p>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Contraseña</label>
                                <input type="password" class="form-control" name="password" id="exampleInputEmail1">

                                <label for="exampleInputEmail1">Confirmar Contraseña</label>
                                <input type="password" class="form-control" name="conpassword" id="exampleInputEmail1">
                                
                            </div>

                        </div>

                        <?php
                            echo $consult->ResultBlockError($error);
                            echo $messa; 
                         ?>
                        
                        <div class="card-footer">
                        <input type="submit" class="btn btn-danger" value="Actualizar" name="Password">
                            <!-- <button type="submit" class="btn btn-danger">Actualizar</button> -->
                        </div>
                    </form>
            
                </div>           
            </div>
        </div>
    </div>

</section>