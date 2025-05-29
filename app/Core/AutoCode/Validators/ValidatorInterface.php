<?php

namespace App\Core\AutoCode\Validators;

use App\Core\AutoCode\Root\ErrorBag;
use App\Core\AutoCode\Validators\ValidationContext;

/**
 * Define el contrato para cualquier validador.
 */
interface ValidatorInterface
{
    /**
     * Valida un campo en un contexto dado y añade errores a la ErrorBag si es necesario.
     * @param ValidationContext $context El contexto de validación que contiene la definición, el valor y todos los datos.
     * @param ErrorBag $errorBag La bolsa de errores para añadir mensajes.
     * @return bool True si el campo es válido según este validador, false en caso contrario.
     */
    public function validate(ValidationContext $context, ErrorBag $errorBag): bool;
}
