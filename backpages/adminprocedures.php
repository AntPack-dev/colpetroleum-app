<?php

//PÁGINA DE GESTIÓN DE PROCEDIMIENTOS

$id_user = $_SESSION['id_user'];

$mtto = new mtto();
$session = new UserFunctions();
$admin = new Admin();

$access_page = $admin->VerificPermitions($id_user, 10);

if(!$access_page == 1){ echo "<script> window.location='../pages/'; </script>"; };


if(isset($_POST['btnregisterprocedures']))
{
    $name = $mysqli->real_escape_string($_POST['name']);
    $description = $mysqli->real_escape_string($_POST['description']);

    $procedure = $mtto->insertProcedure($name, $description);

    echo "<script> window.location='adminprocedures.php'; </script>";
}

?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Procedimientos</h5>
                        <div class="card-tools">
<!--                            <a href="../report/ReportF270" class="btn btn-danger btn-sm" target="_blank"><i class="fas fa-file-alt"></i> F-270</a>-->
                        </div>
                    </div>
                    <div class="card-body">

                        <table id="id_table_procedures" class="display" style="width:100%">
                            <thead>
                            <tr style="text-align: center;">
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Acción</th>
                            </tr>
                            </thead>
                            <tbody style="text-align: center;">
                            </tbody>
                        </table>

                        <?php echo $session->AdminRegisterProcedures($tokenuser); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

</section>


<!--<div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Registrar Procedimiento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php /*$_SERVER['PHP_SELF'] */?>" method="POST">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="inputSuccess">Nombre <b style="color:#B20F0F;">*</b></label>
                                <input type="text" class="form-control"  name="name" required>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Descripción <b style="color:#B20F0F;">*</b></label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="input" name="btnregisterprocedures" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>-->

<script>
    function deleteItem(id) {
        swal.fire({
            title: `¿Estás seguro que deseas eliminar este registro?`,
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cerrar',
            showLoaderOnConfirm: true,
            preConfirm: (arg) => {
                return fetch(`../functions/Delete/DeleteProcedure.php?id=${id}`, {
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

    /*function editInspectionFrequency(id, maintenance, frequency) {
        $('#id_inspection_mant_teams').val(id);
        $('#maint').val(maintenance);
        $('#frequency').val(frequency);
        $('#editInspectionFrequencyModal').modal('show');
    }

    function editInspectionFrequencyForm() {
        event.preventDefault();
        fetch(`../functions/Delete/InspectionFrequency.php?action=update&id=${$('#id_inspection_mant_teams').val()}`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({maint: $('#maint').val(), frequency: $('#frequency').val()})
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
    }*/


</script>

