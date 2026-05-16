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
        Schema::create('meeting_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('meeting_id')->constrained('meetings')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('role', 20)->default('participant'); // 'host', 'co_host', 'participant', 'speaker'
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->integer('duration_seconds')->nullable();
            $table->boolean('is_present')->default(true);
            $table->boolean('raised_hand')->default(false);

            // In-meeting permissions
            $table->boolean('can_share_screen')->default(false);
            $table->boolean('can_record')->default(false);
            $table->boolean('can_mute_others')->default(false);

            $table->timestamp('created_at')->useCurrent();

            $table->unique(['meeting_id', 'user_id', 'joined_at']);
            $table->index(['meeting_id', 'user_id'], 'idx_meeting_participants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_participants');
    }
};
