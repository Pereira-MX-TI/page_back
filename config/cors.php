<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['POST', 'GET', 'DELETE', 'PUT', 'OPTIONS', '*'],

    'allowed_origins' => ['http://localhost:2500', '*', '*', '*, *', '*, *, *', '*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['X-Custom-Header', 'Upgrade-Insecure-Requests', 'csrftoken', 'x-csrf-token', 'CSRFToken', 'X-CSRF-TOKEN', 'Access-Control-Allow-Origin', 'content-type', 'accept', '*'],

    'exposed_headers' => ['x-custom-response-header'],

    'max_age' => 0,

    'supports_credentials' => false,
];
