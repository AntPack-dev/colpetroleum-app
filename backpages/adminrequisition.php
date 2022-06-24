<?php

$mtto = new mtto();
$result = $mtto->obtenerRequisicionesAdmin(1);

/*if ($_POST['action'] == 'create') {

    $mtto->insertRequisition([
        'user_id' => $_SESSION['id_user'],
        'equipment' => $_POST['equipment'],
        'requested_items' => $_POST['requested_items'],
        'place' => $_POST['place'],
        'request_date' => date('Y-m-d'),
        'status' => 1,
        'status_text' => 'Solicitado',
    ]);
    echo "<script> window.location='requisition.php';</script>";
}*/
?>


<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Requisiciones</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h5>Solicitudes en proceso</h5>
                                        <table class='table table-bordered col-sm-12'>
                                            <thead>
                                            <tr>
                                                <th>Fecha de Solicitud</th>
                                                <th>Usuario</th>
                                                <th>Equipo</th>
                                                <th>Piezas solicitadas</th>
                                                <th>Lugar de solicitud</th>
                                                <th>Estado de solicitud</th>
                                                <th>Acciones</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($result as $item) { ?>
                                                <tr>
                                                    <td><?php echo $item['request_date']?></td>
                                                    <td><?php echo $item['first_name'] . ' ' . $item['second_name']?></td>
                                                    <td><?php echo $item['equipment']?></td>
                                                    <td><?php echo $item['requested_items']?></td>
                                                    <td><?php echo $item['place']?></td>
                                                    <td><?php echo $item['status_text']?></td>
                                                    <td>
                                                        <?php if ($item['status'] == 1) {?>
                                                            <button class="btn btn-primary" onclick="atender(<?php echo $item['id']?>)">Atender</button>
                                                        <?php } ?>
                                                        <?php if ($item['status'] == 2) {?>
                                                            <button class="btn btn-primary">Entregar</button>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function atender(id) {

    }
</script>
