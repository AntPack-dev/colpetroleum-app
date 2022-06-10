<?php

//PÁGINA DE LA HOJA DE VIDA DEL EQUIPO O MAQUINARIA

$mtto = new mtto();
$session = new UserFunctions();
$admin = new Admin();

$access_page = $admin->VerificPermitions($id_user, 4);
if (!$access_page == 1) {
    echo "<script> window.location='../pages/'; </script>";
};

$tk_teams = $mysqli->real_escape_string($_GET['teams']);

$id_user = $_SESSION['id_user'];

$id_teams = $mtto->getValueMtto('id_teams_units', 'teams_units_rsu', 'token_teams_units', $tk_teams);
$date = $mtto->getValueMtto('dateregister_teams_units', 'teams_units_rsu', 'token_teams_units', $tk_teams);
$name = $mtto->getValueMtto('name_teams_units', 'teams_units_rsu', 'token_teams_units', $tk_teams);
$type = $mtto->getValueMtto('type_teams_units', 'teams_units_rsu', 'token_teams_units', $tk_teams);
$cost_mant = $mtto->getValueMtto('costmaint_teams_units', 'teams_units_rsu', 'token_teams_units', $tk_teams);
$model = $mtto->getValueMtto('model_teams_units', 'teams_units_rsu', 'token_teams_units', $tk_teams);
$serie = $mtto->getValueMtto('serie_teams_units', 'teams_units_rsu', 'token_teams_units', $tk_teams);
$number = $mtto->getValueMtto('number_teams_units', 'teams_units_rsu', 'token_teams_units', $tk_teams);
$letter = $mtto->getValueMtto('letter_units_teams', 'teams_units_rsu', 'token_teams_units', $tk_teams);
$capacity = $mtto->getValueMtto('capacity_teams_units', 'teams_units_rsu', 'token_teams_units', $tk_teams);
$mark = $mtto->getValueMtto('mark_teams_units', 'teams_units_rsu', 'token_teams_units', $tk_teams);
$plate = $mtto->getValueMtto('plate_teams_units', 'teams_units_rsu', 'token_teams_units', $tk_teams);
$description = $mtto->getValueMtto('description_teams_units', 'teams_units_rsu', 'token_teams_units', $tk_teams);
$url_image_uno = $mtto->getValueMtto('teams_image_one', 'teams_units_rsu', 'token_teams_units', $tk_teams);
$url_image_dos = $mtto->getValueMtto('teams_image_two', 'teams_units_rsu', 'token_teams_units', $tk_teams);
$total_m = $mtto->getValueMtto('SUM(totalcost_teams_maint)', 'report_teams_maint', 'fk_teams_report_maint', $id_teams);

$reference = $letter . "-" . $number;

if (isset($_POST['btnregisterinspection'])) {
    $date_reg = $mtto->DateMtto();
    $tk_inspection = $mtto->GenerateTokenMtto();
    $maint = $mysqli->real_escape_string($_POST['maint']);
    $frequency = $mysqli->real_escape_string($_POST['frequency']);
    $frequency_type = $mysqli->real_escape_string($_POST['frequency_type']);
    if ($frequency_type == 1) {
        $frequency_type_text = 'Por Horas';
        $frequency_value_hours = $_POST['frequency_value_hours'];
        $frequency_value_date = null;
    } else {
        $frequency_type_text = 'Por fecha';
        $frequency_value_hours = null;
        $frequency_value_date = $_POST['frequency_value_date'];
    }
//    var_dump($_POST); exit;
    $reg = $mtto->InsertInspection($date_reg, $tk_inspection, $maint, $frequency, $id_teams, $id_user, $frequency_type, $frequency_type_text, $frequency_value_hours, $frequency_value_date);

    if ($reg > 0) {
        echo "<script> window.location='resumeteams?teams=" . $tk_teams . "'; </script>";
    } else {
        echo "<script> window.location='resumeteams?teams=" . $tk_teams . "'; </script>";
    }
}

if (isset($_POST['btnupdateteams'])) {
    $name_up = $mysqli->real_escape_string($_POST['name']);
    $type_up = $mysqli->real_escape_string($_POST['type']);
    $model_up = $mysqli->real_escape_string($_POST['model']);
    $serie_up = $mysqli->real_escape_string($_POST['serie']);
    $capacity_up = $mysqli->real_escape_string($_POST['capacity']);
    $mark_up = $mysqli->real_escape_string($_POST['mark']);
    $plate_up = $mysqli->real_escape_string($_POST['plate']);
    $description_up = $mysqli->real_escape_string($_POST['description']);

    $update = $mtto->UpdateDetailsTeams($type_up, $model_up, $mark_up, $name_up, $serie_up, $capacity_up, $plate_up, $description_up, $tk_teams);


    if ($update > 0) {
        echo "<script> window.location='resumeteams?teams=" . $tk_teams . "'; </script>";
    } else {
        echo "<script> window.location='resumeteams?teams=" . $tk_teams . "'; </script>";
    }
}

if (isset($_POST['btnregisterimagenuno'])) {
    if (file_exists($url_image_uno)) {
        unlink($url_image_uno);
    }

    $imagenuno = $_FILES['imagenuno'];

    // print_r($_FILES);

    $upimgone = $mtto->AddImagenOne($_FILES, $tk_teams);

    if ($upimgone > 0) {
        echo "<script> window.location='resumeteams?teams=" . $tk_teams . "'; </script>";
    } else {
        echo "<script> window.location='resumeteams?teams=" . $tk_teams . "'; </script>";
    }


}

if (isset($_POST['btnregisterimagendos'])) {
    if (file_exists($url_image_dos)) {
        unlink($url_image_dos);
    }

    $imagendos = $_FILES['imagendos'];

    $upimgtwo = $mtto->AddImagenDos($_FILES, $tk_teams);


    if ($upimgtwo > 0) {
        echo "<script> window.location='resumeteams?teams=" . $tk_teams . "'; </script>";
    } else {
        echo "<script> window.location='resumeteams?teams=" . $tk_teams . "'; </script>";
    }

}

if (isset($_POST['btnregistermantreport'])) {
    $mant_id = $mysqli->real_escape_string($_POST['reference_mant']);
    $confirm_execute = $mysqli->real_escape_string($_POST['confirm_execute_mant']);
    $date_proxmant = $mysqli->real_escape_string($_POST['date_proxmant']);
    $token_report = $mtto->GenerateTokenMtto();

    //Consulta datos del reporte de mantenimiento
    $type_report = $mtto->getValueMtto('type_activity_report_maint', 'report_maint', 'id_report_maint', $mant_id);
    $description_report = $mtto->getValueMtto('description_report_mant', 'report_maint', 'id_report_maint', $mant_id);
    $actor_report = $mtto->getValueMtto('actor_execution_report_mant', 'report_maint', 'id_report_maint', $mant_id);
    $location_report = $mtto->getValueMtto('location_report_mant', 'report_maint', 'id_report_maint', $mant_id);
    $codreportfails_report = $mtto->getValueMtto('cod_report_fails_mant', 'report_maint', 'id_report_maint', $mant_id);
    $date_report = $mtto->getValueMtto('date_report_mant', 'report_maint', 'id_report_maint', $mant_id);
    $number_report = $mtto->getValueMtto('number_report_mant', 'report_maint', 'id_report_maint', $mant_id);
    $cost_report = $mtto->getValueMtto('cost_total_mant_analysis', 'report_maint', 'id_report_maint', $mant_id);

    $reg = $mtto->InsertReportMaintTeams($token_report, $number_report, $type_report, $description_report, $actor_report, $location_report, $codreportfails_report, $date_report, $date_proxmant, $confirm_execute, $cost_report, $id_teams, $id_user);
    $mtto->UpdateStateReportMant($mant_id);

    if ($reg > 0) {

        echo "<script> window.location='resumeteams?teams=" . $tk_teams . "'; </script>";
    } else {
        echo "<script> window.location='resumeteams?teams=" . $tk_teams . "'; </script>";
    }

}

?>

<section class="content">

    <input type="hidden" id="teams" value="<?php echo $id_teams; ?>" name="teams">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Hoja de vida - Ficha tecnica </h5>
                    </div>

                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Programa de mantenimiento e inspección</h5>
                                        <div class="card-tools">

                                            <a href="../report/ResumeTeams?teams=<?php echo $tk_teams; ?>"
                                               target="_blank" class="btn btn-danger">Ver formato</a>
                                        </div>
                                    </div>

                                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">

                                        <div class="card-body">

                                            <div class="row">

                                                <div class="input-group mb-1 col-sm-6">
                                                    <div class="input-group-prepend">
                                                        <span style="background-color: #F8F9F9;"
                                                              class="input-group-text">Fecha</span>
                                                    </div>
                                                    <input style="background-color: #FCF3CF;"
                                                           value="<?php echo $date; ?>" type="text" class="form-control"
                                                           disabled>
                                                </div>

                                                <div class="input-group mb-1 col-sm-6">
                                                    <div class="input-group-prepend">
                                                        <span style="background-color: #F8F9F9;"
                                                              class="input-group-text">Nombre específico</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="name"
                                                           value="<?php echo $name; ?>">
                                                </div>

                                                <div class="input-group mb-1 col-sm-6">
                                                    <div class="input-group-prepend">
                                                        <span style="background-color: #F8F9F9;"
                                                              class="input-group-text">Tipo de equipo</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="type"
                                                           value="<?php echo $type; ?>">
                                                </div>

                                                <div class="input-group mb-1 col-sm-6">
                                                    <div class="input-group-prepend">
                                                        <span style="background-color: #F8F9F9;"
                                                              class="input-group-text">Costos total de mantenimientos</span>
                                                    </div>
                                                    <input style="background-color: #EAFAF1;" type="text"
                                                           value="$<?php echo number_format($total_m); ?>"
                                                           class="form-control" disabled>
                                                </div>

                                                <div class="input-group mb-1 col-sm-6">
                                                    <div class="input-group-prepend">
                                                        <span style="background-color: #F8F9F9;"
                                                              class="input-group-text">Modelo</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="model"
                                                           value="<?php echo $model; ?>">
                                                </div>

                                                <div class="input-group mb-1 col-sm-6">
                                                    <div class="input-group-prepend">
                                                        <span style="background-color: #F8F9F9;"
                                                              class="input-group-text">Serie</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="serie"
                                                           value="<?php echo $serie; ?>">
                                                </div>

                                                <div class="input-group mb-1 col-sm-6">
                                                    <div class="input-group-prepend">
                                                        <span style="background-color: #F8F9F9;"
                                                              class="input-group-text">Referencia</span>
                                                    </div>
                                                    <input style="background-color: #FCF3CF;" type="text"
                                                           class="form-control" value="<?php echo $reference; ?>"
                                                           disabled>
                                                </div>

                                                <div class="input-group mb-1 col-sm-6">
                                                    <div class="input-group-prepend">
                                                        <span style="background-color: #F8F9F9;"
                                                              class="input-group-text">Capacidad</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="capacity"
                                                           value="<?php echo $capacity; ?>">
                                                </div>

                                                <div class="input-group mb-1 col-sm-6">
                                                    <div class="input-group-prepend">
                                                        <span style="background-color: #F8F9F9;"
                                                              class="input-group-text">Marca</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="mark"
                                                           value="<?php echo $mark; ?>">
                                                </div>

                                                <div class="input-group mb-1 col-sm-6">
                                                    <div class="input-group-prepend">
                                                        <span style="background-color: #F8F9F9;"
                                                              class="input-group-text">Placa</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="plate"
                                                           value="<?php echo $plate; ?>">
                                                </div>

                                                <div class="input-group mb-1 col-sm-12">
                                                    <div class="input-group-prepend">
                                                        <span style="background-color: #F8F9F9;"
                                                              class="input-group-text">Caracteristicas</span>
                                                    </div>
                                                    <input type="text" class="form-control" name="description"
                                                           value="<?php echo $description; ?>">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text"><i
                                                                    class="fas fa-exclamation-circle"
                                                                    title="NO REGISTRAR SIMBOLOS"> </i></div>
                                                    </div>
                                                </div>


                                            </div>


                                            <div class="row">

                                                <div class="col-sm-12">

                                                    <table class='table table-bordered col-sm-12'>
                                                        <thead>
                                                        <tr style='background-color: #F8F9F9;'>
                                                            <th style='text-align: left;'><?php echo $session->AdminRegisterInspection($tokenuser); ?></th>
                                                            <th colspan='2' style='text-align: center;'>FRECUENCIA DE
                                                                INSPECCIÓN Y MANTENIMIENTO
                                                            </th>
                                                        </tr>

                                                        <?php echo $mtto->SearchInspection($tk_teams); ?>

                                                        <table class="table table-bordered">
                                                            <thead style="text-align: center; background-color: #F8F9F9;">
                                                            <td style="text-align: left;"><?php echo $session->AdminUpdateTeamsImgOne($tokenuser); ?></i></button></td>

                                                            <th>FOTOGRAFIA NO. 1</th>

                                                            <td style="text-align: left;"><?php echo $session->AdminUpdateTeamsImgTwo($tokenuser); ?></td>

                                                            <th>FOTOGRAFIA NO. 2</th>
                                                            </thead>

                                                            <tbody>
                                                            <td colspan="2">
                                                                <div class="filtr-item col-sm-12" data-category="1"
                                                                     data-sort="white sample">
                                                                    <a href="<?php echo $url_image_uno; ?>"
                                                                       data-toggle="lightbox"
                                                                       data-title="Fotografia No. 1">
                                                                        <img src="<?php echo $url_image_uno; ?>"
                                                                             style="height: 200px; width: 400px;"
                                                                             alt="FOTOGRAFIA NO REGISTRADA"/>
                                                                    </a>
                                                                </div>
                                                            </td>

                                                            <td colspan="2">
                                                                <div class="filtr-item col-sm-12" data-category="2"
                                                                     data-sort="white sample">
                                                                    <a href="<?php echo $url_image_dos; ?>"
                                                                       data-toggle="lightbox"
                                                                       data-title="Fotografia No. 2">
                                                                        <img src="<?php echo $url_image_dos; ?>"
                                                                             style="height: 200px; width: 400px;"
                                                                             alt="FOTOGRAFIA NO REGISTRADA"/>
                                                                    </a>
                                                                </div>
                                                            </td>


                                                            </tbody>


                                                        </table>

                                                </div>


                                            </div>

                                            <?php echo $session->AdminUpdateTeams($tokenuser); ?>


                                        </div>

                                    </form>


                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Registros de mantenimientos realizados</h5>
                                    </div>
                                    <div class="card-body">

                                        <table id="id_table_register_man" class="display" style="width: 100%;">

                                            <thead>
                                            <tr>
                                                <th>Consecutivo de actividad No.</th>
                                                <th>Consecutivo de mantenimiento</th>
                                                <th>Tipo de actividad</th>
                                                <th>Descripción del mantenimiento</th>
                                                <th>Responsable de ejecución</th>
                                                <th>Lugar de ejecución</th>
                                                <th>Codigo de reporte de falla o avería</th>
                                                <th>Fecha</th>
                                                <th>Alarma</th>
                                                <th>Proxima fecha de ejecución</th>
                                                <th>Confirmación de ejecución</th>
                                                <th>Costos asociados</th>
                                            </tr>
                                            </thead>

                                            <tbody style="text-align: center;">


                                            </tbody>

                                        </table>


                                        <?php echo $session->AdminRegisterActivity($tokenuser); ?>

                                        <!-- <button type="submit" class="btn btn-danger" name="btnregisteractivity" data-toggle="modal" data-target="#modal-reg-act">
                                            Registrar Actividad
                                        </button>           -->


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

<!-- REGISTRAR LA FRECUENCIA DE INSPECCIÓN DE MANTENIMIENTO -->
<div class="modal fade" id="modal-mants">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Registrar Frecuencia de inspección y mantenimiento
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" id="form_inspection_of_mant_teams" method="POST">
                <input type="hidden" name="btnregisterinspection" value="btnregisterinspection">
                <div class="modal-body">
                    <div class="callout callout-info">
                        <h5>Pasos para registrar Frecuencia de inspección y mantenimiento:</h5>
                        <p>
                            1) Escribimos en la casilla Mantenimiento a realizar, la descripción de la actividad a
                            realizar.<br>
                            2) Escribimos en la casilla Frecuencia, el tiempo que se deba hacer la actividad.<br>
                            3) Presionamos sobre el botón <b>Guardar</b>.
                        </p>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Mantenimiento a realizar<b style="color:#B20F0F;">*</b></label>
                                <input type="text" class="form-control" name="maint" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Frecuencia<b style="color:#B20F0F;">*</b></label>
                                <input type="text" class="form-control" name="frequency" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tipo de Frecuencia<b style="color:#B20F0F;">*</b></label>
                                <select name="frequency_type" id="frequency_type" class="form-control" required>
                                    <option value="1">Por Horas</option>
                                    <option value="2">Por Fechas</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" id="div_frequency_value_hours">
                                <label>Valor de frecuencia en horas<b style="color:#B20F0F;">*</b></label>
                                <input type="number" class="form-control" id="frequency_value_hours" name="frequency_value_hours">
                            </div>
                            <div class="form-group" id="div_frequency_value_date" style="display: none">
                                <label>Valor de frecuencia en fecha<b style="color:#B20F0F;">*</b></label>
                                <input type="date" class="form-control" id="frequency_value_date" name="frequency_value_date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- EDITAR LA FRECUENCIA DE INSPECCIÓN DE MANTENIMIENTO -->
<div class="modal fade" id="editInspectionFrequencyModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Frecuencia de inspección y mantenimiento
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form onsubmit="editInspectionFrequencyForm(event)" method="POST">
                <input type="hidden" class="form-control" name="id" id="id_inspection_mant_teams" required>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Mantenimiento a realizar<b style="color:#B20F0F;">*</b></label>
                                <input type="text" class="form-control" name="maint" id="maint" required>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Frecuencia<b style="color:#B20F0F;">*</b></label>
                                <input type="text" class="form-control" name="frequency" id="frequency" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Tipo de Frecuencia<b style="color:#B20F0F;">*</b></label>
                                <select name="frequency_type" id="frequency_type_edit" class="form-control" required>
                                    <option value="1">Por Horas</option>
                                    <option value="2">Por Fechas</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group" id="div_frequency_value_hours_edit">
                                <label>Valor de frecuencia en horas<b style="color:#B20F0F;">*</b></label>
                                <input type="number" class="form-control" id="frequency_value_hours_edit" name="frequency_value_hours">
                            </div>
                            <div class="form-group" id="div_frequency_value_date_edit" style="display: none">
                                <label>Valor de frecuencia en fecha<b style="color:#B20F0F;">*</b></label>
                                <input type="date" class="form-control" id="frequency_value_date_edit" name="frequency_value_date">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<!-- REGISTRAR IMAGEN UNO -->

<div class="modal fade" id="modal-foto-one">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Aplicar fotografia uno
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data" method="POST">
                <div class="modal-body">

                    <div class="callout callout-info">
                        <h5>Pasos para subir foto de equipo o maquinaria:</h5>

                        <p>
                            1) Presionamos sobre el botón <b>Browse</b>, y buscará la foto a importar.<br>
                            2) Seleccionamos la foto y se cargará en la caja de testo.<br>
                            3) Presionamos sobre el botón <b>Guardar</b>.


                        </p>
                    </div>

                    <div class="row">

                        <div class="col-sm-12">
                            <div class="custom-file">
                                <input type="file" name="imagenuno" class="custom-file-input" id="customFile">
                                <label class="custom-file-label" for="customFile">Seleccionar imagen</label>
                            </div>

                        </div>

                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="input" class="btn btn-success" name="btnregisterimagenuno">Guardar</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- REGISTRAR IMAGEN 2 -->

<div class="modal fade" id="modal-foto-dos">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Aplicar fotografia dos
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data" method="POST">
                <div class="modal-body">

                    <div class="callout callout-info">
                        <h5>Pasos para subir foto de equipo o maquinaria:</h5>

                        <p>
                            1) Presionamos sobre el botón <b>Browse</b>, y buscará la foto a importar.<br>
                            2) Seleccionamos la foto y se cargará en la caja de testo.<br>
                            3) Presionamos sobre el botón <b>Guardar</b>.


                        </p>
                    </div>

                    <div class="row">

                        <div class="col-sm-12">
                            <div class="custom-file">
                                <input type="file" name="imagendos" class="custom-file-input" id="exampleInputFile">
                                <label class="custom-file-label" for="exampleInputFile">Seleccionar imagen</label>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="input" class="btn btn-success" name="btnregisterimagendos">Guardar</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<!-- REGISTRAR ACTIVIDAD -->
<div class="modal fade" id="modal-reg-act">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Registrar actividad de mantenimiento
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="modal-body">

                    <div class="callout callout-info">
                        <h5>Pasos para registrar actividad de mantenimiento:</h5>

                        <p>
                            1) Seleccionamos el reporte de mantenimiento, en la casilla Referencia del equipo.<br>
                            2) En la casilla Fecha proxima ejecución, ingresamos la fecha del proximo mantenimiento a
                            realizar.<br>
                            3) Seleccionamos SI o NO, en la casilla Se ejecutó correctamente la actividad.<br>
                            4) Presionamos sobre el botón <b>Guardar</b>.

                        </p>
                    </div>

                    <div class="row">

                        <div class="col-sm-12">

                            <label for="inputSuccess">Referencia del equipo:<b style="color:#B20F0F;">*</b></label>
                            <select name="reference_mant" size="10" class="form-control" required>
                                <?php echo $mtto->OptionMant($id_teams); ?>

                            </select>

                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Fecha proxima ejecución</label>
                                <input type="date" class="form-control" name="date_proxmant">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Se ejecuto correctamente la actividad<b style="color:#B20F0F;">*</b></label>
                                <select class="form-control" name="confirm_execute_mant" required>
                                    <option value="1">SELECCIONE</option>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                </select>
                                <!-- <input type="text" class="form-control" name="confirm_execute_mant" required> -->
                            </div>
                        </div>

                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="input" class="btn btn-success" name="btnregistermantreport">Guardar</button>
                </div>

            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    function deleteInspectionFrequency(id) {
        swal.fire({
            title: `¿Estás seguro que deseas eliminar este registro?`,
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cerrar',
            showLoaderOnConfirm: true,
            preConfirm: (arg) => {
                return fetch(`../functions/Delete/InspectionFrequency.php?action=delete&id=${id}`, {
                    method: 'GET',
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'application/json'
                    },
                }).then(response => {
                    if (response.status == 401) {
                        location.reload();
                    }
                    if (!response.ok) {
                        response.json().then(result => {
                            swal.fire({
                                title: result.message,
                                type: 'error',
                            });
                        });
                        return false;
                    }
                    return response.json();
                }).catch(error => {
                    console.error(error);
                    swal.fire({
                        title: error,
                        type: 'error'
                    });
                    return false;
                });
            },
            allowOutsideClick: () => !swal.isLoading()
        }).then((result) => {
            if (result.value) {
                swal.fire({
                    title: result.value.message,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonText: "Ok",
                    closeOnConfirm: false
                }).then(() => {
                    window.location.reload();
                });
            }
        });
    }

    function editInspectionFrequency(id, maintenance, frequency, frequency_type, frequency_value_hours, frequency_value_date) {
        $('#frequency_type_edit').val(frequency_type).change();
        $('#frequency_value_hours_edit').val(frequency_value_hours);
        $('#frequency_value_date_edit').val(frequency_value_date);
        $('#id_inspection_mant_teams').val(id);
        $('#maint').val(maintenance);
        $('#frequency').val(frequency);
        $('#editInspectionFrequencyModal').modal('show');
    }

    function editInspectionFrequencyForm() {
        event.preventDefault();

        const frequencyType = $('#frequency_type_edit').val();
        if (frequencyType == 1 && !$('#frequency_value_hours_edit').val()) {
            swal.fire({
                title: 'Ingrese un valor para la frecuencia en horas',
                type: 'error',
            });
            return;
        }
        if (frequencyType == 2 && !$('#frequency_value_date_edit').val()) {
            swal.fire({
                title: 'Ingrese un valor para la frecuencia en fechas',
                type: 'error',
            });
            return;
        }


        fetch(`../functions/Delete/InspectionFrequency.php?action=update&id=${$('#id_inspection_mant_teams').val()}`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                maint: $('#maint').val(),
                frequency: $('#frequency').val(),
                frequency_type: $('#frequency_type_edit').val(),
                frequency_value_hours: $('#frequency_value_hours_edit').val(),
                frequency_value_date: $('#frequency_value_date_edit').val(),
            })
        }).then(function (res) {
            return res.json();
        }).then(function (res) {
            swal.fire({
                title: res.message,
                type: "success",
                showCancelButton: false,
                confirmButtonText: "Ok",
                closeOnConfirm: false
            }).then(() => {
                window.location.reload();
            });
        })
    }

    /*$('#editInspectionFrequencyForm').on('submit', function () {
        e.preventDefault();
        console.log(this);
    });*/
</script>
