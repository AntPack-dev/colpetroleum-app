<?php
include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');


header('Content-Type: application/json; charset=utf-8');
try {
    global $mysqli;

    $id = $_REQUEST['id'];
    $mtto = new mtto();

    if ($mtto->deleteTeamUnitRsu($_REQUEST['id'])) {
        $data = [
            'success' => true,
            'message' => 'El registro fue eliminado'
        ];
    } else {
        var_dump("Error description: " . $mysqli -> error);
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
