<?php

session_start();

include('../db/ConnectDB.php');
include('../functions/OperationsUser.php');

$iduser = $_SESSION['id_user'];

$dates = new RegisterUsers();
$close = new LoginUser();

$date = $dates->DateUsers();
$close->CloseSession($iduser, $date);

session_destroy();

header('Location: ../');

