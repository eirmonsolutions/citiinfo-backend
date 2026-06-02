<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_blocked')) {
                $table->boolean('is_blocked')->default(0)->after('password');
            }

            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes(); // deleted_at
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_blocked')) {
                $table->dropColumn('is_blocked');
            }

            if (Schema::hasColumn('users', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
