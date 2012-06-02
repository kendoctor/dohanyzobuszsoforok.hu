<?php

require('fpdf.php');

$pdf = new FPDF();
$pdf->AddFont('times-latin2','','times-latin2.php');
$pdf->AddPage();
$pdf->SetFont('times-latin2', '', 12);
$text = $_POST['text'];
$text = iconv('utf-8', 'iso-8859-2', $text);
$pdf->Write(5, $text);
$pdf->Output('dohanyzo-buszsofor.pdf', 'I');

?>
