<?php

namespace Huangdijia\WebSoar\Http\Middleware;

use Illuminate\Support\Facades\Gate;

class Authorize
{
    public function handle($request, $next)
    {
        return $this->allowedToUseSoar()
            ? $next($request)
            : abort(403);
    }

    protected function allowedToUseSoar(): bool
    {
        if (! config('web-soar.enabled')) {
            return false;
        }

        return Gate::check('viewWebSoar');
    }
}
