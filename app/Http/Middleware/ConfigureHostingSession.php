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

        // API JSON must use APP_URL for storage links (not X-Forwarded-Host from Next.js proxy).
        if ($request->is('api/*')) {
            $appUrl = rtrim((string) config('app.url'), '/');
            if ($appUrl !== '') {
                URL::forceRootUrl($appUrl);
            }
        } elseif ($host) {
            URL::forceRootUrl($request->getSchemeAndHttpHost());
        }

        if ($isHttps) {
            URL::forceScheme('https');
        }

        // Keep SESSION_DOMAIN=localhost so the same browser session works on :3000 → :8000 API calls.
        $domain = config('session.domain');
        if (in_array($domain, ['127.0.0.1', '.127.0.0.1'], true)) {
            config(['session.domain' => null]);
        }

        if (str_ends_with($host, 'citiinfo.com.au')) {
            $sessionDomain = env('SESSION_DOMAIN');

            if (empty($sessionDomain)) {
                $sessionDomain = '.citiinfo.com.au';
            }

            config([
                'session.domain'    => $sessionDomain,
                'session.secure'    => true,
                'session.same_site' => 'lax',
            ]);
        } elseif ($isHttps) {
            config(['session.secure' => true]);
        }

        return $next($request);
    }
}
