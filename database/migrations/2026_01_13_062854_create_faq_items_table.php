<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faq_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('faq_id');
            $table->string('question');
            $table->text('answer')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('faq_id')->references('id')->on('faqs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faq_items');
    }
};
