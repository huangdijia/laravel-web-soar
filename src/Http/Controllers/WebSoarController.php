<?php

namespace Huangdijia\WebSoar\Http\Controllers;

use Guanguans\SoarPHP\Soar;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class WebSoarController
{
    public function index(Request $request)
    {
        $tables = Cache::remember('web-soar:tables', 600, function () {
            return collect(array_map('reset', \DB::select('SHOW TABLES')))
                ->mapWithKeys(function ($table, $key) {
                    $columns = Schema::getColumnListing($table);
                    return [$table => $columns];
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
