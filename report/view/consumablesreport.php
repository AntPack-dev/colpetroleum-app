<?php

session_start();

include('../db/ConnectDB.php');
include('../functions/FunctionsMtto.php');

if(!isset($_SESSION['id_user']))
{
  header("Location: ../../");
}

if(!isset($_SESSION['token']))
{
  header("Location: ../../");
}

$mtto = new mtto();

$tk_consumables = $mysqli->real_escape_string($_GET['report']);

$id_report_consumables = $mtto->getValueMtto('id_consumables', 'consumables_report', 'token_consumables', $tk_consumables);

//CONSULTA DE LOS DATOS DEL REPORTE

$site_consumables = $mtto->getValueMtto('site_consumables', 'consumables_report', 'id_consumables', $id_report_consumables);
$contract_consumables = $mtto->getValueMtto('contract_consumables', 'consumables_report', 'id_consumables', $id_report_consumables);
$rsu = $mtto->getValueMtto('rsu_consumables', 'consumables_report', 'id_consumables', $id_report_consumables);
$rsu_consumables = $mtto->getValueMtto('letter_units_teams', 'teams_units_rsu', 'id_teams_units', $rsu);




?>


<page backtop="25mm" backttom="15mm" backleft="0m" backright="0mm">

    <page_header>
            <table style="border: 1px solid black;">
                <tr>
                    <td rowspan="3" style="border: 1px solid white;"><img src="img/LOGO.png" style="position: relative; width: 250px; height: 50px;"></td>
                    <td rowspan="3" style="border: 1px solid black; font-weight: bold; font-size: 18px; text-align:center; width: 360px;">ENTREGA DE HERRAMIENTAS Y/O MATERIALES A UNIDADES RSU</td>
                    <td style="border: 1px solid black; width: 100px; font-size: 11px;">Código: F-240</td>
                </tr>
                <tr>
                    <td style="border: 1px solid black; font-size: 11px;">Versión: 0</td>           

                </tr>

                <tr>
                    <td style="border: 1px solid black; font-size: 11px;">Fecha: Noviembre-2019</td>          

                </tr>       
                
            </table>
    </page_header>

    <?php echo $mtto->ConditionTypeConsumables($id_report_consumables); ?>  

    <table style="border: 1px solid white; margin-top: 10px; font-size: 12px; text-align: center;">
        <tr>
        <th style="border: 1px solid black; width: 55px; height: 20px; background-color: #F2DCDB;">LUGAR:</th>
            <td style="border: 1px solid black; width: 230px; height: 20px; text-align: left; font-size: 10px;"><?php echo $site_consumables; ?></td>
            <th style="border: 1px solid black; width: 99px; height: 20px; background-color: #F2DCDB;">RSU:</th>
            <td style="border: 1px solid black; width: 100px; height: 20px; font-size: 10px;"><?php echo $rsu_consumables; ?></td>
            <th style="border: 1px solid black; width: 100px; height: 20px; background-color: #F2DCDB;">CONTRATO:</th>
            <td style="border: 1px solid black; width: 120px; height: 20px; font-size: 10px;"><?php echo $contract_consumables; ?></td>
        </tr>
    </table>

    <table style="border: 1px solid white; margin-top: 5px; font-size: 12px">
        <tr style="text-align: center;">
            <th style="border: 1px solid black; width: 45px; height: 40px; background-color: #F2DCDB;">ÍTEM</th>
            <th style="border: 1px solid black; width: 385px; height: 40px; background-color: #F2DCDB;">DESCRIPCIÓN DE MATERIAL Y/O HERRAMIENTAS</th>
            <th style="border: 1px solid black; width: 80px; height: 40px; background-color: #F2DCDB;">CANTIDAD</th>
            <th style="border: 1px solid black; width: 200px; height: 40px; background-color: #F2DCDB;">OBSERVACIONES</th>
        </tr>

        <?php echo $mtto->ProductsConsumables($id_report_consumables); ?>
        
         
    </table>

    <table style="border: 1px solid white; margin-top: 5px;">
        <tr style="font-size: 9px; text-align: center;">
            <th style="border: 1px solif black; width:240px; height: 20px; background-color: #F2DCDB;">DATOS DE QUIEN ENTREGA (BASE)</th>
            <th style="border: 1px solif black; width:240px; height: 20px; background-color: #F2DCDB;">DATOS DE QUIEN RECIBE (ÁREA DE OPERACIONES)</th>
            <th style="border: 1px solif black; width:240px; height: 20px; background-color: #F2DCDB;">DATOS DE QUIEN RECIBE EN LA UNIDAD RSU</th>
        </tr>
        <tr>
            <td style="border: 1px solif black; width:240px; height: 10px; font-size: 10px; font-weight: bold;">NOMBRE:</td>
            <td style="border: 1px solif black; width:240px; height: 10px; font-size: 10px; font-weight: bold;">NOMBRE:</td>
            <td style="border: 1px solif black; width:240px; height: 10px; font-size: 10px; font-weight: bold;">NOMBRE:</td>
        </tr>
        <tr>
            <td style="border: 1px solif black; width:240px; height: 10px; font-size: 10px; font-weight: bold;">CARGO:</td>
            <td style="border: 1px solif black; width:240px; height: 10px; font-size: 10px; font-weight: bold;">CARGO:</td>
            <td style="border: 1px solif black; width:240px; height: 10px; font-size: 10px; font-weight: bold;">CARGO:</td>
        </tr>
        <tr>
            <td style="border: 1px solif black; width:240px; height: 10px; font-size: 10px; font-weight: bold;">FIRMA:</td>
            <td style="border: 1px solif black; width:240px; height: 10px; font-size: 10px; font-weight: bold;">FIRMA:</td>
            <td style="border: 1px solif black; width:240px; height: 10px; font-size: 10px; font-weight: bold;">FIRMA:</td>
        </tr>
        <tr>
            <td style="border: 1px solif black; width:240px; height: 10px; font-size: 10px; font-weight: bold;">FECHA ENTREGA:</td>
            <td style="border: 1px solif black; width:240px; height: 10px; font-size: 10px; font-weight: bold;">FECHA RECIBIDO:</td>
            <td style="border: 1px solif black; width:240px; height: 10px; font-size: 10px; font-weight: bold;">FECHA RECIBIDO:</td>
        </tr>
    </table>

    <page_footer>
        <table>
            <tr>
                <td></td>
            </tr>
        </table>

    </page_footer>

</page>

