<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->where('role', 'blog_user')->update(['role' => 'site_user']);
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'site_user')->update(['role' => 'blog_user']);
    }
};
