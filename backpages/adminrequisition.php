<?php

$mtto = new mtto();
$result = $mtto->obtenerRequisicionesAdmin('1,2');
$resultEntregadas = $mtto->obtenerRequisicionesAdmin('3');
if ($_GET['action'] == 'atender') {
    $mtto->actualizarRequisition($_GET['id'], 2);
    echo "<script> window.location='adminrequisition.php';</script>";
}
if ($_GET['action'] == 'entregar') {
    $mtto->actualizarRequisition($_GET['id'], 3);
    echo "<script> window.location='adminrequisition.php';</script>";
}
?>


<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h5>Requisiciones</h5>
            </div>
            <div class="col-md-12">
                <div class="card card-primary card-tabs">
                    <div class="card-header p-0 pt-1">
                        <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="true">En proceso</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">Entregadas</a>
                            </li>

                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-one-tabContent">
                            <div class="tab-pane fade active show" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
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
                                                                    <button class="btn btn-primary" onclick="entregar(<?php echo $item['id']?>)">Entregar</button>
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
                            <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <h5>Solicitudes en entregadas</h5>
                                                <table class='table table-bordered col-sm-12'>
                                                    <thead>
                                                    <tr>
                                                        <th>Fecha de Solicitud</th>
                                                        <th>Usuario</th>
                                                        <th>Equipo</th>
                                                        <th>Piezas solicitadas</th>
                                                        <th>Lugar de solicitud</th>
                                                        <th>Estado de solicitud</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php foreach ($resultEntregadas as $item) { ?>
                                                        <tr>
                                                            <td><?php echo $item['request_date']?></td>
                                                            <td><?php echo $item['first_name'] . ' ' . $item['second_name']?></td>
                                                            <td><?php echo $item['equipment']?></td>
                                                            <td><?php echo $item['requested_items']?></td>
                                                            <td><?php echo $item['place']?></td>
                                                            <td><?php echo $item['status_text']?></td>
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

<script>
    function atender(id) {
        swal.fire({
            title: `¿Estás seguro que deseas marcar esta requisición como "Atendiendo"?`,
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cerrar',
            showLoaderOnConfirm: true,
            preConfirm: (arg) => {
                window.location.replace("<?php $_SERVER['PHP_SELF'] ?>?action=atender&id=" + id);
            },
            allowOutsideClick: () => !swal.isLoading()
        });
    }
    function entregar(id) {
        swal.fire({
            title: `¿Estás seguro que deseas marcar esta requisición como "Entregada"?`,
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cerrar',
            showLoaderOnConfirm: true,
            preConfirm: (arg) => {
                window.location.replace("<?php $_SERVER['PHP_SELF'] ?>?action=entregar&id=" + id);
            },
            allowOutsideClick: () => !swal.isLoading()
        });
    }
</script>
