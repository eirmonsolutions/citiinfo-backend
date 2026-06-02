<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->whereIn('role', ['site_user', 'blog_user'])->update(['role' => 'seo_user']);
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'seo_user')->update(['role' => 'site_user']);
    }
};
