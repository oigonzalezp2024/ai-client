<?php

namespace App\Core\AutoCode\Validators;

use App\Core\AutoCode\Root\DataDefinition;

/**
 * Encapsula el contexto necesario para que un validador realice su trabajo.
 * Contiene la definición del campo, el valor actual del campo y todos los datos de entrada.
 */
class ValidationContext
{
    private DataDefinition $definition;
    private mixed $fieldValue;
    private array $allInputData;

    public function __construct(DataDefinition $definition, mixed $fieldValue, array $allInputData)
    {
        $this->definition = $definition;
        $this->fieldValue = $fieldValue;
        $this->allInputData = $allInputData;
    }

    public function getDefinition(): DataDefinition
    {
        return $this->definition;
    }

    public function getFieldValue(): mixed
    {
        return $this->fieldValue;
    }

    public function getAllInputData(): array
    {
        return $this->allInputData;
    }
}
