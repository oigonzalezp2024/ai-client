<?php
// C:\xampp\htdocs\web20250527\ai-client\app\Core\AutoCode\run.php

// Asegúrate de que esta ruta a autoload.php sea correcta
// Esta ruta asume que run.php está en app/Core/AutoCode y vendor/autoload.php está en la raíz del proyecto.
require_once __DIR__ . '/vendor/autoload.php';

use App\Core\AutoCode\Root\CodeGenerationOrchestrator;
use App\Core\AutoCode\Root\DataLoader;
use App\Core\AutoCode\Root\CodeGenerator; // Para la generación de la Entidad de Dominio
use App\Core\AutoCode\Root\InfrastructureCodeGenerator;
use App\Core\AutoCode\Root\ApplicationCodeGenerator;
use App\Core\AutoCode\Root\CodeConventionConverter;
use App\Core\AutoCode\Root\DataDefinition; // Asegúrate de que DataDefinition también esté importada

echo "--- Iniciando el Proceso de Generación de Código ---\n";

// Definiciones de namespaces y directorios
$projectRoot = __DIR__ . '/'; // Ruta base del proyecto (ai-client)

// Rutas a los archivos de definiciones y plantillas
$dataDefinitionsFile = $projectRoot . 'data_definitions.php';
$entityTemplatePath = $projectRoot . 'app/Core/AutoCode/Templates/Domain/Entity.tpl'; // Asegúrate de que esta ruta y nombre de archivo sean correctos
$infrastructureRepositoryTemplatePath = $projectRoot . 'app/Core/AutoCode/Templates/Infrastructure/Repository.tpl'; // Asegúrate de que esta ruta y nombre de archivo sean correctos

// Namespaces utilizados en la generación
$domainEntitiesNamespace = 'App\\Domain\\Entities';
$infrastructureNamespace = 'App\\Infrastructure\\Persistence\\MySQL';
$domainRepositoriesNamespace = 'App\\Domain\\Repositories';
$applicationUseCasesNamespace = 'App\\Application\\UseCases';

// Clase de conexión a la base de datos (para InfrastructureCodeGenerator)
$databaseConnectionClassFQN = 'App\\Infrastructure\\Persistence\\MySQL\\DatabaseConnection';
$databaseConnectionClassName = 'DatabaseConnection'; // Solo el nombre de la clase sin namespace

// Instanciar el convertidor de convenciones de código
$codeConventionConverter = new CodeConventionConverter();

// Instanciar los generadores de código
$dataLoader = new DataLoader($dataDefinitionsFile);

// CodeGenerator es para las Entidades de Dominio
$entityGenerator = new CodeGenerator($entityTemplatePath, $codeConventionConverter);

// InfrastructureCodeGenerator es para las implementaciones de Repositorio
$infrastructureGenerator = new InfrastructureCodeGenerator(
    $infrastructureRepositoryTemplatePath,
    $databaseConnectionClassFQN,
    $databaseConnectionClassName,
    $codeConventionConverter
);

// ApplicationCodeGenerator es para los Casos de Uso
$applicationGenerator = new ApplicationCodeGenerator(
    $codeConventionConverter,
    $domainEntitiesNamespace,
    $domainRepositoriesNamespace
);

// Instanciar el orquestador
$orchestrator = new CodeGenerationOrchestrator(
    $dataLoader,
    $entityGenerator,
    $infrastructureGenerator,
    $applicationGenerator,
    $codeConventionConverter, // ¡NUEVO: Pasamos el converter aquí!
    $projectRoot, // El directorio raíz del proyecto
    $domainEntitiesNamespace,
    $infrastructureNamespace,
    $domainRepositoriesNamespace,
    $applicationUseCasesNamespace
);

// Ejecutar el proceso de generación de código
try {
    $orchestrator->generateCode();
    echo "\n----------------------------------------\n";
    echo "Proceso de generación de código completado.\n\n";

    echo "Actualizando autoloader de Composer...\n";
    // Ejecutar composer dump-autoload para actualizar el autoloader
    // Esto es crucial para que las nuevas clases sean reconocidas por PHP
    $composerCommand = 'composer'; // Asume que 'composer' está en el PATH del sistema

    // Adaptación para Windows o Linux/macOS
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        // En Windows, Composer puede ser composer.bat o composer.phar. Intentaremos varios métodos.
        $composerFound = false;
        $commandsToTry = ['composer.bat', 'composer', 'php composer.phar'];

        foreach ($commandsToTry as $cmd) {
            $testOutput = [];
            $testReturn = 0;
            exec($cmd . ' --version', $testOutput, $testReturn);
            if ($testReturn === 0) {
                $composerCommand = $cmd;
                $composerFound = true;
                break;
            }
        }

        if (!$composerFound) {
            echo "Advertencia: Composer no se encontró en el PATH ni 'composer.phar' en la raíz del proyecto.\n";
            echo "El autoloader no se actualizará automáticamente. Por favor, ejecute 'composer dump-autoload' manualmente desde la raíz de su proyecto.\n";
        }

    }
    // Si no estamos en Windows, o si se encontró Composer en Windows
    if (!empty($composerCommand)) {
        $output = [];
        $return_var = 0;

        // Cambiar al directorio raíz del proyecto para ejecutar composer
        $currentDir = getcwd();
        chdir($projectRoot);
        exec($composerCommand . ' dump-autoload', $output, $return_var);
        chdir($currentDir); // Volver al directorio original

        if ($return_var === 0) {
            echo implode("\n", $output);
            echo "\nAutoloader de Composer actualizado correctamente.\n";
        } else {
            echo "\nError al actualizar el autoloader de Composer. Código de retorno: {$return_var}\n";
            echo implode("\n", $output);
            echo "\nAsegúrate de que 'composer' esté en tu PATH o que 'composer.phar' esté en la raíz de tu proyecto ('{$projectRoot}').\n";
        }
    }


    echo "\n--- Proceso de Generación de Código Completado Exitosamente. ---\n";

} catch (Exception $e) {
    echo "Error durante la generación de código: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
