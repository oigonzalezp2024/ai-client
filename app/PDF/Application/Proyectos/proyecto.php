<?php
// C:\xampp\htdocs\web20250530\ai-client\app\PDF\Application\Proyectos\proyecto.php

// Crucial: No hay espacios ni nuevas líneas antes de esta etiqueta <?php!

// Configura el reporte de errores para depuración.
// E_ALL & ~E_DEPRECATED mostrará todos los errores excepto las advertencias de funciones obsoletas.
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED); // Mantén esto así para ignorar los deprecados de utf8_decode()

// Asegúrate de que esta ruta sea correcta en relación con proyecto.php
require_once(__DIR__ . '/../../Infrastructure/Adapters/ProyectoFPDFAdapter.php');

// --- Ejemplo de uso ---
$jsonString = <<<'JSON_CONTENT'
{
    "timestamp": "2025-06-01T14:31:33.250590",
    "texto_entrada": "genera el proyecto del desarrollo de una e-commerce que se pueda tener como base para futuros desarrollos para clientes",
    "respuesta_asistente": "**Proyecto: Desarrollo de Plataforma E-Commerce Base**\n\n**Objetivo General:**\n\nCrear una plataforma de comercio electrónico genérica y escalable, que sirva como base sólida para la personalización y adaptación a las necesidades específicas de futuros clientes de LaEmpresa.\n\n**Alcance:**\n\nEl proyecto abarcará el desarrollo de las funcionalidades esenciales de una tienda en línea, con un enfoque en la flexibilidad y la extensibilidad.  Se incluirán:\n\n* **Catálogo de Productos:** Gestión de productos (descripción, precio, imágenes, categorías), búsqueda avanzada, filtros y opciones de clasificación.\n* **Carrito de Compras:** Añadir, eliminar y modificar productos; cálculo de totales y gastos de envío.\n* **Proceso de Pago:** Integración con pasarelas de pago (ej. Stripe, PayPal), gestión de diferentes métodos de pago, seguridad en la transacción.\n* **Gestión de Usuarios:** Registro y autenticación de usuarios, gestión de perfiles, historial de pedidos.\n* **Gestión de Pedidos:** Creación, seguimiento y actualización de pedidos, notificaciones al cliente, gestión de envíos.\n* **Administración:** Panel de control para la gestión de productos, usuarios, pedidos, configuraciones generales y reportes básicos.\n* **Diseño Responsivo:** Adaptabilidad a diferentes dispositivos (escritorio, tablets, móviles).\n* **API:** Diseño de una API robusta para facilitar futuras integraciones con otros sistemas (CRM, ERP, etc.).\n\n**Tecnologías:**\n\n* **Frontend:** React (o similar), HTML5, CSS3, JavaScript.\n* **Backend:** Node.js con Express (o Python con Django/Flask).\n* **Base de Datos:** PostgreSQL (o MySQL).\n* **Plataforma de Cloud:** AWS, Google Cloud o Azure.\n* **Pasarelas de Pago:** Stripe, PayPal (u otras relevantes).\n\n**Plan de Trabajo (Ejemplo):**\n\n* **Fase 1: Planificación y Diseño (2 semanas)**\n    -   Definición detallada de requisitos.\n    -   Diseño de la arquitectura de la plataforma.\n    -   Diseño de la base de datos.\n    -   Selección de tecnologías y herramientas.\n* **Fase 2: Desarrollo del Backend (6 semanas)**\n    -   Implementación de la API.\n    -   Desarrollo de la lógica de negocio.\n    -   Integración con la base de datos.\n    -   Implementación de la seguridad.\n* **Fase 3: Desarrollo del Frontend (6 semanas)**\n    -   Desarrollo de la interfaz de usuario.\n    -   Integración con la API del backend.\n    -   Implementación del diseño responsivo.\n    -   Pruebas de usabilidad.\n* **Fase 4: Pruebas e Integración (4 semanas)**\n    -   Pruebas unitarias, de integración y de sistema.\n    -   Corrección de errores.\n    -   Integración continua y despliegue automatizado.\n* **Fase 5: Documentación y Despliegue (2 semanas)**\n    -   Documentación técnica y de usuario.\n    -   Despliegue en el entorno de producción.\n    -   Capacitación al equipo de soporte.\n\n**Equipo:**\n\n* Gerente de Proyecto (AI Gerente de Desarrollo AI)\n* Desarrolladores Backend (2)\n* Desarrolladores Frontend (2)\n* Tester (1)\n* Diseñador UI/UX (1)\n\n**Presupuesto (Ejemplo):**\n\n* Salarios del equipo: [Importe]\n* Infraestructura (cloud): [Importe]\n* Herramientas y licencias: [Importe]\n* Pasarelas de pago: [Importe]\n* Marketing y publicidad (inicial): [Importe]\n* Contingencia (10%): [Importe]\n* **Total:** [Importe]\n\n**Gestión de Riesgos:**\n\n* **Riesgo:** Retrasos en el desarrollo.\n    -   **Mitigación:** Planificación detallada, seguimiento continuo del progreso, metodologías ágiles.\n* **Riesgo:** Problemas de seguridad.\n    -   **Mitigación:** Implementación de buenas prácticas de seguridad, pruebas de penetración, auditorías de seguridad.\n* **Riesgo:** Problemas de escalabilidad.\n    -   **Mitigación:** Diseño de una arquitectura escalable, pruebas de carga, optimización del código.\n\n**Indicadores Clave de Rendimiento (KPIs):**\n\n* Cumplimiento del cronograma del proyecto.\n* Cumplimiento del presupuesto.\n* Número de errores encontrados en las pruebas.\n* Rendimiento de la plataforma (tiempo de respuesta, capacidad de carga).\n* Satisfacción del cliente (interno).\n\n**Próximos Pasos:**\n\n1.  Aprobación del proyecto.\n2.  Asignación de recursos.\n3.  Inicio de la fase de planificación y diseño.\n\nEste proyecto sentará las bases para un desarrollo eficiente y rentable de futuras soluciones de comercio electrónico personalizadas para los clientes de LaEmpresa.\n"
}
JSON_CONTENT;

// Decodifica el JSON
$data = json_decode($jsonString, true);

// Crea una instancia del adaptador FPDF con los datos del JSON
$pdf = new ProyectoFPDFAdapter($data);

// Genera el PDF a partir del JSON
$pdf->GeneratePdfFromJson();

// Salida del PDF: 'I' para mostrar en el navegador, 'D' para descargar
$pdf->Output('I', 'reporte_ecommerce.pdf');

// Omite la etiqueta de cierre
