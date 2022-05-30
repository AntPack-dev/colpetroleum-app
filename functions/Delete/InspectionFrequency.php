<?php
include('../../db/ConnectDB.php');

switch ($_REQUEST['action']) {
    case 'delete': {
        deleteInspectionFrequency();
    }
    case 'update': {
        updateInspectionFrequency();
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
        $maint = $data['maint'];
        $frequency = $data['frequency'];
        if ($mysqli->query( "UPDATE inspection_of_mant_teams SET maintenance_carried='$maint', frequency_inspection_teams='$frequency' WHERE id_inspection_mant_teams = $id") === TRUE) {
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
