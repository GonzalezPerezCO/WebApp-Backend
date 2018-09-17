<?php
return [
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,

        // Database connection settings
        "db" => [
            "host" => "127.0.0.1",
            "dbname" => "deportes",
            "user" => "admin",
            "pass" => "Fr4nc15c0"
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/error.log',
            'level' => \Monolog\Logger::DEBUG
        ],
    ],
];