<?php
//PÁGINA DE GESTIÓN DE PROCEDIMIENTOS

$id_user = $_SESSION['id_user'];

$mtto = new mtto();
$session = new UserFunctions();
$admin = new Admin();

$access_page = $admin->VerificPermitions($id_user, 10);

if (!$access_page == 1) {
    echo "<script> window.location='../pages/'; </script>";
};

if (isset($_POST['action']) && $_POST['action'] == 'create') {
    $data = [
        'title' => $_POST['title'],
        'date' => $_POST['date'],
        'objective' => str_replace('bolder', 'bold', $_POST['objective']),
        'scope' => str_replace('bolder', 'bold', $_POST['scope']),
        'definitions' => str_replace('bolder', 'bold', $_POST['definitions']),
        'position_1' => str_replace('bolder', 'bold', $_POST['position_1']),
        'number_workers_1' => str_replace('bolder', 'bold', $_POST['number_workers_1']),
        'responsibilities_1' => str_replace('bolder', 'bold', $_POST['responsibilities_1']),
        'position_2' => str_replace('bolder', 'bold', $_POST['position_2']),
        'number_workers_2' => str_replace('bolder', 'bold', $_POST['number_workers_2']) ,
        'responsibilities_2' => str_replace('bolder', 'bold', $_POST['responsibilities_2']) ,
        'recommendations' => str_replace('bolder', 'bold', $_POST['recommendations']) ,
        'planning' => str_replace('bolder', 'bold', $_POST['planning']) ,
        'monthly_maintenance' => str_replace('bolder', 'bold', $_POST['monthly_maintenance']) ,
        'semi_annual_maintenance' => str_replace('bolder', 'bold', $_POST['semi_annual_maintenance']) ,
        'maintenance_2_years' => str_replace('bolder', 'bold', $_POST['maintenance_2_years']) ,
        'equipment_tools' => str_replace('bolder', 'bold', $_POST['equipment_tools']) ,
        'records' => str_replace('bolder', 'bold', $_POST['records']) ,
        'confidentiality_note' => str_replace('bolder', 'bold', $_POST['confidentiality_note']) ,
        'version' => str_replace('bolder', 'bold', $_POST['version']) ,
        'change_reason' => str_replace('bolder', 'bold', $_POST['change_reason']) ,
    ];
    $procedure = $mtto->insertProcedure($data);
    echo "<script> window.location='adminprocedures.php'; </script>";
}

if (isset($_GET['action']) && $_GET['action'] == 'edit' && !empty($_GET['id'])) {
    $procedure = $mtto->findProcedure($_GET['id']);
}

if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $data = [
        'title' => $_POST['title'],
        'date' => $_POST['date'],
        'objective' => str_replace('bolder', 'bold', $_POST['objective']),
        'scope' => str_replace('bolder', 'bold', $_POST['scope']),
        'definitions' => str_replace('bolder', 'bold', $_POST['definitions']),
        'position_1' => str_replace('bolder', 'bold', $_POST['position_1']),
        'number_workers_1' => str_replace('bolder', 'bold', $_POST['number_workers_1']),
        'responsibilities_1' => str_replace('bolder', 'bold', $_POST['responsibilities_1']),
        'position_2' => str_replace('bolder', 'bold', $_POST['position_2']),
        'number_workers_2' => str_replace('bolder', 'bold', $_POST['number_workers_2']) ,
        'responsibilities_2' => str_replace('bolder', 'bold', $_POST['responsibilities_2']) ,
        'recommendations' => str_replace('bolder', 'bold', $_POST['recommendations']) ,
        'planning' => str_replace('bolder', 'bold', $_POST['planning']) ,
        'monthly_maintenance' => str_replace('bolder', 'bold', $_POST['monthly_maintenance']) ,
        'semi_annual_maintenance' => str_replace('bolder', 'bold', $_POST['semi_annual_maintenance']) ,
        'maintenance_2_years' => str_replace('bolder', 'bold', $_POST['maintenance_2_years']) ,
        'equipment_tools' => str_replace('bolder', 'bold', $_POST['equipment_tools']) ,
        'records' => str_replace('bolder', 'bold', $_POST['records']) ,
        'confidentiality_note' => str_replace('bolder', 'bold', $_POST['confidentiality_note']) ,
        'version' => str_replace('bolder', 'bold', $_POST['version']) ,
        'change_reason' => str_replace('bolder', 'bold', $_POST['change_reason']) ,
    ];
    $procedure = $mtto->updateProcedure($_POST['id_procedure'], $data);
    echo "<script> window.location='adminprocedures.php'; </script>";
}
?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <?php if (isset($procedure)) { ?>
                            <h5 class="card-title">EDITAR Procedimiento</h5>
                        <?php } else { ?>
                            <h5 class="card-title">Crear Procedimiento</h5>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                            <?php if (isset($procedure)) { ?>
                                <input type="hidden" name="id_procedure"
                                       value="<?php echo $procedure['id_procedure'] ?>">
                                <input type="hidden" name="action" value="update">
                            <?php } else { ?>
                                <input type="hidden" name="action" value="create">
                            <?php } ?>
                            <div class="form-group">
                                <label>Título</label>
                                <input type="text" name="title" class="form-control" required
                                       value="<?php echo isset($procedure) ? $procedure['title'] : '' ?>">
                            </div>
                            <div class="form-group">
                                <label>Fecha</label>
                                <input type="date" name="date" class="form-control" required
                                       value="<?php echo isset($procedure) ? $procedure['date'] : '' ?>">
                            </div>
                            <div class="form-group">
                                <label>Objetivo</label>
                                <?php if (isset($procedure)) { ?>
                                    <textarea type="text" name="objective" class="form-control summernote"
                                              required><?php echo $procedure['objective'] ?></textarea>
                                <?php } else { ?>
                                    <textarea type="text" name="objective" class="form-control summernote" required><p>Establecer el paso a paso para realizar de manera segura y eficaz, el mantenimiento preventivo a los
acumuladores.<br></p></textarea>
                                <?php } ?>
                            </div>
                            <div class="form-group">
                                <label>Alcance</label>
                                <?php if (isset($procedure)) { ?>
                                    <textarea type="text" name="scope" class="form-control summernote"
                                              required><?php echo $procedure['scope'] ?></textarea>
                                <?php } else { ?>
                                    <textarea type="text" name="scope" class="form-control summernote" required><p>Este procedimiento aplica para al personal que interviene en el mantenimiento preventivo del acumulador,
incluyendo tareas de limpieza, revisión del estado de los componentes, toma de cambio de aceite y
consumibles en Col Petroleum Services SAS.<br></p></textarea>
                                <?php } ?>
                            </div>
                            <div class="form-group">
                                <label>Definiciones</label>
                                <?php if (isset($procedure)) { ?>
                                    <textarea type="text" name="definitions" class="form-control summernote"
                                              required><?php echo $procedure['definitions'] ?></textarea>
                                <?php } else { ?>
                                    <textarea type="text" name="definitions" class="form-control summernote" required><p><b>Unidad acumuladora de presión:</b> Es la encargada de presurizar el fluido necesario para hacer accionar
las preventoras y la HCR. El fluido se almacena en la unidad, en una presión normalmente del doble de
la requerida, en cilindros especialmente diseñados. En caso de ser requerido la válvula de seguridad se
abre accionando rápidamente las preventoras y la HCER del ring.<br><span style="font-size: 1rem;"><b>Sistema neumático:</b> Los sistemas neumáticos están conformados por válvulas conductas de aire
comprimido., actuadores y un compresor. El cuerpo de los sistemas esta´´ compuesto por tuberías que
quieren de un sistema de retorno para para poder funcionar.
<br><b>Bomba Tríplex:</b> Bomba de acción simple, que opera a través de una descarga individual donde el fluido
es bombeado sobre un extremo del pistón de la bomba en cada uno de los tres cilindros. Estaunidad
bombea el fluido solo cuando el pistón se desplaza hacia adelante, y el cilindro se llena de fluido para ser
bombeado cuando el pistón se desplaza hacia atrás.
<br><b>Tablero de control eléctrico:</b> Es. una herramienta que nos ayuda a controlar los sistemas de energía a
través de dispositivos de conexión que tienen la función de maniobrar, medir y resguardar la seguridad
de toda una instalación para que la misma funcione adecuadamente. Por consiguiente, es una pieza
fundamental en toda ordenación eléctrica.</span></p></textarea>
                                <?php } ?>
                            </div>
                            <div class="form-group">
                                <label>Recomendaciones</label>
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>CARGO</th>
                                        <th>CANTIDAD DE TRABAJADORES POR CUADRILLA</th>
                                        <th>RESPONSABILIDADES</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td style="width: 25%"><input type="text" class="form-control" name="position_1"
                                                                      value="<?php echo isset($procedure) ? $procedure['position_1'] : 'COORDINADOR DE MANTENIMIENTO' ?>"
                                                                      required>
                                        </td>
                                        <td style="width: 10%"><input type="text" class="form-control"
                                                                      name="number_workers_1"
                                                                      value="<?php echo isset($procedure) ? $procedure['number_workers_1'] : '01' ?>"
                                                                      required></td>
                                        <td style="width: 65%">
                                            <?php if (isset($procedure)) { ?>
                                                <textarea name="responsibilities_1"
                                                          class="form-control summernote"
                                                          required><?php echo $procedure['responsibilities_1'] ?></textarea>
                                            <?php } else { ?>
                                                <textarea name="responsibilities_1"
                                                          class="form-control summernote" required><ul><li>Asegurar que todo el personal involucrado en el mantenimiento preventivo del
acumulador use los EPP asignados a su labor.</li><li>Asegurar que los trabajadores a su cargo han recibido la formación adecuada
y cuentan con la experiencia y el conocimiento para poder realizar el
mantenimiento de manera segura y efectiva.</li><li>Antes de intervenir el acumulador debe asegurarse de que este se encuentre
bloqueado y etiquetado para realizar el trabajo seguro.</li><li>Es el responsable del mantenimiento y debe asegurar el cumplimiento del
siguiente procedimiento para el mantenimiento preventivo a acumuladores.</li></ul><p><span style="font-weight: bold;">Nota:</span>&nbsp;Cuando se realicen mantenimientos al acumulador se debe asegurar que
la unidad este despresurizada.<br></p></textarea>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control" name="position_2"
                                                   value="<?php echo isset($procedure) ? $procedure['position_2'] : 'AUXILIAR DE MANTENIMIENTO' ?>"
                                                   required></td>
                                        <td><input type="text" class="form-control" name="number_workers_2"
                                                   value="<?php echo isset($procedure) ? $procedure['number_workers_2'] : '01' ?>"
                                                   required></td>
                                        <td>
                                            <?php if (isset($procedure)) { ?>
                                                <textarea class="form-control summernote" name="responsibilities_2"
                                                          required><?php echo $procedure['responsibilities_2'] ?></textarea>
                                            <?php } else { ?>
                                                <textarea class="form-control summernote" name="responsibilities_2"
                                                          required><ul><li>Participar activamente en el mantenimiento.</li><li>Trabajar de manera eficiente y segura sin exponerse a sí mismo o a sus
compañeros a riesgos.<br></li></ul></textarea>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group">
                                <label>Recomendaciones</label>
                                <?php if (isset($procedure)) { ?>
                                    <textarea type="text" name="recommendations" class="form-control summernote"
                                              required><?php echo $procedure['recommendations'] ?></textarea>
                                <?php } else { ?>
                                    <textarea type="text" name="recommendations" class="form-control summernote"
                                              required><ul><li>Señalizar las áreas para informar acerca de los peligros existentes</li><li><span style="font-size: 1rem;">Mantener el área de trabajo, limpia y ordenada</span></li><li><span style="font-size: 1rem;">Durante el desarrollo de actividades, debe haber buena comunicación entre la cuadrilla</span></li><li><span style="font-size: 1rem;">Revisar las medidas de control en caso de derrames</span></li><li><span style="font-size: 1rem;">Asegurarse que el equipo y material que se va a usar en esta actividad estén en condiciones seguras,
para evitar alguna fuga o derrame</span></li><li><span style="font-size: 1rem;">No utilizar joyas o accesorios</span></li><li><span style="font-size: 1rem;">En las áreas de operación no se debe fumar</span></li><li><span style="font-size: 1rem;">No se permite el consumo de bebidas alcohólicas o alucinógenos en los sitios de trabajo</span></li><li><span style="font-size: 1rem;">No se debe permitir la presencia de personas ajenas a la operación en los sitios de trabajo</span></li><li><span style="font-size: 1rem;">Mantener buenas relaciones y buen trato con los compañeros de trabajo</span></li><li><span style="font-size: 1rem;">Mantener una actitud positiva frente al trabajo y al cumplimiento de las normas de seguridad</span></li><li><span style="font-size: 1rem;">Este atento, podría ser golpeado por objetos que caigan de la torre</span></li><li><span style="font-size: 1rem;">Prohibido el uso de celulares en el área de trabajo</span></li></ul></textarea>
                                <?php } ?>
                            </div>
                            <fieldset>
                                <legend>Procedimiento / Descripción de la actvidad</legend>
                                <div class="form-group">
                                    <label>Planeación</label>
                                    <?php if (isset($procedure)) { ?>
                                        <textarea type="text" name="planning" class="form-control summernote"
                                                  required><?php echo $procedure['planning'] ?></textarea>
                                    <?php } else { ?>
                                        <textarea type="text" name="planning" class="form-control summernote" required><ul><li>Programación del mantenimiento</li><li>Coordinar con supervisor de Operaciones de turno, el tiempo para poder intervenir</li><li>Designar las funciones</li><li>Verificar las herramientas, repuestos y recursos requeridos para el mantenimiento a acumulador</li><li>Realizar el reporte del mantenimiento en el formato correspondiente (Al finalizarel mantenimiento)</li></ul><p><b>Nota:</b> Para trabajos nocturnos se requiere el uso de luz adicional para que nos genere la cantidad de
luz requerida para realizar el mantenimiento<br></p></textarea>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label>Ejecución del Mantenimiento preventivo al Acumulador - Mantenimiento
                                        mensual</label>
                                    <?php if (isset($procedure)) { ?>
                                        <textarea type="text" name="monthly_maintenance" class="form-control summernote"
                                                  required><?php echo $procedure['monthly_maintenance'] ?></textarea>
                                    <?php } else { ?>
                                        <textarea type="text" name="monthly_maintenance" class="form-control summernote"
                                                  required><ul><li>Informar la labor a realizar al Supervisor de Operaciones de la unidad RSU correspondiente</li><li>Realizar análisis de riesgos y permiso de trabajo cuando aplique</li><li>Corte visible de energía</li><li>Verificar la no existencia de tensión</li><li>Despresurizar el acumulador</li><li>Implementar el procedimiento de bloqueo y etiquetado de equipos, cuando aplique</li><li>El coordinador de mantenimiento verifica la presión del aire en sistema neumático del acumulador.
la cual debe ser de 120 psi</li><li>El coordinador de mantenimiento revisa el nivel de aceite del tanque de la unidad acumuladora y
de la bomba triplex</li><li>El coordinador de mantenimiento revisa el estado de la correa que acopla la bomba tríplex con el
motor eléctrico, revisa tensión de la correa y el estado de la polea</li><li>El coordinador de mantenimiento realiza limpieza a la trampa y al filtro de aceite de la succión de
la bomba tríplex</li><li>Se revisa la presión de nitrógeno, la cual debe tener un rango de 800 a 1000 psi</li><li>El coordinador de mantenimiento deja limpia el área de trabajo</li><li>El coordinador de mantenimiento retira las etiquetas de bloqueo</li><li>El coordinador de mantenimiento entrega el equipo limpio al Supervisor de Operaciones de la RSU</li><li>El coordinador de mantenimiento entrega el acumulador operativo</li><li>El coordinador de mantenimiento revisa posibles fugas por válvulas y pistones de la bomba tríplex<br></li></ul></textarea>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label>Ejecución del Mantenimiento preventivo al Acumulador - Mantenimiento
                                        semestral</label>
                                    <?php if (isset($procedure)) { ?>
                                        <textarea type="text" name="semi_annual_maintenance"
                                                  class="form-control summernote"
                                                  required><?php echo $procedure['semi_annual_maintenance'] ?></textarea>
                                    <?php } else { ?>
                                        <textarea type="text" name="semi_annual_maintenance"
                                                  class="form-control summernote"
                                                  required><ul><li>Informar la labor a realizar al Supervisor de Operaciones de la unidad RSU correspondiente</li><li>Realizar análisis de riesgos y permiso de trabajo cuando aplique</li><li>Realizar todas las actividades de mantenimiento mensual a los acumuladores</li><li>El coordinador de mantenimiento y/o técnico eléctrico verifica el estado general del sistema
eléctrico del acumulador</li><li>El coordinador de mantenimiento y/o técnico eléctrico limpia el tablero de control</li></ul><p><span style="font-weight: bold;">Nota:</span>&nbsp;Estas actividades están sujetas a cambio, según historial de fallas en general a mantenimientos
predictivos que determinen la condición de cada elemento a intervenir y actividades a ejecutar.<br></p></textarea>
                                    <?php } ?>
                                </div>
                                <div class="form-group">
                                    <label>Ejecución del Mantenimiento preventivo al Acumulador - Mantenimiento cada 2
                                        años</label>
                                    <?php if (isset($procedure)) { ?>
                                        <textarea type="text" name="maintenance_2_years" class="form-control summernote"
                                                  required><?php echo $procedure['maintenance_2_years'] ?></textarea>
                                    <?php } else { ?>
                                        <textarea type="text" name="maintenance_2_years" class="form-control summernote"
                                                  required><ul><li>Realizar todas las actividades de mantenimiento mensual a los acumuladores</li><li>Realizar todas las actividades de mantenimiento semestral a los acumuladores</li><li>Revisar daños estructurales<br></li></ul></textarea>
                                    <?php } ?>
                                </div>
                            </fieldset>
                            <div class="form-group">
                                <label>Equipos y Herramientas</label>
                                <?php if (isset($procedure)) { ?>
                                    <textarea type="text" name="equipment_tools" class="form-control summernote"
                                              required><?php echo $procedure['equipment_tools'] ?></textarea>
                                <?php } else { ?>
                                    <textarea type="text" name="equipment_tools" class="form-control summernote"
                                              required><ul><li>Juego llave mixta de ¼” hasta 15/8”</li><li>Inyector de grasa</li><li>Dado cuadrante 3/8” desde ¼” hasta ¾”</li><li>Calibrador de nitrógeno</li><li>Llave de tubo 18-24-36<br></li></ul></textarea>
                                <?php } ?>
                            </div>
                            <div class="form-group">
                                <label>Registros</label>
                                <?php if (isset($procedure)) { ?>
                                    <textarea type="text" name="records" class="form-control summernote"
                                              required><?php echo $procedure['records'] ?></textarea>
                                <?php } else { ?>
                                    <textarea type="text" name="records" class="form-control summernote" required><ul><li>F-17 Análisis de riesgos</li><li>F 77 hoja de mantenimiento<br></li></ul></textarea>
                                <?php } ?>
                            </div>
                            <div class="form-group">
                                <label>Nota de confidencialidad</label>
                                <?php if (isset($procedure)) { ?>
                                    <textarea type="text" name="confidentiality_note" class="form-control summernote"
                                              required><?php echo $procedure['confidentiality_note'] ?></textarea>
                                <?php } else { ?>
                                    <textarea type="text" name="confidentiality_note" class="form-control summernote"
                                              required><p>Todos los derechos reservados de este documento son para Col Petroleum Services SAS. Este
documento es intransferible, no se puede realizar ninguna reproducción externa parcial o total, copia o
transmitido digital de este documento sin un consentimiento o permiso escrito, según las leyes que regulan
los derechos del autor y con base a la regulación vigente.<br></p></textarea>
                                <?php } ?>
                            </div>
                            <fieldset>
                                <legend>Control de Cambios</legend>
                                <div class="form-group">
                                    <label>Versión</label>
                                    <input type="text" name="version" class="form-control"
                                           value="<?php echo isset($procedure) ? $procedure['version'] : 'Versión 0' ?>"
                                           required>
                                </div>
                                <div class="form-group">
                                    <label>Motivo de Cambio</label>
                                    <input type="text" name="change_reason" class="form-control"
                                           value="<?php echo isset($procedure) ? $procedure['change_reason'] : 'Creación del documento' ?>"
                                           required>
                                </div>
                            </fieldset>
                            <div class="form-group">
                                <button class="btn btn-success" type="submit">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
