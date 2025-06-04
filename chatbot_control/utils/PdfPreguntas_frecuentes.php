<?php
require('../../librerias/fpdf/fpdf.php');
class PdfPreguntas_frecuentes extends FPDF
{
	function LoadData($id)
	{
		include "../../controller/Controllerpreguntas_frecuentes.php";
		$controller = new Controllerpreguntas_frecuentes();
		$data = $controller->pregunta_fId($id);
		return $data;
	}

	function BasicTable($header, $data)
	{
		foreach ($header as $col)
			$this->Cell(40, 7, $col, 1);
		$this->Ln();
		foreach ($data as $row) {
			foreach ($row as $col)
				$this->Cell(40, 6, $col, 1);
			$this->Ln();
		}
	}

	function ImprovedTable($header, $data)
	{
		$w = array(40, 35, 45, 40);
		for ($i = 0; $i < count($header); $i++)
			$this->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
		$this->Ln();
		foreach ($data as $row) {
			$this->Cell($w[0], 6, $row[0], 'LR');
			$this->Cell($w[1], 6, $row[1], 'LR');
			$this->Cell($w[2], 6, number_format($row[2]), 'LR', 0, 'R');
			$this->Cell($w[3], 6, number_format($row[3]), 'LR', 0, 'R');
			$this->Ln();
		}
		$this->Cell(array_sum($w), 0, '', 'T');
	}

	function FancyTable($header, $data)
	{
		$this->SetFillColor(255, 0, 0);
		$this->SetTextColor(255);
		$this->SetDrawColor(128, 0, 0);
		$this->SetLineWidth(.3);
		$this->SetFont('', 'B');
		$w = array(40, 35, 45, 40);
		for ($i = 0; $i < count($header); $i++)
			$this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
		$this->Ln();
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetFont('');
		$fill = false;
		foreach ($data as $row) {
			$this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
			$this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
			$this->Cell($w[2], 6, number_format($row[2]), 'LR', 0, 'R', $fill);
			$this->Cell($w[3], 6, number_format($row[3]), 'LR', 0, 'R', $fill);
			$this->Ln();
			$fill = !$fill;
		}
		$this->Cell(array_sum($w), 0, '', 'T');
	}
}
