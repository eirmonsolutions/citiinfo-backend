<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('business_enquiries', function (Blueprint $table) {
            $table->unsignedTinyInteger('rating')->nullable()->after('message');
            $table->foreignId('business_review_id')->nullable()->after('rating')
                ->constrained('business_reviews')->nullOnDelete();
        });

        // Sync existing star reviews into messages so panels show history
        $reviews = \App\Models\BusinessReview::with('user')->get();
        $service = app(\App\Services\BusinessEnquiryService::class);

        foreach ($reviews as $review) {
            $service->syncFromReview($review, $review->user);
        }
    }

    public function down(): void
    {
        Schema::table('business_enquiries', function (Blueprint $table) {
            $table->dropForeign(['business_review_id']);
            $table->dropColumn(['rating', 'business_review_id']);
        });
    }
};
