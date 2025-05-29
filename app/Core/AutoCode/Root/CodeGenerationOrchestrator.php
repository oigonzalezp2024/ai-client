<?php

namespace App\Core\AutoCode\Root;

use App\Core\AutoCode\Root\DataLoader;
use App\Core\AutoCode\Root\CodeGenerator;
use App\Core\AutoCode\Root\InfrastructureCodeGenerator;
use App\Core\AutoCode\Root\ApplicationCodeGenerator;
use App\Core\AutoCode\Root\CodeConventionConverter; // ¡Importar la clase!

class CodeGenerationOrchestrator
{
    private DataLoader $dataLoader;
    private CodeGenerator $entityGenerator;
    private InfrastructureCodeGenerator $infrastructureGenerator;
    private ApplicationCodeGenerator $applicationGenerator;
    private CodeConventionConverter $converter; // ¡Nueva propiedad!

    private string $outputDirDomain;
    private string $outputDirInfrastructure;
    private string $outputDirApplication;
    private string $outputDirDomainRepositories;

    private string $domainEntitiesNamespace;
    private string $infrastructureNamespace;
    private string $domainRepositoriesNamespace;
    private string $applicationUseCasesNamespace;

    public function __construct(
        DataLoader $dataLoader,
        CodeGenerator $entityGenerator,
        InfrastructureCodeGenerator $infrastructureGenerator,
        ApplicationCodeGenerator $applicationGenerator,
        CodeConventionConverter $converter, // ¡Nuevo parámetro en el constructor!
        string $outputDirBase,
        string $domainEntitiesNamespace,
        string $infrastructureNamespace,
        string $domainRepositoriesNamespace,
        string $applicationUseCasesNamespace = 'App\\Application\\UseCases'
    ) {
        $this->dataLoader = $dataLoader;
        $this->entityGenerator = $entityGenerator;
        $this->infrastructureGenerator = $infrastructureGenerator;
        $this->applicationGenerator = $applicationGenerator;
        $this->converter = $converter; // ¡Asignar la nueva propiedad!

        $this->outputDirDomain = $outputDirBase . 'app/Domain/Entities/';
        $this->outputDirInfrastructure = $outputDirBase . 'app/Infrastructure/Persistence/MySQL/';
        $this->outputDirApplication = $outputDirBase . 'app/Application/UseCases/';
        $this->outputDirDomainRepositories = $outputDirBase . 'app/Domain/Repositories/';

        $this->domainEntitiesNamespace = $domainEntitiesNamespace;
        $this->infrastructureNamespace = $infrastructureNamespace;
        $this->domainRepositoriesNamespace = $domainRepositoriesNamespace;
        $this->applicationUseCasesNamespace = $applicationUseCasesNamespace;

        $this->ensureOutputDirectoriesExist();
    }

    private function ensureOutputDirectoriesExist(): void
    {
        $dirs = [
            $this->outputDirDomain,
            $this->outputDirInfrastructure,
            $this->outputDirApplication,
            $this->outputDirDomainRepositories
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
                echo "Directorio creado: {$dir}\n";
            }
        }
    }

    public function generateCode(): void
    {
        echo "Iniciando la generación de código...\n";

        $allDataDefinitions = $this->dataLoader->load();

        if (empty($allDataDefinitions)) {
            echo "No se encontraron definiciones de datos para generar código.\n";
            return;
        }

        foreach ($allDataDefinitions as $entityName => $definitions) {
            echo "Generando código para la entidad: {$entityName}\n";

            // --- Generar Entidad de Dominio ---
            $entityCode = $this->entityGenerator->generate(
                $definitions,
                $entityName,
                $this->domainEntitiesNamespace
            );
            file_put_contents("{$this->outputDirDomain}{$entityName}.php", $entityCode);
            echo "    - Entidad de Dominio: {$entityName}.php creada.\n";

            // --- Generar Interfaz de Repositorio de Dominio ---
            $repositoryInterfaceName = "{$entityName}RepositoryInterface";
            // ¡LÍNEA CORREGIDA! Usamos el converter directamente
            $repoVarName = $this->converter->toCamelCase($entityName); 

            // Obtener el tipo de PHP para el ID desde la primera definición de datos
            $idDefinition = $definitions[0] ?? null;
            // Asegurarse de que el tipo sea 'int' si la definición lo indica.
            // Esto fue el origen del error de compatibilidad
            $idPhpTypeForInterface = ($idDefinition) ? $idDefinition->getDataType() : 'string';
            
            $repositoryInterfaceCode = "<?php\n\n";
            $repositoryInterfaceCode .= "namespace {$this->domainRepositoriesNamespace};\n\n";
            $repositoryInterfaceCode .= "use {$this->domainEntitiesNamespace}\\{$entityName};\n\n";
            $repositoryInterfaceCode .= "interface {$repositoryInterfaceName}\n";
            $repositoryInterfaceCode .= "{\n";
            $repositoryInterfaceCode .= "    public function findById({$idPhpTypeForInterface} \$id): ?{$entityName};\n";
            $repositoryInterfaceCode .= "    public function save({$entityName} \${$repoVarName}): {$entityName};\n";
            $repositoryInterfaceCode .= "    public function delete({$entityName} \${$repoVarName}): void;\n";
            $repositoryInterfaceCode .= "    public function findAll(): array;\n";
            $repositoryInterfaceCode .= "}\n";

            file_put_contents("{$this->outputDirDomainRepositories}{$repositoryInterfaceName}.php", $repositoryInterfaceCode);
            echo "    - Interfaz de Repositorio: {$repositoryInterfaceName}.php creada.\n";

            // --- Generar Implementación de Repositorio de Infraestructura ---
            $infrastructureCode = $this->infrastructureGenerator->generate(
                $definitions,
                $entityName,
                $this->infrastructureNamespace,
                $this->domainEntitiesNamespace,
                $this->domainRepositoriesNamespace
            );
            file_put_contents("{$this->outputDirInfrastructure}{$entityName}Repository.php", $infrastructureCode);
            echo "    - Implementación de Repositorio: {$entityName}Repository.php creada.\n";

            // --- Generar Casos de Uso de Aplicación ---
            $applicationUseCases = $this->applicationGenerator->generate(
                $definitions,
                $entityName,
                $this->applicationUseCasesNamespace
            );

            foreach ($applicationUseCases as $useCaseClassName => $useCaseCode) {
                $entityUseCaseDir = "{$this->outputDirApplication}{$entityName}/";
                if (!is_dir($entityUseCaseDir)) {
                    mkdir($entityUseCaseDir, 0777, true);
                    echo "Directorio creado: {$entityUseCaseDir}\n";
                }
                file_put_contents("{$entityUseCaseDir}{$useCaseClassName}.php", $useCaseCode);
                echo "    - Caso de Uso: {$useCaseClassName}.php creada.\n";
            }

            echo "----------------------------------------\n";
        }

        echo "Proceso de generación de código completado.\n";
    }
}
