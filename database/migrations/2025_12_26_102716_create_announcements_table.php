<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();

            // Business Listing relation
            $table->unsignedBigInteger('listing_id');
            $table->string('listing_name');

            // Announcement fields
            $table->string('icon')->nullable();          // lucide icon name (e.g. "megaphone")
            $table->string('title');
            $table->text('description')->nullable();

            $table->string('button_text')->nullable();
            $table->string('button_link')->nullable();

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->boolean('is_active')->default(1);

            $table->timestamps();

            // FK (change table name if yours differs)
            $table->foreign('listing_id')->references('id')->on('business_listings')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
