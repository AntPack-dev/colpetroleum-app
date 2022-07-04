<?php

$mtto = new mtto();
$result = $mtto->obtenerRequisiciones();
$requisitionModel = null;
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
if ($_GET['action'] == 'edit') {
    $requisitionModel = $mtto->obtenerRequisition($_GET['id']);
}
if ($_POST['action'] == 'update') {
    $mtto->actualizarRequisition($_POST['id'], [
        'equipment' => $_POST['equipment'],
        'requested_items' => $_POST['requested_items'],
        'place' => $_POST['place'],
        'request_date' => date('Y-m-d'),
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
                                        <?php if ($requisitionModel) { ?>
                                            <h5 class="card-title">Editar solicitud</h5>
                                        <?php } else { ?>
                                            <h5 class="card-title">Nueva solicitud</h5>
                                        <?php } ?>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                                    <?php if ($requisitionModel) { ?>
                                                        <input type="hidden" name="id" value="<?php echo $requisitionModel['id'] ?>">
                                                        <input type="hidden" name="action" value="update">
                                                    <?php } else { ?>
                                                        <input type="hidden" name="action" value="create">
                                                    <?php } ?>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Equipo</label>
                                                                <input type="text" name="equipment"
                                                                       value="<?php echo ($requisitionModel ? $requisitionModel['equipment'] : '') ?>"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Piezas a solicitar</label>
                                                                <input type="text" name="requested_items"
                                                                       value="<?php echo ($requisitionModel ? $requisitionModel['requested_items'] : '') ?>"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label>Lugar de solicitud</label>
                                                                <input type="text" name="place"
                                                                       value="<?php echo ($requisitionModel ? $requisitionModel['place'] : '') ?>"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <?php if ($requisitionModel) { ?>
                                                                    <button class="btn btn-primary">Editar Solicitud</button>
                                                                <?php } else { ?>
                                                                    <button class="btn btn-primary">Solicitar</button>
                                                                <?php } ?>
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
                                                        <th>Acciones</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($result as $item) { ?>
                                                    <tr>
                                                        <td><?php echo $item['request_date']?></td>
                                                        <td><?php echo $item['equipment']?></td>
                                                        <td><?php echo $item['requested_items']?></td>
                                                        <td><?php echo $item['place']?></td>
                                                        <td><?php echo $item['status_text']?></td>
                                                        <td>
                                                            <?php if ($item['status'] == 1 || $item['status'] == 2) { ?>
                                                                <a href="<?php echo $_SERVER['PHP_SELF'] ?>?action=edit&id=<?php echo $item['id'] ?>" class="btn btn-primary">Editar</a>
                                                            <?php } ?>
                                                            <?php if ($item['status'] == 1) { ?>
                                                                <button onclick="eliminarRequisicion(<?php echo $item['id']?>)" class="btn btn-danger">Eliminar</button>
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
        </div>
    </div>
</section>

