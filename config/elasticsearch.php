<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Elastic Search client IP
    |--------------------------------------------------------------------------
    |
    */

    'client_ip' => env('ELASTIC_SEARCH_HOST', 'localhost'),

    /*
    |--------------------------------------------------------------------------
    | Elastic Search client Port number
    |--------------------------------------------------------------------------
    |
    */

    'client_port' => env('ELASTIC_SEARCH_PORT', '9200'),

    /*
    |--------------------------------------------------------------------------
    | Elastic Search Base Index Name
    |--------------------------------------------------------------------------
    |
    */

    'base_language_index_name' => env('ELASTIC_SEARCH_BASE_LANGUAGE_NAME', 'api'),
];