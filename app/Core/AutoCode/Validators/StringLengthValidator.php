<?php

namespace App\Core\AutoCode\Validators;

use App\Core\AutoCode\Root\ErrorBag;
use App\Core\AutoCode\Validators\ValidationContext;

/**
 * Validador para la longitud máxima de campos de tipo string.
 */
class StringLengthValidator implements ValidatorInterface
{
    public function validate(ValidationContext $context, ErrorBag $errorBag): bool
    {
        $definition = $context->getDefinition();
        $fieldValue = $context->getFieldValue();

        // Este validador solo aplica a strings
        if ($definition->getDataType() !== 'string') {
            return true; // No es su responsabilidad validar otros tipos
        }

        // Si el campo no es string o es nulo (si ExistenceValidator lo permite), no validamos su longitud.
        // La validación de tipo se encargaría de esto primero.
        if (!is_string($fieldValue)) {
            return true;
        }

        $maxLength = (int)$definition->getLength();
        if (mb_strlen($fieldValue) > $maxLength) {
            $errorBag->addError(
                "El campo '{$definition->getFieldName()}' de la entidad '{$definition->getEntityName()}' " .
                "excede la longitud máxima permitida de {$maxLength} caracteres."
            );
            return false;
        }

        return true;
    }
}
