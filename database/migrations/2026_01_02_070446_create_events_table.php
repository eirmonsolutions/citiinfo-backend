<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // who created event (admin user)
            $table->unsignedBigInteger('user_id')->index();

            // organizer listing
            $table->unsignedBigInteger('listing_id')->index();
            $table->string('listing_name')->nullable(); // optional (same like announcement)

            $table->string('title');

            $table->string('location'); // google map address input

            // date + time (screenshot me date + time alag fields)
            $table->date('start_date');
            $table->time('start_time')->nullable();

            $table->date('end_date');
            $table->time('end_time')->nullable();

            $table->text('description');

            // ticket platform + url
            $table->string('ticket_platform')->nullable();  // e.g. Facebook
            $table->string('ticket_url')->nullable();

            // featured image
            $table->string('featured_image')->nullable();

            // toggle status
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // foreign keys (agar aapki tables ka naam different ho to adjust karna)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('listing_id')->references('id')->on('business_listings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
