<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('business_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');

            $table->string('contact_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->string('website')->nullable();

            $table->boolean('is_primary')->default(true);

            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('business_listings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_contacts');
    }
};
