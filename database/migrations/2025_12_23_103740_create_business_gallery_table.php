<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('business_gallery', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');

            $table->string('image_path');
            $table->string('caption')->nullable();
            $table->string('alt_text')->nullable();

            $table->boolean('is_cover')->default(false);
            $table->integer('sort_order')->default(0);

            $table->timestamp('uploaded_at')->nullable();

            $table->foreign('business_id')->references('id')->on('business_listings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_gallery');
    }
};
