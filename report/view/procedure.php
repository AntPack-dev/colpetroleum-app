<?php

session_start();

include('../db/ConnectDB.php');
include('../functions/FunctionsMtto.php');

if(!isset($_SESSION['id_user']))
{
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
                    <td rowspan="3" style="width: 25%; border: 1px solid white;"><img src="img/LOGO.png" style="position: relative; width: 150px; height: 50px;"></td>
                    <td rowspan="3" style="width: 400px; vertical-align: middle; border: 1px solid black; font-weight: bold; text-align:center; padding: 0 10px"><?php echo $procedure['title'] ?></td>
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
        <h5 style="margin: 0; padding: 0; font-size: 15px"><b>1. OBJETIVO</b></h5>
        <p><?php echo $procedure['objective'] ?></p>
        <h5 style="margin: 0; padding: 0; font-size: 15px"><b>2. ALCANCE</b></h5>
        <p><?php echo $procedure['scope'] ?></p>
        <h5 style="margin: 0; padding: 0; font-size: 15px"><b>3. DEFINICIONES</b></h5>
        <p><?php echo $procedure['definitions'] ?></p>
        <h5 style="margin: 0; padding: 0; font-size: 15px"><b>4. RESPONSABLES</b></h5>
    </div>
    <table style="width: 100%;border-collapse: collapse;">
        <thead>
        <tr>
            <th style="width: 20%; text-align: center; background #f5b4ae; padding: 5px; border: 1px solid;">CARGO</th>
            <th style="width: 30%; text-align: center; background #f5b4ae; padding: 5px; border: 1px solid;">CANTIDAD DE TRABAJADORES POR CUADRILLA</th>
            <th style="width: 50%; text-align: center; background #f5b4ae; padding: 5px; border: 1px solid;">RESPONSABILIDADES</th>
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
</page>
