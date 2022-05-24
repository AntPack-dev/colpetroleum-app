<?php

session_start();

include('../db/ConnectDB.php');

if(!isset($_SESSION['id_user']))
{
  header("Location: ../");
}

if(!isset($_SESSION['token']))
{
  header("Location: ../");
}

require '../bookstores/Vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

if(isset($_POST['btngeneratereport']))
{
   

    $type_report = $mysqli->real_escape_string($_POST['type_report']);
    $alcance_report = $mysqli->real_escape_string($_POST['alcance_report']);
    $date_ini = $mysqli->real_escape_string($_POST['date_ini']);
    $date_end = $mysqli->real_escape_string($_POST['date_end']);

    switch($type_report){
        case "635":

            if($alcance_report == "General")
            {               

                try {
                    ob_start();
                    require_once 'view/ReportMttoOne.php';
                    $content = ob_get_clean();
                
                    $html2pdf = new Html2Pdf('L','A4','es','true','UTF-8');
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->writeHTML($content);
                    $html2pdf->output('forms.pdf');
                } catch (Html2PdfException $e) {
                    $html2pdf->clean();
                
                    $formatter = new ExceptionFormatter($e);
                    echo $formatter->getHtmlMessage();
                }

            }
            else
            {
                
                try {
                    ob_start();
                    require_once 'view/ReportMttoTwo.php';
                    $content = ob_get_clean();
                
                    $html2pdf = new Html2Pdf('L','A4','es','true','UTF-8');
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->writeHTML($content);
                    $html2pdf->output('forms.pdf');
                } catch (Html2PdfException $e) {
                    $html2pdf->clean();
                
                    $formatter = new ExceptionFormatter($e);
                    echo $formatter->getHtmlMessage();
                }
            }
            
            break;
        case "734":

            if($alcance_report == "General")
            {
                try {
                    ob_start();
                    require_once 'view/ReportMttoThree.php';
                    $content = ob_get_clean();
                
                    $html2pdf = new Html2Pdf('P','A4','es','true','UTF-8');
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->writeHTML($content);
                    $html2pdf->output('forms.pdf');
                } catch (Html2PdfException $e) {
                    $html2pdf->clean();
                
                    $formatter = new ExceptionFormatter($e);
                    echo $formatter->getHtmlMessage();
                }
            }
            else
            {
                try {
                    ob_start();
                    require_once 'view/ReportMttoFourth.php';
                    $content = ob_get_clean();
                
                    $html2pdf = new Html2Pdf('P','A4','es','true','UTF-8');
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->writeHTML($content);
                    $html2pdf->output('forms.pdf');
                } catch (Html2PdfException $e) {
                    $html2pdf->clean();
                
                    $formatter = new ExceptionFormatter($e);
                    echo $formatter->getHtmlMessage();
                }
            }
            
            break;
        case "528":
            
            if($alcance_report == "General")
            {
                try {
                    ob_start();
                    require_once 'view/ReportMttoFive.php';
                    $content = ob_get_clean();
                
                    $html2pdf = new Html2Pdf('P','A4','es','true','UTF-8');
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->writeHTML($content);
                    $html2pdf->output('forms.pdf');
                } catch (Html2PdfException $e) {
                    $html2pdf->clean();
                
                    $formatter = new ExceptionFormatter($e);
                    echo $formatter->getHtmlMessage();
                }
            }
            else
            {
                try {
                    ob_start();
                    require_once 'view/ReportMttoSix.php';
                    $content = ob_get_clean();
                
                    $html2pdf = new Html2Pdf('P','A4','es','true','UTF-8');
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->writeHTML($content);
                    $html2pdf->output('forms.pdf');
                } catch (Html2PdfException $e) {
                    $html2pdf->clean();
                
                    $formatter = new ExceptionFormatter($e);
                    echo $formatter->getHtmlMessage();
                }
            }

            break;
        case "479":
            
            if($alcance_report == "General")
            {
                try {
                    ob_start();
                    require_once 'view/ReportMttoSeven.php';
                    $content = ob_get_clean();
                
                    $html2pdf = new Html2Pdf('L','A4','es','true','UTF-8');
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->writeHTML($content);
                    $html2pdf->output('forms.pdf');
                } catch (Html2PdfException $e) {
                    $html2pdf->clean();
                
                    $formatter = new ExceptionFormatter($e);
                    echo $formatter->getHtmlMessage();
                }
            }
            else
            {
                try {
                    ob_start();
                    require_once 'view/ReportMttoEight.php';
                    $content = ob_get_clean();
                
                    $html2pdf = new Html2Pdf('L','A4','es','true','UTF-8');
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->writeHTML($content);
                    $html2pdf->output('forms.pdf');
                } catch (Html2PdfException $e) {
                    $html2pdf->clean();
                
                    $formatter = new ExceptionFormatter($e);
                    echo $formatter->getHtmlMessage();
                }
            }

            break; 
        case "845":
            
            if($alcance_report == "General")
            {
                try {
                    ob_start();
                    require_once 'view/ReportMttoNine.php';
                    $content = ob_get_clean();
                
                    $html2pdf = new Html2Pdf('L','A4','es','true','UTF-8');
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->writeHTML($content);
                    $html2pdf->output('forms.pdf');
                } catch (Html2PdfException $e) {
                    $html2pdf->clean();
                
                    $formatter = new ExceptionFormatter($e);
                    echo $formatter->getHtmlMessage();
                }
            }
            else
            {
                try {
                    ob_start();
                    require_once 'view/ReportMttoTen.php';
                    $content = ob_get_clean();
                
                    $html2pdf = new Html2Pdf('L','A4','es','true','UTF-8');
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    $html2pdf->writeHTML($content);
                    $html2pdf->output('forms.pdf');
                } catch (Html2PdfException $e) {
                    $html2pdf->clean();
                
                    $formatter = new ExceptionFormatter($e);
                    echo $formatter->getHtmlMessage();
                }
            }

            break;

            case "564":
            
                if($alcance_report == "General")
                {
                    try {
                        ob_start();
                        require_once 'view/ReportMttoEleven.php';
                        $content = ob_get_clean();
                    
                        $html2pdf = new Html2Pdf('P','A4','es','true','UTF-8');
                        $html2pdf->pdf->SetDisplayMode('fullpage');
                        $html2pdf->writeHTML($content);
                        $html2pdf->output('forms.pdf');
                    } catch (Html2PdfException $e) {
                        $html2pdf->clean();
                    
                        $formatter = new ExceptionFormatter($e);
                        echo $formatter->getHtmlMessage();
                    }
                }
                else
                {
                    try {
                        ob_start();
                        require_once 'view/ReportMttoTwelve.php';
                        $content = ob_get_clean();
                    
                        $html2pdf = new Html2Pdf('P','A4','es','true','UTF-8');
                        $html2pdf->pdf->SetDisplayMode('fullpage');
                        $html2pdf->writeHTML($content);
                        $html2pdf->output('forms.pdf');
                    } catch (Html2PdfException $e) {
                        $html2pdf->clean();
                    
                        $formatter = new ExceptionFormatter($e);
                        echo $formatter->getHtmlMessage();
                    }
                }
    
                break;
            
            case "890":

                echo "AQUI VA UN REPORTE ESTADISTICO DEPENDIENDO DEL RANGO DE FECHAS SELECCIONADA";        
            

                break;
    }
}
else
{
    header('Location: ../pages/');
}