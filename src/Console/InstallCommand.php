<?php

namespace Huangdijia\WebSoar\Console;

use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;

class InstallCommand extends Command
{
    use DetectsApplicationNamespace;

    protected $signature = 'web-soar:install';

    protected $description = 'Install all of the Web Soar resources';

    public function handle()
    {
        $this->comment('Publishing Web Soar Assets...');

        $this->callSilent('vendor:publish', ['--tag' => 'web-soar-assets', '--force' => true]);

        $this->info('Web soar installed successfully.');
    }
}
