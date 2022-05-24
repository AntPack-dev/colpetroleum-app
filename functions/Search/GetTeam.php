<?php

//REALIZA LA CONSULTA DE EQUIPOS ACORDE AL SELECT DE LA RSU

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$mtto = new mtto();

$id_rsu = $mysqli->real_escape_string($_POST['id_rsu']);

$html = $mtto->OptionTeamsRSU($id_rsu);

echo $html;




