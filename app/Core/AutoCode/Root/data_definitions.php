<?php

// data_definitions.php

return [
    'Proveedor' => [
        [
            'field_name' => 'id_proveedor',
            'data_type' => 'int',
            'column_name' => 'id_proveedor',
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
    // Puedes añadir más entidades aquí si las necesitas para la generación automática
    // 'Cliente' => [
    //     [
    //         'field_name' => 'id_cliente',
    //         'data_type' => 'int',
    //         'column_name' => 'id_cliente',
    //         'is_primary_key' => true,
    //         'is_autoincrement' => true,
    //         'is_nullable' => false,
    //         'default_value' => null
    //     ],
    //     [
    //         'field_name' => 'nombre_cliente',
    //         'data_type' => 'string',
    //         'column_name' => 'nombre',
    //         'is_primary_key' => false,
    //         'is_autoincrement' => false,
    //         'is_nullable' => false,
    //         'default_value' => ''
    //     ],
    // ],
];