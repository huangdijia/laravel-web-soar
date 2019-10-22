<?php

namespace Huangdijia\WebSoar\Http\Controllers;

use Guanguans\SoarPHP\Soar;
use Illuminate\Http\Request;

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

        return $soar->score($validated['code']);
    }
}
