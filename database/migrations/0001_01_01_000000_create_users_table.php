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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email', 255)->unique();
            $table->string('username', 100)->unique();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->text('avatar_url')->nullable();
            $table->text('bio')->nullable();
            $table->string('timezone', 50)->default('UTC');
            $table->timestamp('last_active_at')->nullable();
            $table->boolean('email_verified')->default(false);
            $table->boolean('is_active')->default(true);

            // OAuth specific fields
            $table->string('oauth_provider', 50)->nullable();
            $table->string('oauth_provider_id', 255)->nullable();
            $table->text('oauth_access_token')->nullable();
            $table->text('oauth_refresh_token')->nullable();
            $table->timestamp('oauth_token_expires_at')->nullable();

            // Phone for OTP
            $table->string('phone_number', 20)->unique()->nullable();
            $table->boolean('phone_verified')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['oauth_provider', 'oauth_provider_id'], 'idx_oauth_provider');
            $table->index(['email', 'email_verified'], 'idx_email_verification');
            $table->index(['phone_number', 'phone_verified'], 'idx_phone_verification');
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
