<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default connection name
    |--------------------------------------------------------------------------
    |
    */

    'default' => env('DATA_CENTER_DRIVER', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Data center Connections
    |--------------------------------------------------------------------------
    |
    | Currently only supports database drivers
    |
    | database.connection   Database connection used by default
    | database.table    Data table used for connection
    | database.channel  Default channel for data
    | database.refresh  Local cache time (minutes), set to 0 if no cache is required
    |
    |
    */

    'connections' => [

        'database' => [
            'driver' => 'database',
            'connection' => null,
            'table' => 'data_center',
            'channel' => 'abc',
            'refresh' => 5,//cache refresh time
        ],

        /*'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
        ],*/

        /*'file' => [
            'driver' => 'file',
            'path' => storage_path('data-center'),
        ],*/

    ],
];