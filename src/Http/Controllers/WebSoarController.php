<?php

namespace Huangdijia\WebSoar\Http\Controllers;

use Guanguans\SoarPHP\Soar;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;

class WebSoarController
{
    public function index(Request $request)
    {
        return view('web-soar::web-soar', [
            'path' => config('web-soar.path'),
        ]);
    }

    public function execute(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required',
        ]);

        $config = collect(config('web-soar'))->filter(function ($item, $key) {
            return substr($key, 0, 1) == '-';
        })
            ->all();

        $soar = new Soar($config);
        $body = '';

        if (preg_match('/^explain/i', $validated['code'])) {
            $validated['code'] = preg_replace('/^explain/i', '', $validated['code']);
            $body              = $soar->explain($validated['code'], 'md');
        } else {
            $body = $soar->score($validated['code']);
        }
        
        return '<div class="markdown-body">' . Markdown::parse($body) . '</div>';

    }
}
