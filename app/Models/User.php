<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_auto_created',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_blocked' => 'boolean',
        ];
    }

    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class);
    }

    public function isSeoUser(): bool
    {
        return in_array($this->role ?? '', ['seo_user', 'site_user', 'blog_user'], true);
    }

    /** @deprecated Use isSeoUser() */
    public function isSiteUser(): bool
    {
        return $this->isSeoUser();
    }

    /** @deprecated Use isSeoUser() */
    public function isBlogUser(): bool
    {
        return $this->isSeoUser();
    }

    public function canManageBlogs(): bool
    {
        return $this->isSeoUser() || ($this->role ?? '') === 'superadmin';
    }

    public function isListingUser(): bool
    {
        return in_array($this->role ?? '', ['user', 'admin'], true);
    }
}
