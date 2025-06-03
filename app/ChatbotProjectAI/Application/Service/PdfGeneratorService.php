<?php

namespace App\ChatbotProjectAI\Application\Service;

use ProyectoFPDFAdapter;

class PdfGeneratorService
{
    private array $data;
    private string $fileName;

    public function __construct(array $data, string $fileName)
    {
        $this->data = $data;
        $this->fileName = $fileName;
    }

    public function generateAndOutput(): void
    {
        $pdf = new ProyectoFPDFAdapter($this->data);
        $pdf->GeneratePdfFromJson();
        $pdf->Output('I', $this->fileName);
    }
}
