<?php

// data_definitions.php

return [
    'Persona' => [
        [
            'field_name' => 'id_persona',
            'data_type' => 'int',
            'column_name' => 'id_persona',
            'is_primary_key' => true,
            'is_autoincrement' => true,
            'is_nullable' => false,
            'default_value' => null
        ],
        [
            'field_name' => 'nombre',
            'data_type' => 'string',
            'column_name' => 'nombre',
            'is_primary_key' => false,
            'is_autoincrement' => false,
            'is_nullable' => false,
            'default_value' => ''
        ],
        [
            'field_name' => 'celular',
            'data_type' => 'string',
            'column_name' => 'celular',
            'is_primary_key' => false,
            'is_autoincrement' => false,
            'is_nullable' => false,
            'default_value' => ''
        ],
        [
            'field_name' => 'fecha_registro',
            'data_type' => 'string',
            'column_name' => 'fecha_registro',
            'is_primary_key' => false,
            'is_autoincrement' => false,
            'is_nullable' => true,
            'default_value' => null
        ],
        [
            'field_name' => 'activo',
            'data_type' => 'bool',
            'column_name' => 'activo',
            'is_primary_key' => false,
            'is_autoincrement' => false,
            'is_nullable' => false,
            'default_value' => true
        ],
    ],
    'Pregunta' => [
        [
            'field_name' => 'id_pregunta',
            'data_type' => 'int',
            'column_name' => 'id_pregunta',
            'is_primary_key' => true,
            'is_autoincrement' => true,
            'is_nullable' => false,
            'default_value' => null
        ],
        [
            'field_name' => 'su_pregunta',
            'data_type' => 'string',
            'column_name' => 'su_pregunta',
            'is_primary_key' => false,
            'is_autoincrement' => false,
            'is_nullable' => false,
            'default_value' => ''
        ],
        [
            'field_name' => 'respuesta',
            'data_type' => 'string',
            'column_name' => 'respuesta',
            'is_primary_key' => false,
            'is_autoincrement' => false,
            'is_nullable' => true,
            'default_value' => ''
        ],
        [
            'field_name' => 'persona_id',
            'data_type' => 'int',
            'column_name' => 'persona_id',
            'is_primary_key' => false, // <-- ¡CAMBIO CLAVE! Esto debe ser FALSE.
            'is_autoincrement' => false,
            'is_nullable' => false,
            'default_value' => 0
        ],
    ],
];