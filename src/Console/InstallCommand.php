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

class InstallCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'web-soar:install';

    /**
     * @var string
     */
    protected $description = 'Install all of the Web Soar resources';

    public function handle()
    {
        $this->comment('Publishing Web Soar Assets...');

        $this->callSilent('vendor:publish', [
            '--tag' => 'web-soar-assets',
            '--force' => true,
        ]);

        $this->info('Web soar installed successfully.');
    }
}
