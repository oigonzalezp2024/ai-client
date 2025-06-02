<?php
require('../../Infrastructure/Lib/Fpdf/fpdf.php');

class FpdfAdapter extends FPDF
{
    // Removed $col property as it's no longer needed for single column

    protected $y0; // Ordenada de comienzo de la columna (still useful for header/footer alignment)

    function Header()
    {
        // Cabacera
        global $title;

        $this->SetFont('Arial', 'B', 15);
        $w = $this->GetStringWidth($title) + 6;
        $this->SetX((210 - $w) / 2);
        $this->SetDrawColor(0, 80, 180);
        $this->SetFillColor(230, 230, 0);
        $this->SetTextColor(220, 50, 50);
        $this->SetLineWidth(1);
        $this->Cell($w, 9, $title, 1, 1, 'C', true);
        $this->Ln(10);
        // Guardar ordenada
        $this->y0 = $this->GetY();
    }

    function Footer()
    {
        // Pie de página
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(128);
        $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
    }

    // SetCol method is no longer needed for single column layout

    function AcceptPageBreak()
    {
        // For a single column, always accept the page break
        // and let FPDF handle the new page.
        return true;
    }

    function ChapterTitle($num, $label)
    {
        // Título
        $this->SetFont('Arial', '', 12);
        $this->SetFillColor(200, 220, 255);
        $this->Cell(0, 6, "Capítulo $num : $label", 0, 1, 'L', true);
        $this->Ln(4);
        // Guardar ordenada
        $this->y0 = $this->GetY();
    }

    function ChapterBody($file)
    {
        // Abrir fichero de texto
        $txt = file_get_contents($file);
        // Fuente
        $this->SetFont('Times', '', 12);
        // Imprimir texto en una única columna, filling most of the page width
        // 190 is often a good width for a single column with default margins (210 - 10 left - 10 right)
        $this->MultiCell(190, 5, $txt);
        $this->Ln();
        // Cita en itálica
        $this->SetFont('', 'I');
        $this->Cell(0, 5, '(fin del extracto)');
        // No need to reset column as we only have one
    }

    function PrintChapter($num, $title, $file)
    {
        // Añadir capítulo
        $this->AddPage();
        $this->ChapterTitle($num, $title);
        $this->ChapterBody($file);
    }
}
