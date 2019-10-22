<?php

namespace Huangdijia\WebSoar\Http\Controllers;

use Guanguans\SoarPHP\Soar;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;

class WebSoarController
{
    public function index()
    {
        return view('web-soar::web-soar', ['path' => config('web-soar.path')]);
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

        if ($config['-report-type'] == 'markdown') {
            return '<div class="markdown-body">' . Markdown::parse($soar->score($validated['code'])) . '</div>';
        }

        return $soar->score($validated['code']);
    }
}
