<?php
//Clases de administración de funciones de los usuarios

class RegisterUsers
{

    //REGISTRAR USUARIO

    //Valida los datos de registro
    function IsNullUser($firstname, $secondmname, $phoneuser, $emailuser)
    {
        if(strlen(trim($firstname)) < 1 ||
        strlen(trim($secondmname)) < 1 ||
        strlen(trim($phoneuser)) < 1 ||
        strlen(trim($emailuser)) < 1 )
        {
            return true;
            }else{
            return false;
        }
    }

    //Valida la contraseñas al registrar
    function IsNullPassword($pass, $pass_con)
    {
        if(strlen(trim($pass)) < 1 || strlen(trim($pass_con)) < 1)
        {
            return true;
            }else{
            return false;
        }
    }

    //Valida el correo electrónico
    function IsEmail($emailuser)
    {
        if(filter_var($emailuser, FILTER_VALIDATE_EMAIL))
        {
            return true;
            }else{
            return false;
        }
    }

    //Valida y compara las contraseñas
    function ValidePassword($var1, $var2)
    {
        if(strcmp($var1, $var2) !== 0)
        {
            return false;
            }else{
            return true;
        }
    }

    //Verifica si existe el mismo usuario
    function UserExist($username)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT user_name FROM users WHERE user_name = ? LIMIT 1"); //Agregar Sentencia SQL
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;
        $stmt->close();

        if($num > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //Valida si existe un correo ya registrado
    function EmailExist($emailuser)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id_user FROM users WHERE email_user = ? LIMIT 1"); //Agregar sentencia SQL
        $stmt->bind_param("s", $emailuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;
        $stmt->close();

        if($num > 0)
        {
            return true;
            }else{
            return false;
        }
    }

    //Genera Token usuario
    function GenerateToken()
    {
        $gen = md5(uniqid(mt_rand(), false));
        return $gen;
    }

    //Encriptación de la contraseña
    function HashPassword($password)
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $hash;
    }

    //Muestra los avisos de los errores
    function ResultBlockError($errors)
    {
        if(count($errors) > 0)
        {
            echo "<div class='alert alert-warning alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h5><i class='icon fas fa-exclamation-triangle'></i>Espera</h5>";
            foreach($errors as $error)
            {
                echo $error. "<br>";
            }            
            echo "</div>";
        }
    }

    //Devuelve la fecha para registrar usuario
    function DateUsers()
    {
        date_default_timezone_set('America/Bogota'); 

        $date = date("Y-m-d H:i:s");

        return $date;
    }

    //Realiza el registro de usuario
    function RegisterUser($names, $secondnames, $phoneuser, $emailuser, $date, $dates, $activation, $registerpass, $token)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO users (first_name, second_name, phone_user, email_user, date_register, last_modification, activation, register_password, token_register) VALUES (?,?,?,?,?,?,?,?,?)");
        $stmt->bind_param('ssssssiis', $names, $secondnames, $phoneuser, $emailuser, $date, $dates, $activation, $registerpass, $token);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }
    }

    //Realiza consulta a la base de datos, dependiendo la solicitud
    function getValue($campo, $table, $campowhere, $value)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT $campo FROM $table WHERE $campowhere = ? LIMIT 1"); 
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($_campo);
            $stmt->fetch();
            return $_campo;
        }
        else
        {
            return null;
        }
    }

    //Realiza el envio de datos al usuario
    function SendEmail($emailuser, $firstname, $affair, $nombre, $url)
    {    
        require_once '../bookstores/PHPMailer/PHPMailerAutoload.php';

        
        
        $template = file_get_contents('../backpages/template.php');
        $template = str_replace("{{names}}", $nombre, $template);
        $template = str_replace("{{action_url_2}}", $url, $template);
        $template = str_replace("{{action_url_1}}", $url, $template);      


        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = 'mail.supremecluster.com';  
        $mail->Port = 2525;
        $mail->Username = 'cpsmtto@colpetroleumservices.com';
        $mail->Password = 'qcJG9s4F8$';     
        

        $mail->setFrom('cpsmtto@colpetroleumservices.com','CPS MTTO');
        $mail->addAddress($emailuser, $firstname);
        $mail->wordwrap = 50;

        $mail->Subject = $affair;
        $mail->Body = $template;        
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        if($mail->send())       
        return true;
        else 
        return false;     
    
    }

    //Activa el usuario en el aplicativo
    function ActiveUser($iduser)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE users SET activation = 1 WHERE id_user = ?"); //Agregar setencia SQL
        $stmt->bind_param('s', $iduser);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    //Valida los datos para terminar el proceso de activación
    function IsNullUserName($username, $password, $conpassword)
    {
        if(strlen(trim($username)) < 1 || strlen(trim($password)) <1 || strlen(trim($conpassword)) < 1)
        {
            return true;
            }else{
            return false;
        }
    }

    //Verifica si existe el token del usuario registrado
    function VerificTokenUser($token)
    {
        global $mysqli;
        
        $stmt = $mysqli->prepare("SELECT token_register FROM users WHERE token_register = ? LIMIT 1");
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($token_s);
        $stmt->fetch();

        if($token_s == $token)
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    //Registra la contraseña del usuario
    function RegisterPassword($username, $pass_hash, $tokenuser, $register, $token)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE users SET user_name = ?, password = ?, register_password = 1, activation = 1, token = ?, token_register='' WHERE id_user = ? AND token_register = ?");
        $stmt->bind_param('sssis', $username, $pass_hash, $tokenuser, $register, $token);

        if($stmt->execute())
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //Verifica si existe un registro de password
    function VerificRegPassword($user, $token)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT register_password FROM users WHERE id_user = ? AND token_register = ? LIMIT 1"); //Agregar setencia SQL.
        $stmt->bind_param('is', $user, $token);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($register);
        $stmt->fetch();

        if($register == 0)
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    //Verifica si el usuario esta activado
    function VerificActiveUser($tokenuser)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT activation FROM users WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($active);
        $stmt->fetch();

        if($active == 1)
        {
            return true;
        }
        else
        {
            return false;
        }

    }


}

class LoginUser
{

    //Valida las craedenciales al iniciar sesión
    function IsNullLogin($user, $password)
    {
        if(strlen(trim($user)) < 1 || strlen(trim($password)) < 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //Aviso de login
    function ResultBlockLogin($errors)
    {
        if(count($errors) > 0)
        {
            echo "<div class='alert alert-danger alert-dismissible'>
            <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
            <h5><i class='icon fas fa-lock'></i>Alerta</h5>";
            foreach($errors as $error)
            {
                echo $error. "<br>";
            }            
            echo "</div>";
        }
    }   

    //Realiza la verificación del usuario
    function Login($user, $password)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id_user, token, password FROM users WHERE user_name = ? || email_user = ? LIMIT 1"); 
        $stmt->bind_param("ss", $user, $user);
        $stmt->execute();
        $stmt->store_result();
        $rows = $stmt->num_rows;

        $loginuser = new LoginUser();

        if($rows > 0)
        {
            if($loginuser->IsActive($user))
            {
                $stmt->bind_result($iduser, $tokenuser, $passwd);
                $stmt->fetch();

                $validaPassw = password_verify($password, $passwd);

                if($validaPassw)
                {
                    date_default_timezone_set('America/Bogota');
                    $time = date("Y-m-d H:i:s");                                                         

                    $loginuser->LastSession($iduser, $time);
                    $_SESSION['id_user'] = $iduser;
                    $_SESSION['token'] = $tokenuser;

                    
                    header("Location: pages/");

                }
                else
                {
                    $errors = "Acceso denegado.";
                }
            }
            else
            {
                $errors = "Acceso denegado.";
            }
        }
        else
        {
            $errors = "Acceso denegado.";
        }

        return $errors;

    }     

    //Actualiza fecha y hora de inicio de sesión
    function LastSession($iduser, $time)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE users SET last_session= ?, token_password='', password_resquest=0 WHERE id_user = ?"); //Agregar sentencia SQL
        $stmt->bind_param('ss', $time, $iduser);
        $stmt->execute();
        $stmt->close();
    }

    //Cierra la sesión y actualiza el estado de cuenta
    function CloseSession($iduser, $time)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE users SET last_session=? WHERE id_user = ?"); //Agregar sentencia SQL
        $stmt->bind_param('ss', $time, $iduser);
        $stmt->execute();
        $stmt->close();

    }

    //Verifica si el usuario esta activado
    function IsActive($user)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT activation FROM users WHERE user_name = ? || email_user = ? LIMIT 1"); //Agregar sentencia SQL
        $stmt->bind_param('ss', $user, $user);
        $stmt->execute();
        $stmt->bind_result($activation);
        $stmt->fetch();

        if($activation == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }   

}

class UserFunctions{

    //Realiza consulta a la base de datos, dependiendo la solicitud
    function ValueUser($campo, $table, $campowhere, $value)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT $campo FROM $table WHERE $campowhere = ? LIMIT 1");
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($_campo);
            $stmt->fetch();
            return $_campo;
        }
        else
        {
            return null;
        }
    }

    //Asignar permisos al usuario despues de ser registrado.
    function AsignPermits($iduser, $useremail, $idpermit, $idmodule)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("INSERT INTO asign_permits (user_id_asign, mail_user_asign, permit_user_id, id_module_permit) VALUES(?,?,?,?)");
        $stmt->bind_param('isii', $iduser, $useremail, $idpermit, $idmodule);

        if($stmt->execute())
        {
            return $mysqli->insert_id;
            }else{
            return 0;
        }
    }

    //Elimina los permisos de manera individual
    function DeletePermitUser($idpermit)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("DELETE FROM asign_permits WHERE id_asign_permits = ?");
        $stmt->bind_param('i', $idpermit);

        if($stmt->execute())
        {
            return true;
            }else{
            return false;

        }
    }

    //Elimina permisos al usuario
    function DeletePermitsUser($userid)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("DELETE FROM grants WHERE user_id = ?");
        $stmt->bind_param('i', $userid);

        if($stmt->execute())
        {
            return true;
            }else{
            return false;

        }

    }

    //Valida si existe esos datos en formulario
    function IsNullDetailsUser($username, $firstname, $secondname)
    {
        if(strlen(trim($firstname)) < 1 ||
        strlen(trim($secondname)) < 1 ||
        strlen(trim($username)) == null )
        {
            return true;
            }else{
            return false;
        }

    }

    //Actualiza los datos personales del usuario, menos el correo electrónico
    function UpdateUserDetails($username, $firstname, $secondname, $time, $token)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE users SET user_name = ?, first_name = ?, second_name = ?, last_modification = ? WHERE token = ?"); //Agregar sentencia SQL
        $stmt->bind_param('sssss', $username, $firstname, $secondname, $time, $token);
        $stmt->execute();
        $stmt->close();
    }

    //Actualiza la contraseña desde el apartado del usuario
    function UpdatePassword($password, $time, $token)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE users SET password = ?, last_modification_password = ? WHERE token = ?"); //Agregar sentencia SQL
        $stmt->bind_param('sss', $password, $time, $token);
        $stmt->execute();
        $stmt->close();
    }

    //Permisos de usuarios
    function AdminUsers($tokenuser)
    {
        global $mysqli;

        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            while($stmt->fetch())
            {
                if($rol == 4 && $module == 1)
                {
                    $settings = "<li class='nav-item'>
                    <a href='#' class='nav-link'>
                        <i class='text-danger nav-icon fas fa-users-cog'></i>  
                        <p>
                        Administrador de usuarios
                        <i class='text-danger fas fa-angle-right right'></i>              
                        </p>
                    </a>
                    <ul class='nav nav-treeview'>
                        <li class='nav-item'>
                        <a href='administrator' class='nav-link'> 
                            <i class='text-danger fas fa-users nav-icon'></i>
                            <p>Gestionar usuarios</p>
                        </a>
                        </li>            
                        
                        
                    </ul>
                    </li> ";
                }
            }



        }
        else
        {
            return null;
        }

        return $settings;

    }

    //Permisos de analísis de costos
    function AdminAnalysis($tokenuser)
    {
        global $mysqli;

        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            $settings = "
            <li class='nav-item'>
            <a href='#' class='nav-link'>
              <i class='text-danger nav-icon fas fa-donate'></i>
              <p>
                Análisis de costos
                <i class='text-danger fas fa-angle-right right'></i>                
              </p>
            </a>
            <ul class='nav nav-treeview'>";

            while($stmt->fetch())
            {
                if($rol == 1 && $module == 2)
                {
                    $settings.="
                    <li class='nav-item'>
                        <a href='wareanalisys' class='nav-link'>
                        <i class='text-danger fas fa-pencil-alt nav-icon'></i>
                        <p>Registrar Análisis de costos</p>
                        </a>
                    </li>
                    
                    ";
                }

                if($rol == 2 && $module == 2)
                {
                    $settings.="
                    <li class='nav-item'>
                        <a href='recordanalisys' class='nav-link'>
                        <i class='text-danger fas fa-boxes nav-icon'></i>
                        <p>Historial Análisis de costos</p>
                        </a>
                    </li>  
                    ";
                }

                if($rol == 4 && $module == 2)
                {
                    $settings.="
                    <li class='nav-item'>
                        <a href='recordanalisys' class='nav-link'>
                        <i class='text-danger fas fa-boxes nav-icon'></i>
                        <p>Historial Análisis de costos</p>
                        </a>
                    </li>  

                    <li class='nav-item'>
                        <a href='wareanalisys' class='nav-link'>
                        <i class='text-danger fas fa-pencil-alt nav-icon'></i>
                        <p>Registrar Análisis de costos</p>
                        </a>
                    </li>
                    ";
                }


            }

            $settings .="</ul>
            </li>";

        }
        else
        {
            return null;
        }

        return $settings;


    }

    //Permisos para acceder a los conceptos
    function AdminConcepts($tokenuser)
    {
        global $mysqli;

        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            while($stmt->fetch())
            {
                if($rol == 3 && $module == 3)
                {
                    $settings = "<li class='nav-item'>
                    <a href='#' class='nav-link'>
                      <i class='text-danger nav-icon fas fa-sitemap'></i>
                      <p>
                        Conceptos
                        <i class='text-danger fas fa-angle-right right'></i>                
                      </p>
                    </a>
                    <ul class='nav nav-treeview'>
                      <li class='nav-item'>
                        <a href='concepts' class='nav-link'>
                          <i class='text-danger fas fa-hand-holding-water nav-icon'></i>
                          <p>Control de conceptos</p>
                        </a>
                      </li>
        
                    </ul>
                  </li> ";
                }

                if($rol == 1 && $module == 3)
                {
                    $settings = "<li class='nav-item'>
                    <a href='#' class='nav-link'>
                      <i class='text-danger nav-icon fas fa-sitemap'></i>
                      <p>
                        Conceptos
                        <i class='text-danger fas fa-angle-right right'></i>                
                      </p>
                    </a>
                    <ul class='nav nav-treeview'>
                      <li class='nav-item'>
                        <a href='concepts' class='nav-link'>
                          <i class='text-danger fas fa-hand-holding-water nav-icon'></i>
                          <p>Control de conceptos</p>
                        </a>
                      </li>
        
                    </ul>
                  </li> ";
                }

                if($rol == 4 && $module == 3)
                {
                    $settings = "<li class='nav-item'>
                    <a href='#' class='nav-link'>
                      <i class='text-danger nav-icon fas fa-sitemap'></i>
                      <p>
                        Conceptos
                        <i class='text-danger fas fa-angle-right right'></i>                
                      </p>
                    </a>
                    <ul class='nav nav-treeview'>
                      <li class='nav-item'>
                        <a href='concepts' class='nav-link'>
                          <i class='text-danger fas fa-hand-holding-water nav-icon'></i>
                          <p>Control de conceptos</p>
                        </a>
                      </li>
        
                    </ul>
                  </li> ";
                }
            }
        }
        else
        {
            return null;
        }

        return $settings;
    }

    //Permisos para acceder a los equipos
    function AdminTeams($tokenuser)
    {
        global $mysqli;


        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ? GROUP BY id_module_permit");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            $settings = "<li class='nav-item'>
            <a href='#' class='nav-link'>
              <i class='text-danger nav-icon fas fa-truck-moving'></i>
              <p>

                Gestión de equipos
                <i class='text-danger fas fa-angle-right right'></i>                
              </p>
            </a>
            <ul class='nav nav-treeview'>";

            while($stmt->fetch())
            {
                if($rol >= 1 && $module == 4)
                {
                    $settings.= "<li class='nav-item'>
                    <a href='unitsrsu' class='nav-link'>
                      <i class='text-danger fas fa-trailer nav-icon'></i>
                      <p>Unidades RSU</p>
                    </a>
                  </li>";
                }

                if($rol >= 1 && $module == 5)
                {
                    $settings.= "<li class='nav-item'>
                    <a href='failsteams' class='nav-link'>
                      <i class='text-danger fas fa-tools nav-icon'></i>
                      <p>Fallas o Avería</p>
                    </a>
                  </li>";
                }
                if($rol >= 1 && $module == 6)
                {
                    $settings.= "<li class='nav-item'>
                    <a href='mantteams' class='nav-link'>
                      <i class='text-danger fas fa-hard-hat nav-icon'></i>
                      <p>Mantenimientos</p>
                    </a>
                  </li>";
                }

            }

            $settings .="</ul>
            </li>";
        }
        else
        {
            return null;
        }

        return $settings;
    }

    //Permisos para acceder a almacenes
    function AdminWarehouse($tokenuser)
    {
        global $mysqli;


        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ? GROUP BY id_module_permit");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            $settings = "<li class='nav-item'>
            <a href='#' class='nav-link'>
              <i class='text-danger nav-icon fas fa-warehouse'></i>
              <p>
                Inventario
                <i class='text-danger fas fa-angle-right right'></i>                
              </p>
            </a>
            <ul class='nav nav-treeview'>";

            while($stmt->fetch())
            {
                if($rol >= 1 && $module == 7)
                {
                    $settings.="<li class='nav-item'>
                    <a href='adminwarehouse' class='nav-link'>
                      <i class='text-danger fas fa-boxes nav-icon'></i>
                      <p>Gestionar almacenes - inventario</p>
                    </a>
                  </li>

                  <li class='nav-item'>
                    <a href='consumables' class='nav-link'>
                      <i class='text-danger fas fa-file-invoice-dollar nav-icon'></i>
                      <p>Consumibles</p>
                    </a>
                  </li>
                  
                  ";
                }
            }

            $settings .="</ul>
            </li>";
        }
        else
        {
            return null;
        }

        return $settings;
    }

    //Permitir Gestión de procedimientos
    function AdminProcedures($tokenuser)
    {
        global $mysqli;


        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ? GROUP BY id_module_permit");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            $settings = "<li class='nav-item'>
            <a href='#' class='nav-link'>
              <i class='text-danger nav-icon fas fa-warehouse'></i>
              <p>
                Procedimientos
                <i class='text-danger fas fa-angle-right right'></i>                
              </p>
            </a>
            <ul class='nav nav-treeview'>";

            while($stmt->fetch())
            {
                if($rol >= 1 && $module == 10)
                {
                    $settings.="<li class='nav-item'>
                    <a href='adminprocedures.php' class='nav-link'>
                      <i class='text-danger fas fa-boxes nav-icon'></i>
                      <p>Gestionar Procedimientos</p>
                    </a>
                  </li>
                  ";
                }
            }

            $settings .="</ul>
            </li>";
        }
        else
        {
            return null;
        }

        return $settings;
    }

    //Permisos Gestión de unidades RSU
    function AdminRegisterRsu($tokenuser)
    {
        global $mysqli;
        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            while($stmt->fetch())
            {
                if($rol == 1 && $module == 4)
                {
                    $settings = "
                    <button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-default'>
                    Registrar Unidad
                    </button> 
                    <button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-default-contract'>
                    Asignar contrato
                    </button>  ";
                }

                if($rol == 4 && $module == 4)
                {
                    $settings = "
                    <button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-default'>
                    Registrar Unidad
                    </button> 
                    <button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-default-contract'>
                    Asignar contrato
                    </button>  ";
                }
            }
        }
        else
        {
            return null;
        }

        return $settings;
    }

    //Permisos Gestión de procedimientos
    function AdminRegisterProcedures($tokenuser)
    {
        global $mysqli;
        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            while($stmt->fetch())
            {
                if($rol == 4 && $module == 10)
                {
                    $settings = "
                    <a href='/pages/adminprocedurescreateedit.php' class='btn btn-success'>
                    Registrar Procedimiento
                    </a> ";
                }

                /*if($rol == 4 && $module == 4)
                {
                    $settings = "
                    <button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-default'>
                    Registrar Unidad
                    </button> 
                    <button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-default-contract'>
                    Asignar contrato
                    </button>  ";
                }*/
            }
        }
        else
        {
            return null;
        }

        return $settings;
    }

    //Permisos Gestión de requisiciones
    function AdminRequisitions($tokenuser)
    {
        global $mysqli;
        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            while($stmt->fetch())
            {
                if($rol == 4 && $module == 11)
                {
                    $settings = "<li class='nav-item'>
                    <a href='/pages/adminrequisition.php' class='nav-link'>
                    <i class='text-danger nav-icon fas fa-file-alt'></i>
                    Administrar requisiciones
                    </a></li>";
                }

                /*if($rol == 4 && $module == 4)
                {
                    $settings = "
                    <button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-default'>
                    Registrar Unidad
                    </button>
                    <button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-default-contract'>
                    Asignar contrato
                    </button>  ";
                }*/
            }
        }
        else
        {
            return null;
        }

        return $settings;
    }

    //Permisos Gestión de unidades RSU
    function AdminRegisterTeams($tokenuser)
    {
        global $mysqli;
        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            while($stmt->fetch())
            {
                if($rol == 1 && $module == 4)
                {
                    $settings = "
                    <button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-default'>
                    Registrar Equipo
                    </button> ";

                }

                if($rol == 4 && $module == 4)
                {
                    $settings = "
                    <button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-default'>
                    Registrar Equipo
                    </button> ";

                }
            }
        }
        else
        {
            return null;
        }

        return $settings;
    }

    //Permisos de gestión de equipos
    function AdminUpdateTeams($tokenuser)
    {
        global $mysqli;
        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            while($stmt->fetch())
            {
                if($rol == 3 && $module == 4)
                {
                    $settings = "
                    <button type='submit' class='btn btn-danger' name='btnupdateteams' data-toggle='modal' data-target=''>
                        Actualizar información
                    </button>  ";

                }
                if($rol == 4 && $module == 4)
                {
                    $settings = "
                    <button type='submit' class='btn btn-danger' name='btnupdateteams' data-toggle='modal' data-target=''>
                        Actualizar información
                    </button>  ";
                }
            }
        }
        else
        {
            return null;
        }

        return $settings;
    }

    //Permisos de gestión de equipos
    function AdminUpdateTeamsImgOne($tokenuser)
    {
        global $mysqli;
        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            while($stmt->fetch())
            {
                if($rol == 3 && $module == 4)
                {
                    $settings = "
                    <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal-foto-one'><i class='fas fa-edit'></i></button> ";

                }

                if($rol == 1 && $module == 4)
                {
                    $settings = "
                    <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal-foto-one'><i class='fas fa-edit'></i></button> ";
                }
                if($rol == 4 && $module == 4)
                {
                    $settings = "
                    <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal-foto-one'><i class='fas fa-edit'></i></button> ";

                }
            }
        }
        else
        {
            return null;
        }

        return $settings;
    }

    //Permisos de gestión de equipos
    function AdminUpdateTeamsImgTwo($tokenuser)
    {
        global $mysqli;
        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            while($stmt->fetch())
            {
                if($rol == 3 && $module == 4)
                {
                    $settings = "<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal-foto-dos'><i class='fas fa-edit'></i> ";

                }

                if($rol == 1 && $module == 4)
                {
                    $settings = "<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal-foto-dos'><i class='fas fa-edit'></i> ";

                }
                if($rol == 4 && $module == 4)
                {
                    $settings = "<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal-foto-dos'><i class='fas fa-edit'></i> ";

                }
            }
        }
        else
        {
            return null;
        }

        return $settings;
    }

    //Permite registra una actividad
    function AdminRegisterActivity($tokenuser)
    {
        global $mysqli;
        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            while($stmt->fetch())
            {
                if($rol == 3 && $module == 4)
                {
                    $settings = "<button type='submit' class='btn btn-danger' name='btnregisteractivity' data-toggle='modal' data-target='#modal-reg-act'>
                    Registrar Actividad
                </button> ";

                }

                if($rol == 1 && $module == 4)
                {
                    $settings = "<button type='submit' class='btn btn-danger' name='btnregisteractivity' data-toggle='modal' data-target='#modal-reg-act'>
                    Registrar Actividad
                </button>";

                }

                if($rol == 4 && $module == 4)
                {
                    $settings = "<button type='submit' class='btn btn-danger' name='btnregisteractivity' data-toggle='modal' data-target='#modal-reg-act'>
                    Registrar Actividad
                </button>";

                }
            }
        }
        else
        {
            return null;
        }

        return $settings;
    }

    //Permite registra una Inspección
    function AdminRegisterInspection($tokenuser)
    {
        global $mysqli;
        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            while($stmt->fetch())
            {
                if($rol == 3 && $module == 4)
                {
                    $settings = "<button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-mants'><i class='fas fa-plus'></i></button>  ";

                }

                if($rol == 1 && $module == 4)
                {
                    $settings = "<button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-mants'><i class='fas fa-plus'></i></button> ";

                }

                if($rol == 4 && $module == 4)
                {
                    $settings = "<button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-mants'><i class='fas fa-plus'></i></button> ";

                }
            }
        }
        else
        {
            return null;
        }

        return $settings;
    }

    //Permite registrar una falla o avería
    function AdminRegisterFails($tokenuser)
    {
        global $mysqli;
        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            while($stmt->fetch())
            {
                if($rol == 1 && $module == 5)
                {
                    $settings = "<button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-default'>
                    Registrar Falla o Avería
                  </button>";

                }

                if($rol == 4 && $module == 5)
                {
                    $settings = "<button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-default'>
                    Registrar Falla o Avería
                  </button>";

                }

               
            }
        }
        else
        {
            return null;
        }

        return $settings;
    }

    //Permite registrar un reporte de mantenimiento
    function AdminRegisterMant($tokenuser)
    {
        global $mysqli;
        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            while($stmt->fetch())
            {
                if($rol == 1 && $module == 6)
                {
                    $settings = "<button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-default'>
                    Registrar Mantenimiento
                  </button>";

                }

                if($rol == 4 && $module == 6)
                {
                    $settings = "<button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-default'>
                    Registrar Mantenimiento
                  </button>";

                }

               
            }
        }
        else
        {
            return null;
        }

        return $settings;
    }

    //Permite registrar almacén
    function AdminRegisterWarehouse($tokenuser)
    {
        global $mysqli;
        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            while($stmt->fetch())
            {
                if($rol == 1 && $module == 7)
                {
                    $settings = "<button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-default'>
                    Registrar almacén
                  </button>";

                }

                if($rol == 4 && $module == 7)
                {
                    $settings = "<button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-default'>
                    Registrar almacén
                  </button>";

                }

               
            }
        }
        else
        {
            return null;
        }

        return $settings;
    }

    //Permite realizar registrso de activos en el almacén
    function AdminActiviesWarehouse($tokenuser)
    {
        global $mysqli;
        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);

            while($stmt->fetch())
            {
                if($rol == 1 && $module == 7)
                {
                    $settings = "<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal-default'>
                    Registrar Activo
                  </button>    
                  
                  <button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-default-ea'>
                    Gestionar Entrada Almacén
                  </button> 
  
                  <button type='button' class='btn btn-danger' data-toggle='modal' data-target='#modal-default-sa'>
                    Registrar Salida Almacén
                  </button> ";

                }

                if($rol == 4 && $module == 7)
                {
                    $settings = "<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modal-default'>
                    Registrar Activo
                  </button>    
                  
                  <button type='button' class='btn btn-success' data-toggle='modal' data-target='#modal-default-ea'>
                    Gestionar Entrada Almacén
                  </button> 
  
                  <button type='button' class='btn btn-danger' data-toggle='modal' data-target='#modal-default-sa'>
                    Registrar Salida Almacén
                  </button> ";

                }

               
            }
        }
        else
        {
            return null;
        }

        return $settings;
    }

    //Permiso de registrar Cronograma
    function AdminSchedule($tokenuser)
    {
        global $mysqli;
        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);
            
            while($stmt->fetch())
            {
                if($rol == 1 && $module == 8)
                {
                    $settings = "<div class='card-tools'>
                    <a href='../functions/Register/InsertActiviesNew' class='btn btn-dark' title='Añadir Nuevo Cronograma'><i class='fas fa-calendar-plus'></i></a>
                    </div>";
                }

                if($rol == 4 && $module == 8)
                {
                    $settings = "<div class='card-tools'>
                    <a href='../functions/Register/InsertActiviesNew' class='btn btn-dark' title='Añadir Nuevo Cronograma'><i class='fas fa-calendar-plus'></i></a>
                    </div>";
                }
            }
        }
        else
        {
            return null;
        }

        return $settings;

    }

    //Permiso de Registrar Indicadores
    function AdminIndicators($tokenuser)
    {
        global $mysqli;

        $settings = "";

        $stmt = $mysqli->prepare("SELECT permit_user_id, id_module_permit FROM users INNER JOIN asign_permits ON users.id_user = asign_permits.user_id_asign WHERE token = ?");
        $stmt->bind_param('s', $tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($rol, $module);
            
            while($stmt->fetch())
            {
                if($rol == 1 && $module == 9)
                {
                    $settings = "<div class='card-tools'>          
                    <button type='button' class='btn btn-dark' data-toggle='modal' data-target='#modal-default'>
                    <i class='far fa-clipboard'></i> Generar Nuevo Indicador Anual
                    </button>                  
                    </div>";
                }

                if($rol == 4 && $module == 9)
                {
                    $settings = "<div class='card-tools'>          
                    <button type='button' class='btn btn-dark' data-toggle='modal' data-target='#modal-default'>
                    <i class='far fa-clipboard'></i> Generar Nuevo Indicador Anual
                    </button>                  
                    </div>";
                }
            }
        }
        else
        {
            return null;
        }

        return $settings;


    }

    //Nombres del usuario
    function NamesUser($tokenuser)
    {
        global $mysqli;

        $names = "";

        $stmt = $mysqli->prepare("SELECT first_name, second_name FROM users WHERE token = ?");
        $stmt->bind_param('s',$tokenuser);
        $stmt->execute();
        $stmt->store_result();
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($firstname, $secondname);
            $stmt->fetch();           

          $names = "<li class='nav-item'>
          <a class='nav-link'>
            <i class='text-danger nav-icon fas fa-user'></i>
            <p>".$firstname." ".$secondname."</p>
          </a>
        </li>";
        }

        return $names;
    }

    //Fecha actual sesión
    function DateSession()
    {
        $datecontent = "";
        date_default_timezone_set('America/Bogota'); 

        $dia = date('j');
        $mes = date('F');
        $year = date('Y');
        
        if ($mes == "January") $mes = "Enero";
        if ($mes == "February") $mes = "Febrero";
        if ($mes == "March") $mes = "Marzo";
        if ($mes == "April") $mes = "Abril";
        if ($mes == "May") $mes = "Mayo";
        if ($mes == "June") $mes = "Junio";
        if ($mes == "July") $mes = "Julio";
        if ($mes == "August") $mes = "Agosto";
        if ($mes == "September") $mes = "Septiembre";
        if ($mes == "October") $mes = "Octubre";
        if ($mes == "November") $mes = "Noviembre";
        if ($mes == "December") $mes = "Diciembre";
        
        $date = $dia.'-'.$mes.'-'.$year;

        $datecontent = "<li class='nav-item d-none d-sm-inline-block'>
        <a class='nav-link'>".$date."</a>
      </li>";

        return $datecontent;
    }

}

class ResetUser{

    //Genera token para recuperar la contraseña
    function GenereTokenPass($token, $user)
    {
        global $mysqli;

        $reguser = new RegisterUsers();
        $token = $reguser->GenerateToken();

        $stmt = $mysqli->prepare("UPDATE users SET token_password=?, password_resquest=1 WHERE token = ?"); //Agregar sentencia SQL
        $stmt->bind_param('ss', $token, $user);
        $stmt->execute();
        $stmt->close();

        return $token;        
    }

    //Verifica si existe una solicitud de recuperación
    function GetPasswordResquest($token)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT password_resquest FROM users WHERE token = ?"); //Agregar setencia SQL.
        $stmt->bind_param('i', $iduser);
        $stmt->execute();
        $stmt->bind_result($_passr);
        $stmt->fetch();

        if($_passr == 1)
        {
            return true;
        }
        else
        {
            return false;
        }

    }

    //Verifica si la cuenta se encuentra activada para realizar la recuperación.
    function VerificUserActive($user, $token)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT activation FROM users WHERE token = ? AND token_password = ? AND password_resquest = 1 LIMIT 1");
        $stmt->bind_param('ss', $user, $token);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($activation);
        $stmt->fetch();

        if($activation == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //Verifica si el usuario ya realizó el proceso de restauración
    function VerificResetUser($token)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT password_resquest FROM users WHERE token = ? LIMIT 1");
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($passreg);
        $stmt->fetch();

        if($passreg == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
        
    }

    //Realiza la actualización de contraseña según el usuario solicitado
    function UpdatePassword($pass_hash, $lastpassword, $user, $token)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("UPDATE users SET password = ?, last_modification_password = ?, token_password='', password_resquest = 0 WHERE token = ? AND token_password = ? "); //Agregar sentencia SQL.
        $stmt->bind_param('ssis', $pass_hash, $lastpassword, $user, $token);

        if($stmt->execute())
        {
            return true;
        }
        else
        {
            return false;
        }

    }
    
    //Verifica si el token de restauración existe
    function VerificTokenRestart($token)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT token_password FROM users WHERE token_password = ? LIMIT 1");
        $stmt->bind_param('s', $token);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($token_s);
        $stmt->fetch();

        if($token_s == $token)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //Verifica el token del usuario
    function ValideTokenUser($user)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT token FROM users WHERE token = ? LIMIT 1");
        $stmt->bind_param('s', $user);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_s);
        $stmt->fetch();

        if($user_s == $user)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //Envia el correo con el fomulario de restaurar contraseña
    function SendEmailRestart($emailuser, $firstname, $affair, $nombre, $url)
    {
        require_once '../bookstores/PHPMailer/PHPMailerAutoload.php';        
        
        $template = file_get_contents('../backpages/templaterestart.php');
        $template = str_replace("{{names}}", $nombre, $template);
        $template = str_replace("{{action_url_2}}", $url, $template);
        $template = str_replace("{{action_url_1}}", $url, $template);      


        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = 'mail.supremecluster.com';  
        $mail->Port = 2525;
        $mail->Username = 'cpsmtto@colpetroleumservices.com';
        $mail->Password = 'qcJG9s4F8$';     
        

        $mail->setFrom('cpsmtto@colpetroleumservices.com','CPS MTTO');
        $mail->addAddress($emailuser, $firstname);
        $mail->wordwrap = 50;

        $mail->Subject = $affair;
        $mail->Body = $template;        
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        if($mail->send())       
        return true;
        else 
        return false;  

    }

}

class Admin{

    //Busca los usuarios registrados
    function SearchUsers()
    {
        global $mysqli;

        $resquest = $_REQUEST;
        
        $sql = "SELECT * FROM users WHERE id_user >= 1";
        $query = $mysqli->query($sql);  
        $totalData = $query->num_rows;

        $totalFilter = $totalData;

        //Search

        $sql = "SELECT * FROM users WHERE id_user > 1 AND 1=1";
        
        if(!empty($resquest['search']['value']))
        {
            
            $sql.= "first_name LIKE '".$resquest['search']['value']."%' ";
            $sql.= "OR second_name LIKE '".$resquest['search']['value']."%'";
            
        }
        $query = $mysqli->query($sql);
        $totalData = $query->num_rows;
  

        $data = array();
        
        while($row = $query->fetch_array())
        {
            if($row[11] == 1)
            {
            	$state = "<span class='badge bg-success'>Activado</span>";            	
            }
            else            
            {
            	$state = "<span class='badge bg-danger'>No activado</span>";
            }
            
            $subdata = array();
            $subdata[] = $row[2]; //Nombre del usuario
            $subdata[] = $row[6]; //Correo del usuario
            $subdata[] = $row[7]; //Ultima conexion
            $subdata[] = $state; //Estado de la cuenta
            $subdata[] = "<div class='btn-group'>
            <a class='btn btn-default btn-sm' title='Restaurar Contraseña' href='resetpassword?user=".$row[13]."'><i class='fas fa-undo-alt'></i></a>
            <a class='btn btn-default btn-sm' title='Asignar Permisos' href='permitionsuser?user=".$row[13]."'><i class='fas fa-cogs'></i></a>                   
            </div>";

            $data[] = $subdata;

        }

        $json_data = array(
            "draw" => intval($resquest['draw']),
            "recordsTotal"      =>  intval($totalData),
            "recordsFiltered"   =>  intval($totalFilter),
            "data"              =>  $data
        );

        return json_encode($json_data); 
    }    


    //Trae los modulos para el usuario
    function ModulesOptions()
    {
        global $mysqli;     

        $stmt = $mysqli->prepare("SELECT id_permits, name_permits FROM permits");
        $stmt->execute();
        $stmt->store_result();
          
        $stmt->bind_result($idpermit, $typepermit);             

        while($stmt->fetch())
        {
            echo "<div class='form-check'>
                        <input class='form-check-input' type='checkbox' id='permit[]' value='".$idpermit."' name='permit[]'>
                        <label class='form-check-label'>".$typepermit."</label>
                        </div>";               

        }             

    }

    //Trae los modulos acorde al aplicativo
    function SearchModules()
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id_module_mtto, description_module_mtto FROM modules_mtto");
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id_mod, $description);        

        while($stmt->fetch())
        {
            
            echo "<option value=".$id_mod.">".$description."</option>";               
            
        }
       
    }

    //Consulta los permisos del usuario
    function VerificPermitions($id_user, $no_mod)
    {
        global $mysqli;

        $stmt = $mysqli->prepare("SELECT id_module_permit FROM asign_permits WHERE user_id_asign = ? AND id_module_permit = ?");
        $stmt->bind_param('ii', $id_user, $no_mod);
        $stmt->execute();
        $stmt->store_result();        
        $num = $stmt->num_rows;

        if($num > 0)
        {
            $stmt->bind_result($permit);
            $stmt->fetch();
        }
        else
        {
            $permit = 0;
        }
        
        return $permit;
    }

  



    

}
