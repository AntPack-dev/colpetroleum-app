<?php
include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');


header('Content-Type: application/json; charset=utf-8');
try {
    global $mysqli;

    $id = $_REQUEST['id'];
    $mtto = new mtto();

    if ($mtto->deleteUnitRsu($_REQUEST['id'])) {
        $data = [
            'success' => true,
            'message' => 'El registro fue eliminado'
        ];
    } else {
        $data = [
            'success' => false,
            'message' => mysqli_error($mysqli),
//            'message' => 'Ocurrió un error',
        ];
    }
} catch (Exception $e) {
    $data = [
        'message' => 'Ocurrió un error'
    ];
}
echo json_encode($data);
