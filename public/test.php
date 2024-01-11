<?php 

require '../vendor/autoload.php';

use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf();
$html2pdf->pdf->setTitle('My PDF Document');
$html2pdf->writeHTML($htmlContent);
$html2pdf->output();

?>