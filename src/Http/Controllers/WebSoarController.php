<?php
/**
 * This file is part of laravel-web-soar.
 *
 * @link     https://github.com/huangdijia/laravel-web-soar
 * @document https://github.com/huangdijia/laravel-web-soar/blob/2.x/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\WebSoar\Http\Controllers;

use Guanguans\SoarPHP\Exceptions\InvalidArgumentException;
use Guanguans\SoarPHP\Soar;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;

class WebSoarController
{
    /**
     * @throws BindingResolutionException
     * @return View
     */
    public function index()
    {
        $tables = [];

        if (config('web-soar.hint.enabled')) {
            $tables = Cache::remember('web-soar:tables', 600, function () {
                $tables = DB::connection(config('web-soar.hint.connection', 'mysql'))
                    ->select('SHOW TABLES');

                return collect(array_map('reset', $tables))
                    ->reject(function ($table) {
                        return in_array($table, config('web-soar.hint.excludes', []));
                    })
                    ->mapWithKeys(function ($table) {
                        return [$table => Schema::getColumnListing($table)];
                    })
                    ->all();
            });
        }

        return view('web-soar::web-soar', [
            'path' => config('web-soar.path'),
            'tables' => $tables,
        ]);
    }

    /**
     * @throws InvalidArgumentException
     * @throws RuntimeException
     * @return string
     */
    public function execute(Request $request, Soar $soar)
    {
        $validated = $request->validate([
            'code' => 'required',
        ]);
        $pattern = '/^\s*explain\s*/i';

        if (preg_match($pattern, $validated['code'])) {
            $validated['code'] = preg_replace($pattern, '', $validated['code']);
            $body = $soar->explain($validated['code'], 'md');
        } else {
            $body = $soar->score($validated['code']);
        }

        return '<div class="markdown-body">' . Markdown::parse($body) . '</div>';
    }
}
