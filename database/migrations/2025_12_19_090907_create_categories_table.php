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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->string('icon')->nullable();          // flaticon / font icon
            $table->string('image')->nullable();         // main category image
            $table->string('categoryimage')->nullable(); // icon image (upload)

            $table->boolean('is_active')->default(1);    // active / inactive
            $table->boolean('is_home')->default(0);      // ✅ homepage show (max 6)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
