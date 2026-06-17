<?php

namespace App\Http\Middleware;

use Illuminate\Support\Collection;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful as SanctumMiddleware;

class EnsureFrontendRequestsAreStateful extends SanctumMiddleware
{
    /**
     * citiinfo.com.au (Next.js) → api.citiinfo.com.au must load the web session
     * even when Referer/Origin matching alone fails (e.g. via proxy).
     */
    public static function fromFrontend($request)
    {
        if (parent::fromFrontend($request)) {
            return true;
        }

        if ($request->header('X-Requested-With') !== 'XMLHttpRequest') {
            return false;
        }

        $origin = $request->header('Origin') ?: $request->header('Referer');

        if (! $origin) {
            return false;
        }

        $requestHost = parse_url($origin, PHP_URL_HOST);

        if (! $requestHost) {
            return false;
        }

        return static::allowedFrontendHosts()->contains($requestHost);
    }

    protected static function allowedFrontendHosts(): Collection
    {
        $hosts = collect();

        foreach (explode(',', (string) env('SANCTUM_STATEFUL_DOMAINS', '')) as $host) {
            $host = trim($host);
            if ($host !== '') {
                $hosts->push($host);
            }
        }

        foreach (explode(',', (string) env('CORS_ALLOWED_ORIGINS', '')) as $origin) {
            $host = parse_url(trim($origin), PHP_URL_HOST);
            if ($host) {
                $hosts->push($host);
            }
        }

        $frontendHost = parse_url((string) env('FRONTEND_URL', ''), PHP_URL_HOST);
        if ($frontendHost) {
            $hosts->push($frontendHost);
        }

        return $hosts->unique()->values();
    }
}
