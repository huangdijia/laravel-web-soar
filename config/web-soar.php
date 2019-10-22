<?php

return [

    /*
     * The web soar page will be available on this path.
     */
    'path' => '/soar',

    /*
     * Possible values are 'auto', 'light' and 'dark'.
     */
    'theme' => 'auto',

    /*
     * By default this package will only run in local development.
     * Do not change this, unless you know what your are doing.
     */
    'enabled' => env('APP_ENV') === 'local',

   /*
    * This class can modify the output returned by Soar. You can replace this with
    * any class that implements \Huangdijia\WebSoar\OutputModifiers\OutputModifier.
    */
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
    '-soar-path' => env('SOAR_PATH', app()->basePath('vendor/bin/soar')),

    // dsn config
    '-test-dsn' => [
        'host'     => env('SOAR_TEST_DSN_HOST', '127.0.0.1'),
        'port'     => env('SOAR_TEST_DSN_PORT', '3306'),
        'dbname'   => env('SOAR_TEST_DSN_DBNAME', 'database'),
        'username' => env('SOAR_TEST_DSN_USER', 'root'),
        'password' => env('SOAR_TEST_DSN_PASSWORD', ''),
    ],

    // log
    '-log-output' => env('SOAR_LOG_OUTPUT', storage_path('logs/soar.log')),

    // output format, default markdown [markdown, html, json]
    '-report-type' => env('SOAR_REPORT_TYPE', 'markdown'),
];
