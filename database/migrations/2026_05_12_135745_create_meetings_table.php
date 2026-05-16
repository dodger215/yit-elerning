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
        Schema::create('meetings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('meeting_type', 20)->nullable(); // 'public', 'private', 'course_live', 'one_on_one'

            // Scheduling
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->json('recurrence_rule')->nullable();

            // Room
            $table->string('room_id', 255)->unique();
            $table->text('meeting_url')->nullable();
            $table->foreignUuid('host_user_id')->nullable()->constrained('users')->nullOnDelete();

            // Settings
            $table->unsignedInteger('max_participants')->default(100);
            $table->boolean('waiting_room_enabled')->default(true);
            $table->boolean('recording_enabled')->default(false);
            $table->boolean('chat_enabled')->default(true);
            $table->boolean('screen_sharing_enabled')->default(true);
            $table->string('access_code', 20)->nullable();

            // Assets
            $table->text('recording_url')->nullable();
            $table->text('transcript_url')->nullable();

            // Status
            $table->string('status', 20)->default('scheduled'); // 'scheduled', 'active', 'ended', 'cancelled'

            // Course association
            $table->foreignUuid('course_id')->nullable()->constrained('courses')->nullOnDelete();
            $table->foreignUuid('lesson_id')->nullable()->constrained('course_lessons')->nullOnDelete();

            $table->timestamps();

            $table->index(['start_time', 'status'], 'idx_meeting_time');
            $table->index('host_user_id', 'idx_meeting_host');
            $table->index('room_id', 'idx_meeting_room');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
