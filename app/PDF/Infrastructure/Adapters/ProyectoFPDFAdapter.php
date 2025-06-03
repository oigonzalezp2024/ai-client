<?php
// C:\xampp\htdocs\web20250530\ai-client\app\PDF\Infrastructure\Adapters\ProyectoFPDFAdapter.php

// Asegúrate de que esta ruta sea correcta a tu archivo fpdf.php principal
require_once(__DIR__ . '/../Lib/Fpdf/fpdf.php');

class ProyectoFPDFAdapter extends FPDF
{
    protected $y0; // Ordenada de comienzo de la columna
    protected $jsonData; // Almacenará los datos de los datos del JSON
    protected $documentDate; // Propiedad para almacenar la fecha del documento (proveniente del JSON)
    protected $generationTimestamp; // Propiedad para almacenar la fecha y hora de generación real del PDF
    protected $titlesPrintedOnCurrentPage; // Contador de títulos impresos en la página actual

    // Altura de línea para simular interlineado doble (2 veces el tamaño de la fuente base de 12pt)
    protected $lineSpacing = 12; // Base para Times 12pt (12 * 1 = 12mm por línea para doble espacio)

    function __construct($jsonData = null, $orientation = 'P', $unit = 'mm', $size = 'A4')
    {
        parent::__construct($orientation, $unit, $size);
        $this->jsonData = $jsonData;

        // Establecer márgenes (2.54 cm = 25.4 mm)
        $margin = 25.4;
        $this->SetMargins($margin, $margin, $margin);
        $this->SetAutoPageBreak(true, $margin); // Habilitar salto de página automático con margen inferior

        // Inicializar y0 para la primera página
        $this->y0 = $margin; // La posición inicial de Y después del margen superior
        $this->titlesPrintedOnCurrentPage = 0; // Inicializar el contador de títulos

        // Establecer la fecha de generación real del documento en el constructor
        // Esta es una fecha "interna" al momento de generar el PDF.
        $this->generationTimestamp = date('d/m/Y H:i:s'); 

        // Extraer y formatear la fecha del timestamp del JSON para la fecha del documento
        if (isset($this->jsonData['timestamp'])) {
            $this->documentDate = date('d/m/Y', strtotime($this->jsonData['timestamp']));
        } else {
            // Si el timestamp no existe en el JSON, usa la fecha de generación del PDF como fecha del documento.
            $this->documentDate = date('d/m/Y', strtotime($this->generationTimestamp)); 
        }
    }

    // Función auxiliar para convertir cadenas a ISO-8859-1
    private function toLatin1($string) {
        if (mb_check_encoding($string, 'UTF-8')) {
            return mb_convert_encoding($string, 'ISO-8859-1', 'UTF-8');
        }
        return $string;
    }

    function Header()
    {
        // Posición y fuente para la cabecera
        $this->SetY(15); // Un poco más arriba del margen superior para la fecha
        $this->SetFont('Times', 'I', 10); // Fuente Times New Roman itálica 10pt
        $this->SetTextColor(0); // Color de texto negro
        
        // Imprimir la fecha del documento (proveniente del JSON) solo en la primera página
        if ($this->PageNo() == 1 && $this->documentDate) {
            $this->Cell(0, 10, $this->toLatin1('Fecha del reporte: ') . $this->documentDate, 0, 0, 'R');
        }
        
        // Restauramos la posición Y para el contenido principal
        $this->SetY(25.4); // Donde empieza el margen superior para el contenido

        // Resetear el contador de títulos al inicio de cada nueva página
        $this->titlesPrintedOnCurrentPage = 0;
    }
    
    function Footer()
    {
        // Posición a 15 mm del final de la página
        $this->SetY(-15);
        // Fuente Times New Roman itálica 10pt para el pie de página
        $this->SetFont('Times', 'I', 10);
        // Color de texto gris
        $this->SetTextColor(128);
        // Número de página centrado
        $this->Cell(0, 10, $this->toLatin1('Página ') . $this->PageNo(), 0, 0, 'C');
    }

    function AcceptPageBreak()
    {
        return true; 
    }

    function ChapterBodyFromText($text)
    {
        $text_latin1 = $this->toLatin1($text);
        $this->SetFont('Times', '', 12); // Establece la fuente base para el cuerpo del texto
        $this->SetTextColor(0); // Color de texto negro

        $lines = explode("\n", $text_latin1);
        
        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            
            // Manejo de líneas vacías para no procesarlas como texto normal y mantener el espaciado
            if (empty($trimmedLine)) {
                $this->Ln($this->lineSpacing / 2); // Un medio espacio para simular interlineado
                continue; 
            }

            // Determinar si la línea actual es un título principal centrado (Nivel 1)
            $isMainCenteredTitle = preg_match('/^\s*\*{2}(.*?)\*{2}\s*$/', $trimmedLine);

            // Determinar si la línea actual es cualquier tipo de título reconocido (Nivel 1, 2 o 3) para propósitos de conteo
            $isAnyRecognizedTitle = $isMainCenteredTitle || // Nivel 1
                                    preg_match('/^(Alcance:|Plan de Trabajo \(Ejemplo\):|Tecnologías:|Riesgo:|Equipo:|Presupuesto \(Ejemplo\):|Indicadores Clave de Rendimiento \(KPIs\):|Próximos Pasos:)\s*$/', $trimmedLine) || // Nivel 2
                                    preg_match('/^\s*[\*-]?\s*\*{2}(.*?)\*{2}\s*(.*)$/', $trimmedLine); // Nivel 3

            // --- Lógica de salto de página para títulos ---
            // Si la línea actual es un título principal centrado (Nivel 1)
            // y NO es uno de los dos primeros títulos (de cualquier nivel) de la primera página,
            // entonces fuerza un salto de página.
            // También, asegúrate de que no estamos ya al principio de una página recién creada.
            if ($isMainCenteredTitle && !($this->PageNo() == 1 && $this->titlesPrintedOnCurrentPage < 2)) {
                // Solo añade una página si no estamos ya al principio de una página (con un pequeño margen de error)
                // y si ya se ha escrito algo en la página actual (es decir, no es la primera línea del documento o una página recién añadida sin contenido).
                if ($this->GetY() > $this->tMargin + 0.1) { 
                    $this->AddPage();
                }
            }
            // --- Fin lógica de salto de página ---
            
            // 1. Nivel 1: Encabezados principales (línea completa en negrita, como "**Objetivo General:**")
            if (preg_match('/^\s*\*{2}(.*?)\*{2}\s*$/', $trimmedLine, $matches)) {
                $this->Ln($this->lineSpacing); // Espacio antes del título Nivel 1
                $this->SetFont('Times', 'B', 14); // Times New Roman Bold 14pt
                $this->MultiCell(0, $this->lineSpacing, $this->toLatin1($matches[1]), 0, 'C'); // Centrado
                $this->Ln($this->lineSpacing); // Espacio después del título Nivel 1
                $this->SetFont('Times', '', 12); // Restaura la fuente base
                if ($isAnyRecognizedTitle) { // Incrementar contador si es cualquier título reconocido
                    $this->titlesPrintedOnCurrentPage++; 
                }
            }
            // 2. Nivel 2: Subtítulos de sección (ej. "Alcance:", "Plan de Trabajo (Ejemplo):", "Tecnologías:", "Riesgo:", "Equipo:", "Presupuesto (Ejemplo):", "Indicadores Clave de Rendimiento (KPIs):", "Próximos Pasos:")
            elseif (preg_match('/^(Alcance:|Plan de Trabajo \(Ejemplo\):|Tecnologías:|Riesgo:|Equipo:|Presupuesto \(Ejemplo\):|Indicadores Clave de Rendimiento \(KPIs\):|Próximos Pasos:)\s*$/', $trimmedLine, $matches_level2_title)) {
                $this->Ln($this->lineSpacing); // Espacio antes del título Nivel 2
                $this->SetFont('Times', 'B', 12); // Times New Roman Bold 12pt
                $this->MultiCell(0, $this->lineSpacing, $this->toLatin1($matches_level2_title[1]), 0, 'L'); // Imprimimos solo la parte del título
                $this->Ln($this->lineSpacing / 2); // Un espacio después del título Nivel 2 (medio interlineado)
                $this->SetFont('Times', '', 12); // Restaura la fuente base
                if ($isAnyRecognizedTitle) { // Incrementar contador si es cualquier título reconocido
                    $this->titlesPrintedOnCurrentPage++; 
                }
            }
            // 3. Nivel 3: Líneas de lista tipo "Catálogo de Productos:", "Fase X:", "Gerente de Proyecto (AI Gerente de Desarrollo AI)"
            // Se ajustó la expresión regular para unificar el tratamiento de viñetas y guiones como elementos de lista principales.
            elseif (preg_match('/^\s*[\*-]?\s*\*{2}([^:]*?):?\*{2}\s*(.*)$/', $trimmedLine, $matches_bold_desc_or_phase)) {
                $bold_part = trim($matches_bold_desc_or_phase[1]);
                $remaining_part = trim($matches_bold_desc_or_phase[2]);

                $this->SetX($this->lMargin); // Asegura alineación con el margen izquierdo

                $this->SetFont('Times', 'B', 12); // Times New Roman Bold 12pt
                $this->Write($this->lineSpacing, $this->toLatin1($bold_part)); // Escribe la parte en negrita

                $this->SetFont('Times', '', 12); // Times New Roman Normal 12pt
                
                // Manejar el ':' y el espacio para las descripciones de ítems
                if (strpos($remaining_part, ':') === 0) {
                   $remaining_part = trim(substr($remaining_part, 1));
                }
                
                // Si la parte que se supone que sigue es una duración entre paréntesis (como en las fases)
                if (preg_match('/^\((.*?)\)$/', $remaining_part, $matches_duration)) {
                    $this->Write($this->lineSpacing, $this->toLatin1(' (' . $matches_duration[1] . ')'));
                } else if (!empty($remaining_part)) {
                    $this->Write($this->lineSpacing, $this->toLatin1(': ' . $remaining_part));
                }
                $this->Ln($this->lineSpacing); // Salto de línea completo después del elemento
                if ($isAnyRecognizedTitle) { // Incrementar contador si es cualquier título reconocido
                    $this->titlesPrintedOnCurrentPage++; 
                }
            }
            // 4. Sub-listas (ahora con `-` y posiblemente `*` para consistencia, si se decide)
            // Se ajustó la expresión regular para aceptar tanto '-' como '*' para elementos de sublista.
            elseif (preg_match('/^\s*[-\*]\s*(.*?)\s*$/', $trimmedLine, $matches_sublist)) {
                $this->SetX($this->lMargin + 12.7); // Indentación adicional (media pulgada, ~12.7mm)
                $this->SetFont('Times', '', 12); // Times New Roman Normal 12pt
                // Se asegura que el prefijo del elemento de lista sea consistente (ej. un guion)
                $this->MultiCell($this->GetPageWidth() - $this->lMargin - $this->rMargin - 12.7, $this->lineSpacing, $this->toLatin1("- " . $matches_sublist[1]), 0, 'L');
                $this->SetX($this->lMargin); // Resetea X al margen principal
                // No se incrementa titlesPrintedOnCurrentPage aquí, ya que son sub-listas, no títulos principales.
            }
            // 5. Cualquier otro texto normal (párrafos, etc.)
            else {
                $this->SetX($this->lMargin); // Asegura alineación con el margen izquierdo
                $this->MultiCell($this->GetPageWidth() - $this->lMargin - $this->rMargin, $this->lineSpacing, $this->toLatin1($trimmedLine), 0, 'L');
            }
        }
        $this->Ln($this->lineSpacing); // Espacio al final del contenido del capítulo
    }

    function GeneratePdfFromJson()
    {
        if (!$this->jsonData || !isset($this->jsonData['respuesta_asistente'])) {
            $this->AddPage();
            $this->SetFont('Times', 'B', 16);
            $this->Cell(0, $this->lineSpacing, $this->toLatin1('No se encontró contenido en el JSON para generar el PDF.'), 0, 1, 'C');
            return;
        }

        $this->AddPage();
        
        // El título principal del documento ahora debe venir del JSON o ser procesado como parte de respuesta_asistente
        // No hay hardcoding de título aquí.
        
        // Establecer y0 DESPUÉS de cualquier elemento de "portada" o título inicial
        $this->y0 = $this->GetY();

        $this->ChapterBodyFromText($this->jsonData['respuesta_asistente']);

        // Añadir la firma del proyecto y el timestamp de generación al final del documento
        $this->SetY(-30); // Posición fija desde el final de la página para la firma
        $this->SetFont('Times', 'I', 9);
        $this->SetTextColor(128); // Color gris para la información de generación

        // Información de generación alineada a la izquierda
        // Usamos $this->generationTimestamp que se configuró en el constructor.
        $this->Cell(0, $this->lineSpacing / 2, $this->toLatin1('Generado por AI (Google Gemini): ' . $this->generationTimestamp), 0, 1, 'L');
        
        // Alinear la firma de "Estructura de proyecto" un poco más abajo y centrada si se desea, o a la izquierda.
        $this->Ln(2); 
        $this->SetFont('Times', 'I', 10); 
        $this->SetTextColor(0); // Color de texto negro para la firma principal
        $this->MultiCell(0, $this->lineSpacing, $this->toLatin1('Estructura de proyecto generada automáticamente.'), 0, 'L');
    }
}
