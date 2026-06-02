<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('business_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');

            $table->string('name')->nullable();
            $table->text('description')->nullable();

            $table->decimal('price', 12, 2)->nullable();
            $table->string('currency', 10)->nullable(); // USD/AUD/INR

            $table->integer('duration_minutes')->nullable();
            $table->boolean('is_popular')->default(false);
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('business_listings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_services');
    }
};
