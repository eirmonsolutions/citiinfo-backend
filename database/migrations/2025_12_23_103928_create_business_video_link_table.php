<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('business_video_link', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');

            $table->text('video_link_url')->nullable();
            $table->longText('embed_code')->nullable();
            $table->string('provider')->nullable(); // youtube/vimeo/other

            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('business_listings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_video_link');
    }
};
