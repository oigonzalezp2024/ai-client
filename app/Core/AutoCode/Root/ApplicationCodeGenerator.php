<?php

namespace App\Core\AutoCode\Root;

use App\Core\AutoCode\Root\CodeConventionConverter;
use App\Core\AutoCode\Root\DataDefinition;
use InvalidArgumentException;

class ApplicationCodeGenerator
{
    private CodeConventionConverter $converter;
    private string $domainEntitiesNamespace;
    private string $domainRepositoriesNamespace;

    // Constructor con los namespaces inyectados correctamente
    public function __construct(CodeConventionConverter $converter, string $domainEntitiesNamespace, string $domainRepositoriesNamespace)
    {
        $this->converter = $converter;
        $this->domainEntitiesNamespace = $domainEntitiesNamespace;
        $this->domainRepositoriesNamespace = $domainRepositoriesNamespace;
    }

    /**
     * Genera casos de uso a nivel de aplicación para una entidad dada.
     *
     * @param DataDefinition[] $definitions Array de objetos DataDefinition para la entidad.
     * @param string $entityName El nombre de la entidad (ej: 'Proveedor').
     * @param string $applicationUseCasesNamespace El namespace para los casos de uso generados.
     * @return array Un array asociativo de nombres de clases generadas y su código.
     */
    public function generate(array $definitions, string $entityName, string $applicationUseCasesNamespace): array
    {
        if (empty($definitions)) {
            throw new InvalidArgumentException('Data definitions array cannot be empty. At least one definition (e.g., for the ID) is required.');
        }

        foreach ($definitions as $definition) {
            if (!$definition instanceof DataDefinition) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Element in $definitions must be an instance of %s, but %s was given.',
                        DataDefinition::class,
                        is_object($definition) ? get_class($definition) : gettype($definition)
                    )
                );
            }
        }

        $useCases = [];
        $repoVarName = $this->converter->toCamelCase($entityName) . 'Repository'; // Ej: 'proveedorRepository'
        $entityVarName = $this->converter->toCamelCase($entityName); // Ej: 'proveedor'

        /** @var DataDefinition $idDefinition */
        $idDefinition = $definitions[0]; // Asumimos que el primer campo es el ID
        $idField = $idDefinition->getFieldName();
        $idPhpType = $idDefinition->getDataType();

        // Obtener campos no-ID para constructor/setters
        $nonIdDefinitions = array_slice($definitions, 1); // Saltar el campo ID
        $constructorParams = [];
        $entitySetters = [];
        foreach ($nonIdDefinitions as $def) {
            $paramName = $this->converter->toCamelCase($def->getFieldName());
            $paramType = $def->getDataType();
            $setterName = 'set' . ucfirst($paramName);

            $paramTypeHint = $paramType;
            if ($def->isNullable()) {
                $paramTypeHint = '?' . $paramType;
            }
            $constructorParams[] = "{$paramTypeHint} \${$paramName}";
            // ¡¡¡LÍNEA CORREGIDA!!! Usar $entityVarName en lugar de $entity y el escape correcto.
            $entitySetters[] = "\${$entityVarName}->{$setterName}(\${$paramName});";
        }
        $constructorParamsString = implode(', ', $constructorParams);
        $entitySettersString = implode("\n        ", $entitySetters); // Asegura la indentación correcta

        // --- Caso de Uso Create ---
        $createUseCaseClassName = "Create{$entityName}UseCase";
        $createUseCaseCode = <<<PHP
<?php

namespace {$applicationUseCasesNamespace}\\{$entityName};

use {$this->domainEntitiesNamespace}\\{$entityName};
use {$this->domainRepositoriesNamespace}\\{$entityName}RepositoryInterface;

class {$createUseCaseClassName}
{
    private {$entityName}RepositoryInterface \${$repoVarName};

    public function __construct({$entityName}RepositoryInterface \${$repoVarName})
    {
        \$this->{$repoVarName} = \${$repoVarName};
    }

    public function execute({$constructorParamsString}): {$entityName}
    {
        \${$entityVarName} = new {$entityName}();
        {$entitySettersString}
        
        return \$this->{$repoVarName}->save(\${$entityVarName});
    }
}
PHP;
        $useCases[$createUseCaseClassName] = $createUseCaseCode;

        // --- Caso de Uso Get By ID ---
        $getByIdUseCaseClassName = "Get{$entityName}ByIdUseCase";
        $getByIdUseCaseCode = <<<PHP
<?php

namespace {$applicationUseCasesNamespace}\\{$entityName};

use {$this->domainEntitiesNamespace}\\{$entityName};
use {$this->domainRepositoriesNamespace}\\{$entityName}RepositoryInterface;

class {$getByIdUseCaseClassName}
{
    private {$entityName}RepositoryInterface \${$repoVarName};

    public function __construct({$entityName}RepositoryInterface \${$repoVarName})
    {
        \$this->{$repoVarName} = \${$repoVarName};
    }

    public function execute({$idPhpType} \$id): ?{$entityName}
    {
        return \$this->{$repoVarName}->findById(\$id);
    }
}
PHP;
        $useCases[$getByIdUseCaseClassName] = $getByIdUseCaseCode;

        // --- Caso de Uso Update ---
        $updateUseCaseClassName = "Update{$entityName}UseCase";
        $updateUseCaseCode = <<<PHP
<?php

namespace {$applicationUseCasesNamespace}\\{$entityName};

use {$this->domainEntitiesNamespace}\\{$entityName};
use {$this->domainRepositoriesNamespace}\\{$entityName}RepositoryInterface;

class {$updateUseCaseClassName}
{
    private {$entityName}RepositoryInterface \${$repoVarName};

    public function __construct({$entityName}RepositoryInterface \${$repoVarName})
    {
        \$this->{$repoVarName} = \${$repoVarName};
    }

    public function execute({$entityName} \${$entityVarName}): {$entityName}
    {
        return \$this->{$repoVarName}->save(\${$entityVarName});
    }
}
PHP;
        $useCases[$updateUseCaseClassName] = $updateUseCaseCode;

        // --- Caso de Uso Delete ---
        $deleteUseCaseClassName = "Delete{$entityName}UseCase";
        $deleteUseCaseCode = <<<PHP
<?php

namespace {$applicationUseCasesNamespace}\\{$entityName};

use {$this->domainEntitiesNamespace}\\{$entityName};
use {$this->domainRepositoriesNamespace}\\{$entityName}RepositoryInterface;

class {$deleteUseCaseClassName}
{
    private {$entityName}RepositoryInterface \${$repoVarName};

    public function __construct({$entityName}RepositoryInterface \${$repoVarName})
    {
        \$this->{$repoVarName} = \${$repoVarName};
    }

    public function execute({$entityName} \${$entityVarName}): void
    {
        \$this->{$repoVarName}->delete(\${$entityVarName});
    }
}
PHP;
        $useCases[$deleteUseCaseClassName] = $deleteUseCaseCode;

        // --- Caso de Uso List All ---
        $listUseCaseClassName = "List{$entityName}sUseCase"; // Nombre con 's' para indicar plural
        $listUseCaseCode = <<<PHP
<?php

namespace {$applicationUseCasesNamespace}\\{$entityName};

use {$this->domainEntitiesNamespace}\\{$entityName}; // Necesario si el tipo de hint de array usa la clase de la entidad
use {$this->domainRepositoriesNamespace}\\{$entityName}RepositoryInterface;
// use Traversable; // Ya no es necesario, ya que devolvemos un array directamente

class {$listUseCaseClassName}
{
    private {$entityName}RepositoryInterface \${$repoVarName};

    public function __construct({$entityName}RepositoryInterface \${$repoVarName})
    {
        \$this->{$repoVarName} = \${$repoVarName};
    }

    /**
     * @return {$entityName}[]|array Retorna un array de objetos {$entityName}.
     */
    public function execute(): array // <<< CAMBIO CRUCIAL: De Traversable a array
    {
        return \$this->{$repoVarName}->findAll();
    }
}
PHP;
        $useCases[$listUseCaseClassName] = $listUseCaseCode;

        return $useCases;
    }
}
