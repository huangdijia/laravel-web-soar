<?php

namespace Huangdijia\WebSoar\Http\Controllers;

use Guanguans\SoarPHP\Soar;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WebSoarController
{
    public function index(Request $request)
    {
        $tables = Cache::remember('web-soar:tables', 600, function () {
            if (!config('web-soar.hint.enabled')) {
                return [];
            }

            $tables = DB::connection(config('web-soar.hint.connection', 'mysql'))
                ->select('SHOW TABLES');

            return collect(array_map('reset', $tables))
                ->reject(function($table) {
                    return in_array($table, config('web-soar.hint.excludes', []));
                })
                ->mapWithKeys(function ($table) {
                    return [$table => Schema::getColumnListing($table)];
                })
                ->all();
        });

        return view('web-soar::web-soar', [
            'path'   => config('web-soar.path'),
            'tables' => $tables,
        ]);
    }

    public function execute(Request $request, Soar $soar)
    {
        $validated = $request->validate([
            'code' => 'required',
        ]);
        $pattern = '/^\s*explain\s*/i';

        if (preg_match($pattern, $validated['code'])) {
            $validated['code'] = preg_replace($pattern, '', $validated['code']);
            $body              = $soar->explain($validated['code'], 'md');
        } else {
            $body = $soar->score($validated['code']);
        }

        return '<div class="markdown-body">' . Markdown::parse($body) . '</div>';

    }
}
