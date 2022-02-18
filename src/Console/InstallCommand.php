<?php

declare(strict_types=1);
/**
 * This file is part of laravel-web-soar.
 *
 * @link     https://github.com/huangdijia/laravel-web-soar
 * @document https://github.com/huangdijia/laravel-web-soar/blob/2.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\WebSoar\Console;

use Huangdijia\WebSoar\Console\Concerns\InstallSoar;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    use InstallSoar;

    /**
     * @var string
     */
    protected $signature = 'web-soar:install {--force}';

    /**
     * @var string
     */
    protected $description = 'Install config of the Web Soar';

    public function handle()
    {
        $this->comment('Publishing Web Soar Assets...');

        if (! $this->isSoarInstalled()) {
            $this->downloadSoarBinary();
        }

        $this->callSilent('vendor:publish', [
            '--tag' => 'config',
            '--force' => (bool) $this->option('force'),
        ]);

        $this->info('Web soar installed successfully.');
    }
}
