<?php

namespace App\Core\AutoCode\Root;

/**
 * Clase para recolectar y gestionar los errores de validación.
 */
class ErrorBag
{
    private array $errors = [];

    /**
     * Añade un mensaje de error a la bolsa.
     * @param string $message El mensaje de error.
     */
    public function addError(string $message): void
    {
        $this->errors[] = $message;
    }

    /**
     * Obtiene todos los errores recolectados.
     * @return array Un array de mensajes de error.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Verifica si hay algún error en la bolsa.
     * @return bool True si hay errores, false en caso contrario.
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Limpia todos los errores de la bolsa.
     */
    public function clearErrors(): void
    {
        $this->errors = [];
    }

    /**
     * Verifica si hay un error específico para un campo y tipo de validación.
     * Esto requiere una pequeña modificación en cómo se añade el error en los validadores
     * para incluir el campo y el tipo de validación. Para el alcance actual,
     * simplemente verificará si el mensaje contiene el campo.
     * @param string $fieldName El nombre del campo.
     * @param string|null $type El tipo de validación (ej. 'type', 'existence', 'length').
     * @return bool
     */
    public function hasErrorsForField(string $fieldName, ?string $type = null): bool
    {
        // Esta es una implementación simplificada.
        // Una ErrorBag más sofisticada almacenaría los errores como objetos con propiedades
        // (campo, tipo de error, mensaje) para una consulta más precisa.
        foreach ($this->errors as $error) {
            // Un chequeo simple de substring
            if (str_contains($error, "'{$fieldName}'")) {
                if ($type === null || str_contains(strtolower($error), "de tipo {$type}")) { // Muy rudimentario
                    return true;
                }
            }
        }
        return false;
    }
}
