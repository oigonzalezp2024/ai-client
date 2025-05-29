<?php

namespace {{NAMESPACE}};

{{USES}}

class {{ENTITY_NAME}}
{
{{PROPERTIES}}

    public function __construct()
    {
{{CONSTRUCTOR_BODY}}
    }

{{GETTERS}}

{{SETTERS}}

    /**
     * Crea una instancia de {{ENTITY_NAME}} a partir de un array de datos (ej. desde una base de datos).
     *
     * @param array $data Array asociativo con los datos de la entidad.
     * @return self Una nueva instancia de {{ENTITY_NAME}}.
     * @throws InvalidArgumentException Si faltan campos requeridos o los tipos no coinciden.
     */
    public static function fromArray(array $data): self
    {
{{FROM_ARRAY_BODY}}
    }

}