<?php
include('../../db/ConnectDB.php');

switch ($_REQUEST['action']) {
    case 'delete': {
        deleteInspectionFrequency();
        break;
    }
    case 'update': {
        updateInspectionFrequency();
        break;
    }
}

function deleteInspectionFrequency()
{
    header('Content-Type: application/json; charset=utf-8');
    try {
        global $mysqli;

        $id = $_REQUEST['id'];

        if ($mysqli->query( "DELETE FROM inspection_of_mant_teams WHERE id_inspection_mant_teams = $id") === TRUE) {
            $data = [
                'success' => true,
                'message' => 'El registro fue eliminado'
            ];
        } else {
            $data = [
                'success' => false,
                'message' => 'Ocurrió un error'
            ];
        }
    } catch (Exception $e) {
        $data = [
            'message' => 'Ocurrió un error'
        ];
    }
    echo json_encode($data);
}

function updateInspectionFrequency()
{
    header('Content-Type: application/json; charset=utf-8');
    try {
        global $mysqli;
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $id = $_REQUEST['id'];
        $maint = $mysqli->real_escape_string($data['maint']) ;
        $frequency = $mysqli->real_escape_string($data['frequency']);
        $frequency_type = $mysqli->real_escape_string($data['frequency_type']);
        if ($frequency_type == 1) {
            $frequency_type_text = 'Por Horas';
            $frequency_value_hours = $data['frequency_value_hours'];
            $frequency_value_date = 'NULL';
        } else {
            $frequency_type_text = 'Por fecha';
            $frequency_value_hours = 'NULL';
            $frequency_value_date = $data['frequency_value_date'];
        }
        if ($mysqli->query( "UPDATE inspection_of_mant_teams SET maintenance_carried='$maint', frequency_inspection_teams='$frequency', frequency_type= $frequency_type, frequency_type_text='$frequency_type_text', frequency_value_hours= $frequency_value_hours, frequency_value_date='$frequency_value_date' WHERE id_inspection_mant_teams = $id") === TRUE) {
            $data = [
                'success' => true,
                'message' => 'El registro fue actualizado'
            ];
        } else {
            $data = [
                'success' => false,
                'message' => 'Ocurrió un error'
            ];
        }
    } catch (Exception $e) {
        $data = [
            'message' => 'Ocurrió un error'
        ];
    }
    echo json_encode($data);
}
