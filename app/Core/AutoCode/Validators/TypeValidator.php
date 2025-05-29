<?php

namespace App\Core\AutoCode\Validators;

use App\Core\AutoCode\Root\ErrorBag;
use App\Core\AutoCode\Validators\ValidationContext;
use App\Core\AutoCode\Root\DataDefinition;

/**
 * Validador para el tipo de dato de un campo y responsable del casting.
 */
class TypeValidator implements ValidatorInterface
{
    public function validate(ValidationContext $context, ErrorBag $errorBag): bool
    {
        $definition = $context->getDefinition();
        $fieldValue = $context->getFieldValue();

        $fieldName = $definition->getFieldName();
        $entityName = $definition->getEntityName();
        $expectedType = $definition->getDataType();

        $isValid = true;
        $castable = false; // Flag para indicar si es un tipo que intentaremos castear

        switch ($expectedType) {
            case 'string':
                if (!is_string($fieldValue) && !is_numeric($fieldValue) && !is_bool($fieldValue) && !is_null($fieldValue)) {
                    $isValid = false;
                } else {
                    $castable = true;
                }
                break;
            case 'integer':
                if (!is_int($fieldValue) && !is_string($fieldValue) && !is_float($fieldValue) && !is_bool($fieldValue) && !is_null($fieldValue)) {
                    $isValid = false;
                } else {
                    $castable = true;
                }
                break;
            case 'float':
                if (!is_float($fieldValue) && !is_string($fieldValue) && !is_int($fieldValue) && !is_null($fieldValue)) {
                    $isValid = false;
                } else {
                    $castable = true;
                }
                break;
            case 'boolean':
                if (!is_bool($fieldValue) && !is_string($fieldValue) && !is_int($fieldValue) && !is_null($fieldValue)) {
                    $isValid = false;
                } else {
                    $castable = true;
                }
                break;
            default:
                // Si el tipo esperado no es manejado, asumimos válido por ahora,
                // o podrías lanzar un error de configuración.
                break;
        }

        if (!$isValid) {
            $errorBag->addError(
                "El campo '{$fieldName}' de la entidad '{$entityName}' debe ser de tipo {$expectedType}."
            );
            return false;
        }
        
        // Si el valor es null, y esperamos un tipo, y el campo no es explícitamente "nullable" en la definición,
        // esto debería haber sido manejado por ExistenceValidator si el campo es requerido.
        // Aquí asumimos que si llega null, y pasa las validaciones de existencia, está bien.
        // Si el campo puede ser null pero se le aplica un TypeValidator, es posible que el validador
        // deba permitir nulls o el casting debe manejarlos.
        if (is_null($fieldValue)) {
            return true; // Si es null, y pasó ExistenceValidator, lo consideramos válido por tipo (no hay tipo que validar).
        }

        return true;
    }

    /**
     * Intenta castear el valor de un campo a su tipo definido.
     * Este método se llama DESPUÉS de que la validación de tipo ha pasado.
     * @param DataDefinition $definition La definición del campo.
     * @param mixed $value El valor a castear.
     * @return mixed El valor casteado.
     */
    public function castValue(DataDefinition $definition, mixed $value): mixed
    {
        if (is_null($value)) {
            return null; // Si el valor es null, devolver null.
        }

        return match ($definition->getDataType()) {
            'string' => (string)$value,
            'integer' => (int)$value,
            'float' => (float)$value,
            'boolean' => (bool)$value,
            default => $value, // No castear si el tipo no es reconocido.
        };
    }
}
