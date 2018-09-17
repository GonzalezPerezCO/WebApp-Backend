<?php
return [
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,

        // jwt settings
        "jwt" => [
            'secret' => base64_encode(openssl_random_pseudo_bytes(16)),
        ],

        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/error.log',
            'level' => \Monolog\Logger::DEBUG
        ],
    ],
];