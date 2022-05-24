<?php

include('../db/ConnectDB.php');
include('../functions/OperationsUser.php');



$token = $mysqli->real_escape_string($_GET['token']);


$consult = new UserFunctions();

$useremail = $consult->ValueUser('email_user','users','token',$token);
$iduser = $consult->ValueUser('id_user','users','token',$token);

$module = $mysqli->real_escape_string($_POST['module']);


if(isset($_POST['permit']))
{       

    foreach($_POST['permit'] as $idpermit)
    {
        $save = $consult->AsignPermits($iduser, $useremail, $idpermit, $module);
            
    }

    if($save > 0)
    {
        header("Location:../pages/permitionsuser?user=".$token);
        
    }
    else
    {
        header("Location:../pages/permitionsuser?user=".$token);
        
    }

}
else
{
    header("Location:../pages/permitionsuser?user=".$token);
}

