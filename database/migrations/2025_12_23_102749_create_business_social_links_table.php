<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('business_social_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');

            $table->string('platform'); // facebook, instagram...
            $table->text('url')->nullable();

            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('business_listings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_social_links');
    }
};
