<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'login', 'logout', 'usuarios/candidato', 'usuario', 'usuarios/empresa'], // Os caminhos que você deseja permitir CORS, por exemplo, 'api/*'
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], // Métodos permitidos
    'allowed_origins' => ['*'], // Origens permitidas (ou especifique domínios específicos)
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Headers permitidos
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,

];
