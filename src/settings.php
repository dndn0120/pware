<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => true, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_dir' => __DIR__ . '/../templates/',
            'compile_dir' => __DIR__ . '/../storage/template_c/',
            'config_dir' => __DIR__ . '/../configs/',
            'cache_dir' => __DIR__ . '/../storage/cache/',
            'debugging' => false,
            'caching' => false,
            'cache_lifetime' => 120
        ],

        // Monolog settings
        /*
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../storage/logs/'.date('Ymd').'_app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        
        'logger_cli' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../storage/logs_cli/'.date('Ymd').'_app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        */
        
        'db' => [
            'driver' => 'mysql',
            'database' => 'WHOISG',
            'host' => '58.230.118.167',
            'port' => '3306',
            'charset' => 'UTF8',
            'username' => 'whois',
            'password' => 'ghfeldtm!@34',
        ],
            /*
        'path' => [
            'files' => __DIR__ . '/../storage/files/',
            'tempFiles' => __DIR__.'/../storage/files/tempFiles/'
        ],
        */
    ],
];
