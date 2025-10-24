<?php

return [
    'paths' => ['api/*', 'admin/*'], // Specify the paths that should allow CORS
    'allowed_methods' => ['*'], // Allow all HTTP methods (GET, POST, OPTIONS, etc.)
    'allowed_origins' => ['http://localhost:5173'], // Your frontend origin
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'], // Allow all headers
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false, // Set to true if you need to send cookies or auth headers
];
