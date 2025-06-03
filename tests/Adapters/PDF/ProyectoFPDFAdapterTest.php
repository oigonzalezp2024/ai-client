<?php

use PHPUnit\Framework\TestCase;

require_once(__DIR__ . '/../../../app/PDF/Infrastructure/Adapters/ProyectoFPDFAdapter.php');

class ProyectoFPDFAdapterTest extends TestCase
{
    private $outputDir;

    // Este método se ejecuta antes de cada método de test
    protected function setUp(): void
    {
        $this->outputDir = __DIR__ . '/generated_test_pdfs/';
        // Crear el directorio si no existe
        if (!is_dir($this->outputDir)) {
            mkdir($this->outputDir, 0777, true);
        }
        // Limpiar archivos de prueba antiguos para asegurar un estado limpio
        foreach (glob($this->outputDir . '*.pdf') as $file) {
            unlink($file);
        }
    }

    // Este método se ejecuta después de cada método de test
    protected function tearDown(): void
    {
        // Opcional: Eliminar los PDFs generados después de cada test
        // Descomenta si quieres que los tests no dejen PDFs atrás
        // foreach (glob($this->outputDir . '*.pdf') as $file) {
        //     unlink($file);
        // }
        // if (is_dir($this->outputDir) && count(glob($this->outputDir . '*')) === 0) {
        //     rmdir($this->outputDir);
        // }
    }

    // --- Caso de Prueba 1: Reporte Completo ---
    public function testGenerateFullReport(): void
    {
        $jsonString = <<<'JSON_CONTENT'
{
    "timestamp": "2025-06-01T14:31:33.250590",
    "texto_entrada": "genera el proyecto del desarrollo de una e-commerce que se pueda tener como base para futuros desarrollos para clientes",
    "respuesta_asistente": "**Proyecto: Desarrollo de Plataforma E-Commerce Base**\n\n**Objetivo General:**\n\nCrear una plataforma de comercio electrónico genérica y escalable, que sirva como base sólida para la personalización y adaptación a las necesidades específicas de futuros clientes de LaEmpresa.\n\n**Alcance:**\n\nEl proyecto abarcará el desarrollo de las funcionalidades esenciales de una tienda en línea, con un enfoque en la flexibilidad y la extensibilidad. Se incluirán:\n\n* **Catálogo de Productos:** Gestión de productos (descripción, precio, imágenes, categorías), búsqueda avanzada, filtros y opciones de clasificación.\n* **Carrito de Compras:** Añadir, eliminar y modificar productos; cálculo de totales y gastos de envío.\n* **Proceso de Pago:** Integración con pasarelas de pago (ej. Stripe, PayPal), gestión de diferentes métodos de pago, seguridad en la transacción.\n\n* **Gestión de Usuarios:** Registro y autenticación de usuarios, gestión de perfiles, historial de pedidos.\n* **Gestión de Pedidos:** Creación, seguimiento y actualización de pedidos, notificaciones al cliente, gestión de envíos.\n* **Administración:** Panel de control para la gestión de productos, usuarios, pedidos, configuraciones generales y reportes básicos.\n* **Diseño Responsivo:** Adaptabilidad a diferentes dispositivos (escritorio, tablets, móviles).\n* **API:** Diseño de una API robusta para facilitar futuras integraciones con otros sistemas (CRM, ERP, etc.).\n\n**Tecnologías:**\n\n* **Frontend:** React (o similar), HTML5, CSS3, JavaScript.\n* **Backend:** Node.js con Express (o Python con Django/Flask).\n* **Base de Datos:** PostgreSQL (o MySQL).\n* **Plataforma de Cloud:** AWS, Google Cloud o Azure.\n* **Pasarelas de Pago:** Stripe, PayPal (u otras relevantes).\n\n**Plan de Trabajo (Ejemplo):**\n\n* **Fase 1: Planificación y Diseño (2 semanas)**\n    - Definición detallada de requisitos.\n    - Diseño de la arquitectura de la plataforma.\n    - Diseño de la base de datos.\n    - Selección de tecnologías y herramientas.\n* **Fase 2: Desarrollo del Backend (6 semanas)**\n    - Implementación de la API.\n    - Desarrollo de la lógica de negocio.\n    - Integración con la base de datos.\n    - Implementación de la seguridad.\n* **Fase 3: Desarrollo del Frontend (6 semanas)**\n    - Desarrollo de la interfaz de usuario.\n    - Integración con la API del backend.\n    - Implementación del diseño responsivo.\n    - Pruebas de usabilidad.\n* **Fase 4: Pruebas e Integración (4 semanas)**\n    - Pruebas unitarias, de integración y de sistema.\n    - Corrección de errores.\n    - Integración continua y despliegue automatizado.\n* **Fase 5: Documentación y Despliegue (2 semanas)**\n    - Documentación técnica y de usuario.\n    - Despliegue en el entorno de producción.\n    - Capacitación al equipo de soporte.\n\n**Equipo:**\n\n* Gerente de Proyecto (AI Gerente de Desarrollo AI)\n* Desarrolladores Backend (2)\n* Desarrolladores Frontend (2)\n* Tester (1)\n* Diseñador UI/UX (1)\n\n**Presupuesto (Ejemplo):**\n\n* Salarios del equipo: [Importe]\n* Infraestructura (cloud): [Importe]\n* Herramientas y licencias: [Importe]\n* Pasarelas de pago: [Importe]\n* Marketing y publicidad (inicial): [Importe]\n* Contingencia (10%): [Importe]\n* **Total:** [Importe]\n\n**Gestión de Riesgos:**\n\n* **Riesgo:** Retrasos en el desarrollo.\n    - **Mitigación:** Planificación detallada, seguimiento continuo del progreso, metodologías ágiles.\n* **Riesgo:** Problemas de seguridad.\n    - **Mitigación:** Implementación de buenas prácticas de seguridad, pruebas de penetración, auditorías de seguridad.\n* **Riesgo:** Problemas de escalabilidad.\n    - **Mitigación:** Diseño de una arquitectura escalable, pruebas de carga, optimización del código.\n\n**Indicadores Clave de Rendimiento (KPIs):**\n\n* Cumplimiento del cronograma del proyecto.\n* Cumplimiento del presupuesto.\n* Número de errores encontrados en las pruebas.\n* Rendimiento de la plataforma (tiempo de respuesta, capacidad de carga).\n* Satisfacción del cliente (interno).\n\n**Próximos Pasos:**\n\n1. Aprobación del proyecto.\n2. Asignación de recursos.\n3. Inicio de la fase de planificación y diseño.\n\nEste proyecto sentará las bases para un desarrollo eficiente y rentable de futuras soluciones de comercio electrónico personalizadas para los clientes de LaEmpresa.\n"
}
JSON_CONTENT;

        $data = json_decode($jsonString, true);
        $pdf = new ProyectoFPDFAdapter($data);
        $filePath = $this->outputDir . 'test_full_report.pdf';
        $pdf->Output($filePath, 'F');

        // Afirmaciones:
        $this->assertFileExists($filePath); // Asegura que el archivo PDF fue creado
        $this->assertGreaterThan(1024, filesize($filePath)); // Asegura que el archivo no está vacío (más de 1KB)
    }

    // --- Caso de Prueba 2: JSON con 'respuesta_asistente' vacía ---
    public function testGenerateEmptyContentReport(): void
    {
        $jsonString = <<<'JSON_CONTENT'
{
    "timestamp": "2025-06-02T10:00:00.000000",
    "texto_entrada": "genera un reporte vacío",
    "respuesta_asistente": ""
}
JSON_CONTENT;

        $data = json_decode($jsonString, true);
        $pdf = new ProyectoFPDFAdapter($data);
        $filePath = $this->outputDir . 'test_empty_report.pdf';
        $pdf->Output($filePath, 'F');

        $this->assertFileExists($filePath);
        $this->assertGreaterThan(100, filesize($filePath)); // Será un archivo pequeño con solo cabecera y pie
    }

    // --- Caso de Prueba 3: JSON sin 'timestamp' ---
    public function testGenerateReportWithoutTimestamp(): void
    {
        $jsonString = <<<'JSON_CONTENT'
{
    "texto_entrada": "genera un reporte sin timestamp",
    "respuesta_asistente": "**Reporte sin Timestamp**\n\nEste reporte debería usar la fecha de generación del PDF como fecha del documento, ya que no se proporcionó un timestamp en el JSON de entrada.\n\n* Elemento de lista 1\n* Elemento de lista 2\n"
}
JSON_CONTENT;

        $data = json_decode($jsonString, true);
        $pdf = new ProyectoFPDFAdapter($data);
        $filePath = $this->outputDir . 'test_report_without_timestamp.pdf';
        $pdf->Output($filePath, 'F');

        $this->assertFileExists($filePath);
        $this->assertGreaterThan(1024, filesize($filePath));
    }

    // --- Caso de Prueba 4: Reporte más Corto con solo algunas secciones ---
    public function testGenerateShortReport(): void
    {
        $jsonString = <<<'JSON_CONTENT'
{
    "timestamp": "2025-05-15T09:30:00.000000",
    "texto_entrada": "genera un reporte breve",
    "respuesta_asistente": "**Proyecto Breve de Prueba**\n\n**Objetivo General:**\n\nDemostrar la capacidad de generar PDFs con contenido conciso y estructurado.\n\n**Tecnologías:**\n\n* **Lenguaje:** PHP\n* **Librería:** FPDF\n\n**Próximos Pasos:**\n\n1. Revisar el PDF generado.\n2. Validar el formato.\n"
}
JSON_CONTENT;

        $data = json_decode($jsonString, true);
        $pdf = new ProyectoFPDFAdapter($data);
        $filePath = $this->outputDir . 'test_short_report.pdf';
        $pdf->Output($filePath, 'F');

        $this->assertFileExists($filePath);
        $this->assertGreaterThan(1024, filesize($filePath));
    }

    // --- Caso de Prueba 5: Reporte con Caracteres Especiales y Acentos ---
    public function testGenerateReportWithAccents(): void
    {
        $jsonString = <<<'JSON_CONTENT'
{
    "timestamp": "2025-06-01T18:00:00.000000",
    "texto_entrada": "genera un reporte con acentos y caracteres especiales",
    "respuesta_asistente": "**Título con Acentos y Ñ**\n\nEste documento incluye caracteres especiales como á, é, í, ó, ú, ñ, ü, ¿, ¡, y €. Deberían mostrarse correctamente gracias a la conversión a ISO-8859-1.\n\n**Características:**\n\n* **Éxito:** Manejo de acentos.\n* **Diseño:** Adaptabilidad a diferentes dispositivos.\n* **Información:** Contiene información útil.\n\n¡Qué bien funciona la generación de PDFs!\n"
}
JSON_CONTENT;

        $data = json_decode($jsonString, true);
        $pdf = new ProyectoFPDFAdapter($data);
        $filePath = $this->outputDir . 'test_report_accents.pdf';
        $pdf->Output($filePath, 'F');

        $this->assertFileExists($filePath);
        $this->assertGreaterThan(1024, filesize($filePath));
    }

    // --- Caso de Prueba 6: JSON completamente vacío o nulo (simula datos ausentes) ---
    public function testGenerateReportFromNullJson(): void
    {
        $data = null; // Simula que el JSON de entrada es nulo o inválido
        $pdf = new ProyectoFPDFAdapter($data);
        $filePath = $this->outputDir . 'test_null_json_report.pdf';
        $pdf->Output($filePath, 'F');

        $this->assertFileExists($filePath);
        // Este PDF solo contendrá el mensaje de error y el pie de página, por lo que será pequeño.
        $this->assertGreaterThan(100, filesize($filePath));
    }
}
