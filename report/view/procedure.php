<?php

session_start();

include('../db/ConnectDB.php');
include('../functions/FunctionsMtto.php');

if (!isset($_SESSION['id_user'])) {
    header("Location: ../../");
}

$mtto = new mtto();

$id = $mysqli->real_escape_string($_GET['id']);

$procedure = $mtto->findProcedure($id);

?>
<style>
    *, *::before, *::after {
        box-sizing: border-box;
    }
</style>
<page backtop="25mm" backttom="15mm" backleft="4mm" backright="4mm">
    <page_header>
        <div style="padding: 0 40px">
            <table style="border: 1px solid black;">
                <tr>
                    <td rowspan="3" style="width: 25%; border: 1px solid white;"><img src="img/LOGO.png"
                                                                                      style="position: relative; width: 150px; height: 50px;">
                    </td>
                    <td rowspan="3"
                        style="width: 368px; vertical-align: middle; border: 1px solid black; font-weight: bold; text-align:center; padding: 0 10px"><?php echo $procedure['title'] ?></td>
                    <td style="width: 25%; border: 1px solid black; font-size: 11px;">Código: F-270</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; font-size: 11px;">Versión: 0</td>
                </tr>

                <tr>
                    <td style="border: 1px solid black; font-size: 11px;">Fecha: <?php echo $procedure['date'] ?></td>
                </tr>

            </table>
        </div>
    </page_header>
    <div style="padding: 0 27px">
        <div style="margin-bottom: 25px;width: 670px">
            <h5 style="margin: 0; padding: 0; font-size: 15px"><b>1. OBJETIVO</b></h5>
            <div><?php echo $procedure['objective'] ?></div>
        </div>
        <div style="margin-bottom: 25px;width: 670px">
            <h5 style="margin: 0; padding: 0; font-size: 15px"><b>2. ALCANCE</b></h5>
            <div><?php echo $procedure['scope'] ?></div>
        </div>
        <div style="margin-bottom: 25px;width: 670px">
            <h5 style="margin: 0; padding: 0; font-size: 15px"><b>3. DEFINICIONES</b></h5>
            <div><?php echo $procedure['definitions'] ?></div>
        </div>
        <h5 style="margin: 0; padding: 0; font-size: 15px"><b>4. RESPONSABLES</b></h5>
        <br>
    </div>
    <table style="width: 100%;border-collapse: collapse;">
        <thead>
        <tr>
            <th style="width: 20%; text-align: center; background #f5b4ae; padding: 5px; border: 1px solid;">CARGO</th>
            <th style="width: 30%; text-align: center; background #f5b4ae; padding: 5px; border: 1px solid;">CANTIDAD DE
                TRABAJADORES POR CUADRILLA
            </th>
            <th style="width: 50%; text-align: center; background #f5b4ae; padding: 5px; border: 1px solid;">
                RESPONSABILIDADES
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="width: 20%; text-align: center; border: 1px solid;"><?php echo $procedure['position_1'] ?></td>
            <td style="width: 30%; text-align: center; border: 1px solid;"><?php echo $procedure['number_workers_1'] ?></td>
            <td style="width: 50%; text-align: center; border: 1px solid;"><?php echo $procedure['responsibilities_1'] ?></td>
        </tr>
        <tr>
            <td style="width: 20%; text-align: center; border: 1px solid;"><?php echo $procedure['position_2'] ?></td>
            <td style="width: 30%; text-align: center; border: 1px solid;"><?php echo $procedure['number_workers_2'] ?></td>
            <td style="width: 50%; text-align: center; border: 1px solid;"><?php echo $procedure['responsibilities_2'] ?></td>
        </tr>
        </tbody>
    </table>
    <div style="padding-left: 20px">
        <div style="padding: 0 27px; width: 560px;">
            <h5 style="margin: 0; padding: 0; font-size: 15px"><b>5. RECOMENDACIONES</b></h5>
            <table style="width: 100%;">
                <tr>
                    <td>
                        <div><?php echo $procedure['recommendations'] ?></div>
                    </td>
                </tr>
            </table>
            <div style="padding-left: 10px; margin-bottom: 20px">
                <h5 style="margin: 0; padding: 0; font-size: 15px"><b>5.1 USO CORRECTO DE ELEMENTOS DE PROTECCIÓN
                        PERSONAL</b></h5>
                <div style="padding-top: 20px; text-align: center">
                    <img src="img/procedimiento-image.png" style="width: 550px;">
                </div>
            </div>
        </div>
    </div>

    <div style="padding-left: 20px">
        <div style="padding-left: 10px; margin-bottom: 20px; width: 560px">
            <h5 style="margin: 0; padding: 0; font-size: 15px">
                <b>6. PROCEDIMIENTO / DESCRIPCIÓN DE LA ACTIVIDAD</b>
            </h5>
            <div style="padding-left: 10px">
                <h5 style="margin: 10px 0 0 0; padding: 0 0 0 10px; font-size: 15px"><b>6.1 Planeación</b></h5>
                <div style="padding-top: 20px;">
                    <?php echo $procedure['planning'] ?>
                </div>
            </div>
        </div>
    </div>
    <div style="padding-left: 20px">
        <div style="padding-left: 10px; width: 560px">
            <div style="padding-left: 10px">
                <h5 style="margin: 10px 0 0 0; padding: 0 0 0 10px; font-size: 15px">
                    <b>6.2 Ejecución del Mantenimiento preventivo al Acumulador</b>
                </h5>
                <div style="padding-left: 10px; padding-top: 10px">
                    <h5 style="margin: 0; padding: 0; font-size: 15px"><b>6.2.1 Mantenimiento
                            mensual</b></h5>
                </div>
                <div>
                    <?php echo $procedure['monthly_maintenance'] ?>
                </div>
                <div style="padding-left: 10px; padding-top: 10px">
                    <h5 style="margin: 0; padding: 0; font-size: 15px"><b>6.2.2 Mantenimiento
                            semestral</b></h5>
                    </div>
                <div>
                    <?php echo $procedure['semi_annual_maintenance'] ?>
                </div>
                <div style="padding-left: 10px; padding-top: 10px">
                    <h5 style="margin: 0; padding: 0; font-size: 15px">
                        <b>6.2.3 Mantenimiento cada 2 años
                        </b>
                    </h5>
                    </div>
                <div>
                    <?php echo $procedure['maintenance_2_years'] ?>
                </div>
            </div>
        </div>
    </div>

    <div style="padding-left: 20px">
        <div style="width: 560px;">
            <h5 style="margin: 0; padding: 0; font-size: 15px"><b>7. EQUIPOS Y HERRAMIENTAS</b></h5>
            <div>
                <?php echo $procedure['equipment_tools'] ?>
            </div>
        </div>
    </div>
    <div style="padding-left: 20px">
        <div style="width: 560px;">
            <h5 style="margin: 0; padding: 0; font-size: 15px"><b>8. REGISTROS</b></h5>
            <div>
                <?php echo $procedure['records'] ?>
            </div>
        </div>
    </div>

    <div style="padding-left: 20px">
        <div style="width: 560px;">
            <h5 style="margin: 0; padding: 0; font-size: 15px"><b>9. PELIGROS, RIESGOS Y CONTROLES</b></h5>
        </div>
    </div>

</page>
