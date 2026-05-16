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
        Schema::create('course_lessons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('section_id')->constrained('course_sections')->cascadeOnDelete();
            $table->foreignUuid('video_id')->nullable()->constrained('videos')->nullOnDelete();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('lesson_type', 20); // 'video', 'quiz', 'assignment', 'article', 'live_session'
            $table->unsignedInteger('lesson_order');
            $table->integer('duration_minutes')->nullable();
            $table->boolean('is_preview')->default(false); // Free preview for non-enrolled users

            // Quiz specific
            $table->json('quiz_data')->nullable();

            // Assignment specific
            $table->json('assignment_data')->nullable();

            $table->timestamps();

            $table->unique(['section_id', 'lesson_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_lessons');
    }
};
