<?php

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');

$mtto = new mtto();

$token = $mtto->GenerateTokenMtto();//
$date = $mtto->DateMtto();//

$tokenw = $mysqli->real_escape_string($_GET['warehouse']);

$id_warehouse = $mtto->getValueMtto('id_warehouse','warehouse','token_warehouse', $tokenw);//
$description_warehouse = $mtto->getValueMtto('description_warehouse','warehouse','token_warehouse', $tokenw);
$letters = $mtto->LetterWarehouse($description_warehouse);//
$op = "AC-";
$letter = $op.$letters;
$number = $mtto->AfterNumAnalisys($id_warehouse);//
$state = 0;

$description = $mysqli->real_escape_string($_POST['description']);//
$totalvalue = $mysqli->real_escape_string($_POST['totalvalue']);//

$state = 0;

$registeranalisys = $mtto->InsertAnalisysData($token, $date, $id_warehouse, $letter, $number, $description, $totalvalue, $state);

$updateexp = $mtto->UpdateStateAnalysis('expected_analysis', 'fk_analysis_data_expected', $registeranalisys, 'state_expected_analysis', 'fk_warehouse_expected_analysis', $id_warehouse, 'state_expected_analysis');
$updateperson = $mtto->UpdateStateAnalysis('person_analysis','fk_analysis_data_person', $registeranalisys, 'state_person_analysis', 'fk_warehouse_person_analysis', $id_warehouse, 'state_person_analysis');
$updatspare = $mtto->UpdateStateAnalysis('spare_parts_analysis','fk_analysis_data_spare', $registeranalisys, '	state_spare_analysis', 'fk_warehouse_spare_analysis', $id_warehouse, 'state_spare_analysis');


if($registeranalisys > 0)
{
    header("Location: ../../pages/recordanalisys");
}
else
{
    header("Location: ../../pages/analysis?warehouse=$tokenw");
}



