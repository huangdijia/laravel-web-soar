<?php
/**
 * This file is part of laravel-web-soar.
 *
 * @link     https://github.com/huangdijia/laravel-web-soar
 * @document https://github.com/huangdijia/laravel-web-soar/blob/2.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\WebSoar\Console;

use Illuminate\Console\Command;

class PublishCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'web-soar:publish {--force}';

    /**
     * @var string
     */
    protected $description = 'Publish all of the Web Soar resources';

    public function handle()
    {
        $this->callSilent('vendor:publish', [
            '--tag' => 'view',
            '--force' => (bool) $this->option('force'),
        ]);

        $this->callSilent('vendor:publish', [
            '--tag' => 'web-soar-assets',
            '--force' => (bool) $this->option('force'),
        ]);

        $this->info('Web soar published successfully.');
    }
}
