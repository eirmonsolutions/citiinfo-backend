<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('listing_id')->index();
            $table->string('listing_name')->nullable();

            $table->string('title');
            $table->string('code')->index();              // e.g. SAVE20
            $table->decimal('discount_value', 10, 2);     // e.g. 20.00

            $table->date('start_date');
            $table->date('end_date');

            $table->text('details');

            $table->string('featured_image')->nullable(); // path: coupons/xxx.jpg
            $table->boolean('is_active')->default(1);

            $table->timestamps();

            // optional FK (agar business_listings table hai)
            $table->foreign('listing_id')->references('id')->on('business_listings')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
