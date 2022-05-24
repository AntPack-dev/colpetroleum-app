<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$mtto = new mtto();

if(isset($_POST['btnaddobservation']))
{
    $observation = $mysqli->real_escape_string($_POST['observation']);
    $id_month_indicator = $mysqli->real_escape_string($_POST['id_indicator_month']);
    $tk_indicator = $mysqli->real_escape_string($_POST['indicator_tk']);

    

    $obs = str_replace(array('\n', '\t', '\r', '\r\n'), '', $observation);

    $mtto->AddObservationInd($id_month_indicator, $obs);

    header('Location: ../../pages/viewindicators?indicators='.$tk_indicator);
}
