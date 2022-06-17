<?php

//CALENDARIO GENERAL

$id_user = $_SESSION['id_user'];

$mtto = new mtto();
$session = new UserFunctions();
$admin = new Admin();

?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Calendario General de Mantenimiento</h5>
                    </div>
                    <div class="card-body">
                        <div id="general-calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modal-event-general-calendar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Mantenimiento programado</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="callout callout-info">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre específico</label>
                                <p id="nombre-especifico"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo de equipo</label>
                                <p id="tipo-equipo"></p>
                            </div>
                        </div>

                        <div id="container-modelo" class="col-md-6">
                            <div class="form-group">
                                <label>Modelo</label>
                                <p id="modelo"></p>
                            </div>
                        </div>
                        <div id="container-serie" class="col-md-6">
                            <div class="form-group">
                                <label>Serie</label>
                                <p id="serie"></p>
                            </div>
                        </div>
                        <div id="container-referencia" class="col-md-6">
                            <div class="form-group">
                                <label>Referencia</label>
                                <p id="referencia"></p>
                            </div>
                        </div>
                        <div id="container-capacidad" class="col-md-6">
                            <div class="form-group">
                                <label>Capacidad</label>
                                <p id="capacidad"></p>
                            </div>
                        </div>
                        <div id="container-marca" class="col-md-6">
                            <div class="form-group">
                                <label>Marca</label>
                                <p id="marca"></p>
                            </div>
                        </div>
                        <div id="container-placa" class="col-md-6">
                            <div class="form-group">
                                <label>Placa</label>
                                <p id="placa"></p>
                            </div>
                        </div>
                        <div id="container-caracteristicas" class="col-md-6">
                            <div class="form-group">
                                <label>Caracteristicas</label>
                                <p id="caracteristicas"></p>
                            </div>
                        </div>
                        <div id="container-mantenimiento" class="col-md-6">
                            <div class="form-group">
                                <label>Matenimiento</label>
                                <p id="mantenimiento"></p>
                            </div>
                        </div>
                        <div id="container-frecuencia" class="col-md-6">
                            <div class="form-group">
                                <label>Frecuencia</label>
                                <p id="frecuencia"></p>
                            </div>
                        </div>

                        <div id="container-fecha-activity" class="col-md-6">
                            <div class="form-group">
                                <label>Fecha</label>
                                <p id="fecha-activity"></p>
                            </div>
                        </div>
                        <div id="container-hours-activity" class="col-md-6">
                            <div class="form-group">
                                <label>Horas Trabajadas</label>
                                <p id="hours-activity"></p>
                            </div>
                        </div>
                        <div id="container-comment-activity" class="col-md-6">
                            <div class="form-group">
                                <label>Comentario</label>
                                <p id="comment-activity"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!--<div class="row">

                    <div class="col-sm-12">

                        <label for="inputSuccess">Referencia del equipo:<b style="color:#B20F0F;">*</b></label>
                        <select name="reference_mant" size="10" class="form-control" required>
                            <?php /*echo $mtto->OptionMant($id_teams); */?>

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
                        </div>
                    </div>
                </div>-->

            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
    const maintenanceEvents = <?php echo json_encode($mtto->getInspection_of_mant_teamsForGeneralCalendar($tk_teams))?>;
</script>
