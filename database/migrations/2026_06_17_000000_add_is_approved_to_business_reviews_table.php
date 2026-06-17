<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('business_reviews', 'is_approved')) {
            Schema::table('business_reviews', function (Blueprint $table) {
                $table->boolean('is_approved')->default(true)->after('review');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('business_reviews', 'is_approved')) {
            Schema::table('business_reviews', function (Blueprint $table) {
                $table->dropColumn('is_approved');
            });
        }
    }
};
