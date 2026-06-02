<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'slug')) {
                $table->string('slug')->nullable()->after('name')->index();
            }
            $table->index('is_active');
            $table->index('is_home');
        });

        // Populate existing slugs
        $categories = \DB::table('categories')->get();
        foreach ($categories as $cat) {
            $slug = \Illuminate\Support\Str::slug($cat->name);
            \DB::table('categories')->where('id', $cat->id)->update(['slug' => $slug]);
        }

        Schema::table('business_listings', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('status');
            $table->index('is_allowed');
            $table->index('is_featured');
            $table->index(['city', 'status', 'is_allowed']); // Composite for search
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_home']);
            $table->dropColumn('slug');
        });

        Schema::table('business_listings', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['is_allowed']);
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['city', 'status', 'is_allowed']);
        });
    }
};
