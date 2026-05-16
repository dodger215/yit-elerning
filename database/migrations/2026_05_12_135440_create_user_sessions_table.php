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
        Schema::create('user_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->uuid('refresh_token')->unique();
            $table->string('access_token_jti', 255)->nullable(); // JWT ID for invalidation
            $table->string('device_name', 255)->nullable();
            $table->string('device_type', 50)->nullable(); // 'mobile', 'desktop', 'tablet'
            $table->string('browser_name', 100)->nullable();
            $table->string('os_name', 100)->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('location_city', 100)->nullable();
            $table->string('location_country', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_activity_at')->useCurrent();
            $table->timestamp('expires_at');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'is_active'], 'idx_user_active_sessions');
            $table->index(['refresh_token', 'is_active'], 'idx_refresh_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_sessions');
    }
};
