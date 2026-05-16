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
        Schema::create('otp_verifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email', 255)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('otp_code', 10);
            $table->string('purpose', 50); // 'login', 'signup', 'passwordless', 'email_verification', 'phone_verification'
            $table->boolean('is_used')->default(false);
            $table->integer('attempts')->default(0);
            $table->timestamp('expires_at');
            $table->string('delivery_method', 20); // 'email', 'sms', 'both'
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['email', 'phone_number', 'otp_code', 'purpose', 'is_used'], 'idx_otp_lookup');
            $table->index('expires_at', 'idx_otp_expiry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_verifications');
    }
};
