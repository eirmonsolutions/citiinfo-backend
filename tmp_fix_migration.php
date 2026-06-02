<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

try {
    echo "Adding columns to categories...\n";
    Schema::table('categories', function (Blueprint $table) {
        if (!Schema::hasColumn('categories', 'slug')) {
            $table->string('slug')->nullable()->after('name')->index();
        }
        $table->index('is_active');
        $table->index('is_home');
    });

    echo "Populating slugs...\n";
    DB::table('categories')->whereNull('slug')->orWhere('slug', '')->chunkById(100, function ($categories) {
        foreach ($categories as $cat) {
            $slug = Str::slug($cat->name);
            DB::table('categories')->where('id', $cat->id)->update(['slug' => $slug]);
        }
    });

    echo "Adding indices to business_listings...\n";
    Schema::table('business_listings', function (Blueprint $table) {
        $indices = [
            'category_id', 'status', 'is_allowed', 'is_featured'
        ];
        foreach ($indices as $index) {
            try {
                $table->index($index);
            } catch (\Exception $e) {
                echo "Index $index already exists or failed: " . $e->getMessage() . "\n";
            }
        }
        try {
            $table->index(['city', 'status', 'is_allowed'], 'search_composite_index');
        } catch (\Exception $e) {
            echo "Composite index failed: " . $e->getMessage() . "\n";
        }
    });

    echo "SUCCESS: Migration logic applied manually.\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
