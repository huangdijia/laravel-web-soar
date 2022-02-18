<?php

declare(strict_types=1);
/**
 * This file is part of laravel-web-soar.
 *
 * @link     https://github.com/huangdijia/laravel-web-soar
 * @document https://github.com/huangdijia/laravel-web-soar/blob/2.x/README.md
 * @contact  huangdijia@gmail.com
 */
return [
    'path' => '/soar',
    'theme' => 'auto',
    'enabled' => env('SOAR_ENABLED', env('APP_ENV') === 'local'),
    'hint' => [
        'enabled' => env('SOAR_HINT_ENABLED', true),
        'connection' => env('SOAR_HINT_CONNECTION', 'mysql'),
        'excludes' => [],
    ],

    '-soar-path' => env('SOAR_PATH', base_path('vendor/bin/soar')),
    '-test-dsn' => [
        'host' => env('SOAR_TEST_DSN_HOST', '127.0.0.1'),
        'port' => env('SOAR_TEST_DSN_PORT', '3306'),
        'dbname' => env('SOAR_TEST_DSN_DBNAME', 'database'),
        'username' => env('SOAR_TEST_DSN_USER', 'root'),
        'password' => env('SOAR_TEST_DSN_PASSWORD', ''),
    ],
    '-log-output' => env('SOAR_LOG_OUTPUT', storage_path('logs/soar.log')),
    '-report-type' => env('SOAR_REPORT_TYPE', 'markdown'),
];
