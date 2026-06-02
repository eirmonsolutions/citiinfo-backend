<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return $request->expectsJson()
                ? response()->json(['ok' => false, 'message' => 'Unauthenticated.'], 401)
                : redirect()->route('login');
        }

        $allowed = false;
        foreach ($roles as $role) {
            if ($user->role === $role) {
                $allowed = true;
                break;
            }
            if ($role === 'seo_user' && in_array($user->role, ['site_user', 'blog_user'], true)) {
                $allowed = true;
                break;
            }
            if ($role === 'site_user' && in_array($user->role, ['seo_user', 'blog_user'], true)) {
                $allowed = true;
                break;
            }
        }

        if (!$allowed) {
            if (in_array($user->role, ['seo_user', 'site_user', 'blog_user'], true)) {
                $message = 'You only have access to the SEO blog panel.';

                return $request->expectsJson()
                    ? response()->json(['ok' => false, 'message' => $message], 403)
                    : redirect()->route('blog.dashboard')->with('error', $message);
            }

            return $request->expectsJson()
                ? response()->json(['ok' => false, 'message' => 'Forbidden.'], 403)
                : abort(403);
        }

        return $next($request);
    }
}
