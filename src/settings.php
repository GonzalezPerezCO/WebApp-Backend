<?php
return [
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,

        // jwt settings
        "jwt" => [
            'secret' => base64_encode(random_bytes(16)),
        ],

        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG
        ],
    ],
];