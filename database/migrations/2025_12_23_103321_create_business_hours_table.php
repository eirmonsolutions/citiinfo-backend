<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('business_hours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');

            $table->string('day_of_week'); // monday...
            $table->boolean('is_closed')->default(false);

            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();

            $table->time('break_start')->nullable(); // lunch_start
            $table->time('break_end')->nullable();   // lunch_end

            $table->timestamps();

            $table->foreign('business_id')->references('id')->on('business_listings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_hours');
    }
};
