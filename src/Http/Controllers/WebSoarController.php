<?php

namespace Huangdijia\WebSoar\Http\Controllers;

use Guanguans\SoarPHP\Soar;
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
