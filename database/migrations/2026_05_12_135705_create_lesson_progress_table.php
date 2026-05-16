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
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('course_enrollments')->cascadeOnDelete();
            $table->foreignUuid('lesson_id')->constrained('course_lessons')->cascadeOnDelete();
            $table->string('status', 20)->default('not_started'); // 'not_started', 'in_progress', 'completed'
            $table->decimal('score', 5, 2)->nullable(); // For quizzes/assignments
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('last_accessed_at')->useCurrent();

            $table->unique(['enrollment_id', 'lesson_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_progress');
    }
};
