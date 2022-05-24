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
$valuetotal_consumables = $mtto->getValueMtto('valuetotal_consumables', 'consumables_report', 'id_consumables', $id_report_consumables);
$description_consumables = $mtto->getValueMtto('description_consumables', 'consumables_report', 'id_consumables', $id_report_consumables);
$rsu = $mtto->getValueMtto('rsu_consumables', 'consumables_report', 'id_consumables', $id_report_consumables);
$rsu_consumables = $mtto->getValueMtto('letter_units_teams', 'teams_units_rsu', 'id_teams_units', $rsu);

?>


<page backtop="25mm" backttom="15mm" backleft="0m" backright="0mm">

    <page_header>
        <table style="background-color: #FDFEFE; border-radius: 5px; border: 0.5px solid #273746;">
        <tr style="border: 1px solid black;">      
        <td style="width:630px; font-size: 14px; color: #A93226; font-weight: bold;">COL PETROLEUM SERVICES S.A.S.</td>    
        <td rowspan="3"><img src="img/Logodos.png" style="position: relative; width: 110px; height: 60px;"></td>  
            
        </tr>
        <tr><td><?php echo strtoupper($description_consumables); ?></td></tr>      
        

        </table>
    </page_header>

    <?php echo $mtto->ConditionTypeConsumablesCost($id_report_consumables); ?>


    <table style="border-radius: 2px; border: 1px solid black; margin-top: 10px; font-size: 12px; text-align: center;">
        <tr>
        <th style="border-radius: 2px; border: 1px solid black; width: 55px; height: 20px; background-color: #273746; color: white;">LUGAR:</th>
            <td style="border-radius: 2px; border: 1px solid black; width: 230px; height: 20px; text-align: left; font-size: 10px; "><?php echo $site_consumables; ?></td>
            <th style="border-radius: 2px; border: 1px solid black; width: 99px; height: 20px; background-color: #273746; color: white;">RSU:</th>
            <td style="border-radius: 2px; border: 1px solid black; width: 100px; height: 20px; font-size: 10px;"><?php echo $rsu_consumables; ?></td>
            <th style="border-radius: 2px; border: 1px solid black; width: 100px; height: 20px; background-color: #273746; color: white;">CONTRATO:</th>
            <td style="border-radius: 2px; border: 1px solid black; width: 120px; height: 20px; font-size: 10px;"><?php echo $contract_consumables; ?></td>
        </tr>
    </table>

    <table style="border-radius: 2px; border: 1px solid black; margin-top: 5px; font-size: 12px">
        <tr style="text-align: center; color: white;">
            <th style="border-radius: 2px; border: 1px solid black; width: 40px; height: 40px; background-color: #273746;">ÍTEM</th>
            <th style="border-radius: 2px; border: 1px solid black; width: 320px; height: 40px; background-color: #273746;">DESCRIPCIÓN DE MATERIAL Y/O HERRAMIENTAS</th>
            <th style="border-radius: 2px; border: 1px solid black; width: 60px; height: 40px; background-color: #273746;">CANTIDAD</th>
            <th style="border-radius: 2px; border: 1px solid black; width: 130px; height: 40px; background-color: #273746;">COSTO</th>
            <th style="border-radius: 2px; border: 1px solid black; width: 160px; height: 40px; background-color: #273746;">OBSERVACIONES</th>
        </tr>


        <?php echo $mtto->ProductsConsumablesCost($id_report_consumables); ?>

        <tr>
            <td colspan="5" style='border-radius: 2px; border: 1px solid black; width: 40px; height: 10px; background-color: #273746;'></td>
        </tr> 

        <tr>
            <td colspan="3" style='border-radius: 2px; border: 1px solid black; width: 40px; height: 10px;'></td>
            <th style='border-radius: 2px; border: 1px solid black; width: 110px; height: 20px; text-align: right; background-color: #808B96;'>TOTAL</th>
            <th style='border-radius: 2px; border: 1px solid black; width: 160px; height: 20px; text-align: right;'>$<?php echo number_format($valuetotal_consumables); ?></th>
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

