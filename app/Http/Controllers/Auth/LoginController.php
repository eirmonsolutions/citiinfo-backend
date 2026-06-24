<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function showLogin()
    {
        return response()
            ->view('auth.login')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    public function login(Request $request)
    {
        $ip = $request->ip();

        $blockKey = 'login_blocked_ip_' . $ip;
        $attemptKey = 'login_attempts_ip_' . $ip;
        $mailKey = 'login_alert_sent_ip_' . $ip;

        // ✅ Agar IP already blocked hai
        if (Cache::has($blockKey)) {
            return back()
                ->withErrors(['email' => 'Too many login attempts. Please try again after 12 hours.'])
                ->onlyInput('email');
        }

        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $remember = $request->boolean('remember');
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $remember)) {
            // ✅ Successful login par attempts clear
            Cache::forget($attemptKey);
            Cache::forget($mailKey);

            $request->session()->regenerate();

            $user = Auth::user();

            if (!empty($user->is_blocked)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()
                    ->withErrors(['email' => 'Your account is blocked.'])
                    ->onlyInput('email');
            }

            $role = $user->role ?? 'user';

            if ($role === 'superadmin') {
                $redirectTo = route('superadmin.dashboard');
            } elseif ($role === 'admin') {
                $redirectTo = route('admin.dashboard');
            } elseif (in_array($role, ['seo_user', 'site_user', 'blog_user'], true)) {
                $redirectTo = route('blog.dashboard');
            } else {
                $redirectTo = route('user.dashboard');
            }

            $intended = redirect()->intended($redirectTo)->getTargetUrl();

            return redirect()->to($intended ?: $redirectTo);
        }

        // ❌ Failed login attempt count
        $attempts = Cache::get($attemptKey, 0) + 1;

        Cache::put($attemptKey, $attempts, now()->addHours(12));

        // ✅ 5 attempts ke baad IP block
        if ($attempts >= 5) {
            Cache::put($blockKey, true, now()->addHours(12));

            // ✅ Mail sirf ek baar send ho
            if (!Cache::has($mailKey)) {
                Cache::put($mailKey, true, now()->addHours(12));

                Mail::raw(
                    "Login security alert:\n\n" .
                        "Email attempted: {$request->email}\n" .
                        "IP Address: {$ip}\n" .
                        "Failed Attempts: {$attempts}\n" .
                        "Blocked For: 12 hours\n" .
                        "Time: " . now(),
                    function ($message) {
                        $message->to([
                            'info@eirmonsolutions.com.au',
                            'vishaleirmon15896@gmail.com'
                        ])->subject('Security Alert: Login Attempts Blocked');
                    }
                );
            }

            return back()
                ->withErrors(['email' => 'Too many failed attempts. Your IP is blocked for 12 hours.'])
                ->onlyInput('email');
        }

        return back()
            ->withErrors([
                'email' => 'Invalid email or password. Attempt ' . $attempts . ' of 5.'
            ])
            ->onlyInput('email');
    }
}
