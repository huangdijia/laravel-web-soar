<?php
/**
 * This file is part of laravel-web-soar.
 *
 * @link     https://github.com/huangdijia/laravel-web-soar
 * @document https://github.com/huangdijia/laravel-web-soar/blob/master/README.md
 * @contact  huangdijia@gmail.com
 */
namespace Huangdijia\WebSoar\Http\Middleware;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Authorize
{
    /**
     * @param Request $request
     * @param Closure $next
     * @throws BindingResolutionException
     * @throws HttpException
     * @throws NotFoundHttpException
     * @return Response
     */
    public function handle($request, $next)
    {
        if (! $this->allowedToUseSoar()) {
            abort(403);
        }

        return $next($request);
    }

    /**
     * @throws BindingResolutionException
     */
    protected function allowedToUseSoar(): bool
    {
        if (! config('web-soar.enabled')) {
            return false;
        }

        return Gate::check('viewWebSoar');
    }
}
