<?php

//ELIMINA PROCEDIMIENTO

include('../../db/ConnectDB.php');
include('../FunctionsMtto.php');




header('Content-Type: application/json; charset=utf-8');
try {
    $mtto = new mtto();

    $delete = $mtto->deleteProcedure($_GET['id']);

    if ($delete === true) {
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

