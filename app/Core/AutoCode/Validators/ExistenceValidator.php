<?php

namespace App\Core\AutoCode\Validators;

use App\Core\AutoCode\Root\ErrorBag;
use App\Core\AutoCode\Validators\ValidationContext;

/**
 * Validador para verificar si un campo existe en los datos de entrada.
 */
class ExistenceValidator implements ValidatorInterface
{
    public function validate(ValidationContext $context, ErrorBag $errorBag): bool
    {
        $fieldName = $context->getDefinition()->getFieldName();
        $entityName = $context->getDefinition()->getEntityName();
        $allInputData = $context->getAllInputData();

        // Si el campo no está presente en los datos de entrada, se considera que no existe.
        // NOTA: Para campos opcionales, necesitarías una forma de indicarlo en DataDefinition.
        // Por ahora, asumimos que todos los campos en DataDefinition son requeridos.
        if (!array_key_exists($fieldName, $allInputData)) {
            $errorBag->addError("El campo '{$fieldName}' es requerido para la entidad '{$entityName}'.");
            return false;
        }

        return true;
    }
}
