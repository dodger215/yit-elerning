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
        Schema::create('meeting_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('meeting_id')->constrained('meetings')->cascadeOnDelete();
            $table->foreignUuid('invited_user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('invited_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('email', 255)->nullable(); // For external invites
            $table->uuid('invitation_token')->unique();
            $table->string('status', 20)->default('pending'); // 'pending', 'accepted', 'declined', 'expired'
            $table->timestamp('response_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('invitation_token', 'idx_invitation_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_invitations');
    }
};
