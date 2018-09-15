<?php
return [
    'settings' => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,

        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/error.log',
            'level' => \Monolog\Logger::DEBUG
        ],
    ],
];