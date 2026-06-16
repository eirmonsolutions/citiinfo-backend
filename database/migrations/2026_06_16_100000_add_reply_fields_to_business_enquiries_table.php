<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('business_enquiries', function (Blueprint $table) {
            $table->foreignId('sender_user_id')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
            $table->text('admin_reply')->nullable()->after('message');
            $table->timestamp('replied_at')->nullable()->after('admin_reply');
            $table->foreignId('replied_by')->nullable()->after('replied_at')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('business_enquiries', function (Blueprint $table) {
            $table->dropForeign(['sender_user_id']);
            $table->dropForeign(['replied_by']);
            $table->dropColumn(['sender_user_id', 'admin_reply', 'replied_at', 'replied_by']);
        });
    }
};
