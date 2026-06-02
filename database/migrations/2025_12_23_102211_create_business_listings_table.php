<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('business_listings', function (Blueprint $table) {
            $table->id();

            $table->string('business_name');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('category')->nullable();

            $table->string('slug')->unique();

            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();

            $table->text('address')->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            $table->longText('description')->nullable();

            $table->string('logo')->nullable();

            $table->string('listing_type')->nullable();  // free/premium
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('pending'); // pending/approved/rejected

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedBigInteger('clicks_count')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_listings');
    }
};
