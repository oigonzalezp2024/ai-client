<?php
include "../../utils/PdfPersonas.php";
$pdf = new PdfPersonas();
$header = array('Pa�s', 'Capital', 'Superficie (km2)', 'Pobl. (en miles)');
$data = $pdf->LoadData(1);
$pdf->SetFont('Arial', '', 14);
$pdf->AddPage();
$pdf->BasicTable($header, $data);
$pdf->Output();
