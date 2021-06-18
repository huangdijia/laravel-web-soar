<?php
/**
 * This file is part of laravel-web-soar.
 *
 * @link     https://github.com/huangdijia/laravel-web-soar
 * @document https://github.com/huangdijia/laravel-web-soar/blob/2.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\WebSoar\Console\Concerns;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

/**
 * macOS
 * wget https://github.com/XiaoMi/soar/releases/download/0.11.0/soar.darwin-amd64 -O vendor/bin/soar
 * linux
 * wget https://github.com/XiaoMi/soar/releases/download/0.11.0/soar.linux-amd64 -O vendor/bin/soar
 * windows
 * wget https://github.com/XiaoMi/soar/releases/download/0.11.0/soar.windows-amd64 -O vendor/bin/soar
 * authorization.
 */
trait InstallSoar
{
    /**
     * @var string
     */
    protected $soarVersion = '0.11.0';

    /**
     * @var string
     */
    protected $urlFormat = 'https://github.com/XiaoMi/soar/releases/download/%s/soar.%s-amd64';

    /**
     * @throws BindingResolutionException
     * @return bool
     */
    protected function isSoarInstalled()
    {
        return file_exists(base_path('vendor/bin/soar'));
    }

    /**
     * Download the latest version of the Soar binary.
     * @throws BindingResolutionException
     */
    protected function downloadSoarBinary()
    {
        $soarPath = 'vendor/bin/soar';
        $url = sprintf($this->urlFormat, $this->soarVersion, Str::lower(PHP_OS_FAMILY));

        tap(new Process(array_filter([
            'wget',
            $url,
            '-O',
            $soarPath,
        ]), base_path(), null, null, null))->mustRun(function ($type, $buffer) {
            $this->output->write($buffer);
        });

        chmod(base_path($soarPath), 755);

        $this->line('');
    }
}
