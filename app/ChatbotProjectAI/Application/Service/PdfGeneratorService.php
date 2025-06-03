<?php

declare(strict_types=1);

namespace App\ChatbotProjectAI\Application\Service;

// NO hay 'use ProyectoFPDFAdapter;' aquí. Está bien que no esté.

class PdfGeneratorService
{
    private array $data;
    private string $filePath;

    public function __construct(array $data, string $filePath)
    {
        $this->data = $data;
        $this->filePath = $filePath;
    }

    /**
     * Genera el PDF y lo envía directamente al navegador (Output 'I').
     * Este método NO debería ser llamado desde chat.php para la integración.
     */
    public function generateAndOutput(): void
    {
        // Oscar, la corrección es aquí: Usar \ProyectoFPDFAdapter para referenciar la clase global.
        $pdf = new \ProyectoFPDFAdapter($this->data); // <-- ANTEPONER LA BARRA INVERTIDA
        $pdf->GeneratePdfFromJson();
        $pdf->Output('I');
    }

    /**
     * Genera el PDF y lo guarda en el disco en la ruta especificada.
     * Este es el método que DEBES llamar desde chat.php.
     */
    public function generateAndSave(): void
    {
        // Oscar, la corrección es aquí: Usar \ProyectoFPDFAdapter para referenciar la clase global.
        $pdf = new \ProyectoFPDFAdapter($this->data); // <-- ANTEPONER LA BARRA INVERTIDA
        $pdf->GeneratePdfFromJson();
        // 'F' para guardar el PDF en un archivo
        $pdf->Output($this->filePath, 'F');
    }
}