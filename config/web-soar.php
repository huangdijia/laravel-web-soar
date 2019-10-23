<?php

return [
    'path'            => '/soar',
    'theme'           => 'auto',
    'enabled'         => env('SOAR_ENABLED', env('APP_ENV') === 'local'),
    'hint'            => [
        'enabled'    => env('SOAR_HINT_ENABLED', true),
        'connection' => env('SOAR_HINT_CONNECTION', 'mysql'),
        'excludes'   => [],
    ],
    'output_modifier' => \Huangdijia\WebSoar\OutputModifiers\PrefixDateTime::class,

    /**
     * macOS
     * wget https://github.com/XiaoMi/soar/releases/download/0.11.0/soar.darwin-amd64 -O vendor/bin/soar
     * chmod +x vendor/bin/soar
     * linux
     * wget https://github.com/XiaoMi/soar/releases/download/0.11.0/soar.linux-amd64 -O vendor/bin/soar
     * chmod +x vendor/bin/soar
     * windows
     * wget https://github.com/XiaoMi/soar/releases/download/0.11.0/soar.windows-amd64 -O vendor/bin/soar
     * chmod +x vendor/bin/soar
     */
    '-soar-path'      => env('SOAR_PATH', app()->basePath('vendor/bin/soar')),
    '-test-dsn'       => [
        'host'     => env('SOAR_TEST_DSN_HOST', '127.0.0.1'),
        'port'     => env('SOAR_TEST_DSN_PORT', '3306'),
        'dbname'   => env('SOAR_TEST_DSN_DBNAME', 'database'),
        'username' => env('SOAR_TEST_DSN_USER', 'root'),
        'password' => env('SOAR_TEST_DSN_PASSWORD', ''),
    ],
    '-log-output'     => env('SOAR_LOG_OUTPUT', storage_path('logs/soar.log')),
    '-report-type'    => env('SOAR_REPORT_TYPE', 'markdown'),
];
