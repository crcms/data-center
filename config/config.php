<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default connection name
    |--------------------------------------------------------------------------
    |
    */

    'default' => env('DATA_CENTER_DRIVER', 'config'),


    'connections' => [
            'redis' => [
                'driver' => 'redis',
                'connection' => 'cache',
            ],

            'database' => [
                'driver' => 'database',
                'table' => 'cache',
                'connection' => null,
            ],

            'file' => [
                'driver' => 'file',
                'path' => './data-center',//storage_path('data-center'),
            ],

            'config' => [
                'driver' => 'file',
                'path' => './config.php',//storage_path('data-center'),
            ],
    ],
];