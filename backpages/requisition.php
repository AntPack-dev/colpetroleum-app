<?php

$mtto = new mtto();
$result = $mtto->obtenerRequisiciones();

if ($_POST['action'] == 'create') {

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
}
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
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Nueva solicitud</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                                                    <input type="hidden" name="action" value="create">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Equipo</label>
                                                                <input type="text" name="equipment"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Piezas a solicitar</label>
                                                                <input type="text" name="requested_items"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Lugar de solicitud</label>
                                                                <input type="text" name="place" class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <button class="btn btn-primary">Solicitar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h5>Solicitudes realizadas</h5>
                                                <table class='table table-bordered col-sm-12'>
                                                    <thead>
                                                    <tr>
                                                        <th>Fecha de Solicitud</th>
                                                        <th>Equipo</th>
                                                        <th>Piezas solicitadas</th>
                                                        <th>Lugar de solicitud</th>
                                                        <th>Estado de solicitud</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($result as $item) { ?>
                                                    <tr>
                                                        <td><?php echo  $item['request_date']?></td>
                                                        <td><?php echo  $item['equipment']?></td>
                                                        <td><?php echo  $item['requested_items']?></td>
                                                        <td><?php echo  $item['place']?></td>
                                                        <td><?php echo  $item['status_text']?></td>
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
        </div>
    </div>
</section>

