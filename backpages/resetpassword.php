<?php

$user = $mysqli->real_escape_string($_GET['user']);

$message = "";

$consult = new RegisterUsers();
$reset = new ResetUser();

$errors = array();

$email = $consult->getValue('email_user','users','token',$user);
$firstname = $consult->getValue('first_name','users','token',$user);
$secondname = $consult->getValue('second_name','users','token',$user);


if(!empty($_POST))
{
  $emails = $mysqli->real_escape_string($_POST['email']); 
 

  if(!$consult->EmailExist($emails))
  {
    $errors[] = "El correo de este usuario no existe o fue modificado. Por favor noitificarlo al área de sistemas.";
  }
  
  if(count($errors) == 0)
  {   

    $token = $reset->GenereTokenPass($token, $user);

    $url = 'http://'.$_SERVER["SERVER_NAME"].'/pages/recoverpassword?user='.$user.'&token='.$token;

    $affair = "RESTAURACIÓN DE CUENTA - CPS MTTO";

    $name = $firstname." ".$secondname;
        
    if($reset->SendEmailRestart($emails, $firstname, $affair, $name, $url))
    {
      $message = "<div class='alert alert-success alert-dismissible'>
      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
      <h5><i class='icon fas fa-check'></i>Exito</h5>Se ha enviado el formulario de restauración al correo electrónico:<br>
      <b>".$emails.".</b> Notificar al usuario la llegada del correo electrónico.</div>";
    }
    else
    {
      $message = "<div class='alert alert-danger alert-dismissible'>
      <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
      <h5><i class='icon fas fa-error'></i>Error</h5>Error al enviar el formulario de restauración. Notificarlo al área de sistemas.</div>";

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

            <div class="row">
            
            </div>             

            <div class="card-body">
            <?php 
            echo $message; 
            echo $consult->ResultBlockError($errors);

            ?>
           
                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">              

                <div class="card card-danger card-outline ">
                <input type="hidden" class="form-control" value="<?php echo $email; ?>" name="email">               

                    <div class="card-body box-profile">                        

                        <h3 class="profile-username text-center"><?php echo $firstname." ".$secondname; ?></h3>

                        <p class="text-muted text-center"><?php echo $role;  ?></p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Correo Eletrónico</b> <a class="float-right"><?php echo $email; ?></a>
                            </li>                          
                            
                        </ul>

                    </div>                    

                </div>  

                <button type="submit" class="btn btn-danger btn-block">Enviar Formulario</button>             
                
                </form>
                      

            </div> 

        </div>           
      </div>          
    </div>
  </div>


</section>





