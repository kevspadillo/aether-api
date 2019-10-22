<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel CORS
    |--------------------------------------------------------------------------
    |
    | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
    | to accept any value.
    |
    */
   
    'supportsCredentials' => false,
    'allowedOrigins' => ['*'],
    'allowedOriginsPatterns' => [],
    'allowedHeaders' => ['Origin', 'X-Requested-With', 'Content-Type', 'Accept', 'authorization'],
    'allowedMethods' => ['DELETE', 'GET', 'PUT', 'PATCH', 'PUT', 'OPTIONS'],
    'exposedHeaders' => [],
    'maxAge' => 0,

];
