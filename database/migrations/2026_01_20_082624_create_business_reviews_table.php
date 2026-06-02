<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('business_reviews', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('business_id'); // business_listings.id
            $table->unsignedBigInteger('user_id')->nullable(); // logged-in user (optional)

            $table->string('name');
            $table->string('email');
            $table->unsignedTinyInteger('rating'); // 1..5
            $table->text('review');

            $table->boolean('is_approved')->default(true); // abhi direct show, later you can approve system
            $table->timestamps();

            $table->index(['business_id', 'rating']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_reviews');
    }
};
