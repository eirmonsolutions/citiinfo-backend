<?php

namespace App\Services;

use App\Models\BusinessListing;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Cache;

class AuthProfileService
{
    public const CACHE_TTL_MINUTES = 10080; // 7 days

    public function buildProfile(User $user): array
    {
        $role = $user->role ?? 'user';
        $displayName = $user->name ?? 'User';
        $businessName = null;
        $dashboardUrl = url('/user/dashboard');

        if ($role === 'superadmin') {
            $displayName = 'Super Admin';
            $dashboardUrl = url('/superadmin/dashboard');
        }

        if ($role === 'admin') {
            $businessUserId = $user->business_user_id ?? $user->id;

            $businessName = BusinessListing::where('user_id', $businessUserId)
                ->latest('id')
                ->value('business_name');

            $dashboardUrl = url('/admin/dashboard');
        }

        if (in_array($role, ['seo_user', 'site_user', 'blog_user'], true)) {
            $dashboardUrl = url('/seo-user/dashboard');
        }

        $parts = preg_split('/\s+/', trim($user->name ?? $displayName));

        $initials = strtoupper(
            substr($parts[0] ?? 'U', 0, 1) .
            substr($parts[1] ?? '', 0, 1)
        );

        $wishlistIds = Wishlist::where('user_id', $user->id)
            ->pluck('business_id')
            ->values()
            ->all();

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $role,
            'display_name' => $user->name ?? $displayName,
            'business_name' => $businessName ?? null,
            'initials' => $initials,
            'avatar' => $user->avatar ?? null,
            'dashboard_url' => $dashboardUrl,
            'wishlist_count' => count($wishlistIds),
            'wishlist_ids' => $wishlistIds,
        ];
    }

    public function cacheToken(User $user, string $plainTextToken): array
    {
        $profile = $this->buildProfile($user);
        $tokenHash = hash('sha256', $plainTextToken);

        Cache::put(
            "auth:token:{$tokenHash}",
            $user->id,
            now()->addMinutes(self::CACHE_TTL_MINUTES)
        );

        Cache::put(
            "auth:profile:{$user->id}",
            $profile,
            now()->addMinutes(self::CACHE_TTL_MINUTES)
        );

        return $profile;
    }

    public function getProfileFromCache(int $userId): ?array
    {
        return Cache::get("auth:profile:{$userId}");
    }

    public function refreshProfileCache(User $user): array
    {
        $profile = $this->buildProfile($user);

        Cache::put(
            "auth:profile:{$user->id}",
            $profile,
            now()->addMinutes(self::CACHE_TTL_MINUTES)
        );

        Cache::forget("wishlist_count_{$user->id}");

        return $profile;
    }

    public function clearUserCache(int $userId, ?string $plainTextToken = null): void
    {
        Cache::forget("auth:profile:{$userId}");
        Cache::forget("wishlist_count_{$userId}");

        if ($plainTextToken) {
            $tokenHash = hash('sha256', $plainTextToken);
            Cache::forget("auth:token:{$tokenHash}");
        }
    }
}
