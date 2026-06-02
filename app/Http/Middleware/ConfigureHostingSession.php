<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ConfigureHostingSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $isHttps = $request->isSecure()
            || strtolower((string) $request->header('X-Forwarded-Proto')) === 'https';

        // Must include port (e.g. :8000) or asset() CSS/JS URLs break on php artisan serve
        if ($host) {
            URL::forceRootUrl($request->getSchemeAndHttpHost());
        }

        if ($isHttps) {
            URL::forceScheme('https');
        }

        // Override bad .env / cached config (common cause of 419 on Hostinger)
        $domain = config('session.domain');
        if (in_array($domain, ['localhost', '127.0.0.1', '.localhost'], true)) {
            config(['session.domain' => null]);
        }

        if (str_ends_with($host, 'citiinfo.com.au')) {
            config([
                'session.domain'      => null,
                'session.secure'      => true,
                'session.same_site'   => 'lax',
            ]);
        } elseif ($isHttps) {
            config(['session.secure' => true]);
        }

        return $next($request);
    }
}
