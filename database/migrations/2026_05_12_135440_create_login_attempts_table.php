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
        Schema::create('login_attempts', function (Blueprint $table) {
            $table->id();
            $table->string('email', 255)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('attempt_type', 50)->nullable(); // 'otp', 'magic_link', 'oauth'
            $table->boolean('is_successful')->default(false);
            $table->text('failure_reason')->nullable();
            $table->timestamp('attempted_at')->useCurrent();

            $table->index(['email', 'ip_address', 'attempted_at'], 'idx_login_attempts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_attempts');
    }
};
