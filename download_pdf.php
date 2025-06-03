<?php

// Ruta donde se guardan los PDFs generados
$pdfStoragePath = __DIR__ . '/pdfs/';

// Asegúrate de que el nombre del archivo se ha pasado por la URL
if (isset($_GET['file'])) {
    $fileName = basename(urldecode($_GET['file'])); // Limpiar el nombre del archivo para evitar path traversal
    $filePath = $pdfStoragePath . $fileName;

    // Verificar si el archivo existe y es un PDF
    if (file_exists($filePath) && is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'pdf') {
        // Establecer las cabeceras para forzar la descarga del archivo
        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        // Leer y enviar el archivo al navegador
        readfile($filePath);

        // Opcional: Eliminar el archivo después de la descarga para mantener limpio el servidor
        // unlink($filePath);
        exit();
    } else {
        // Archivo no encontrado o no es un PDF
        http_response_code(404);
        die("Error: El archivo PDF solicitado no se encontró o no es válido.");
    }
} else {
    // No se proporcionó el nombre del archivo
    http_response_code(400);
    die("Error: Nombre de archivo no especificado.");
}
