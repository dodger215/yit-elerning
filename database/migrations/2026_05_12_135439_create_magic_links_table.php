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
        Schema::create('magic_links', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('email', 255);
            $table->uuid('token')->unique();
            $table->string('purpose', 50)->default('login'); // 'login', 'signup', 'email_change'
            $table->boolean('is_used')->default(false);
            $table->timestamp('expires_at');
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['token', 'is_used'], 'idx_magic_token');
            $table->index(['email', 'expires_at'], 'idx_magic_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('magic_links');
    }
};
