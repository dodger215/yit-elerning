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
        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('short_description', 500)->nullable();
            $table->text('cover_image_url')->nullable();
            $table->foreignUuid('trailer_video_id')->nullable()->constrained('videos')->nullOnDelete();

            // Pricing
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->boolean('is_free')->default(false);
            $table->string('currency', 3)->default('USD');

            // Course details
            $table->string('level', 20)->nullable(); // 'beginner', 'intermediate', 'advanced', 'all_levels'
            $table->string('language', 10)->default('en');
            $table->integer('duration_hours')->nullable();

            // Enrollment metrics
            $table->unsignedInteger('total_students')->default(0);
            $table->unsignedInteger('total_reviews')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);

            // Status
            $table->string('status', 20)->default('draft'); // 'draft', 'published', 'archived', 'under_review'

            // Relations
            $table->foreignUuid('instructor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('video_categories')->nullOnDelete();

            $table->timestamps();
            $table->timestamp('published_at')->nullable();

            $table->index('instructor_id', 'idx_course_instructor');
            $table->index('status', 'idx_course_status');
            $table->index('average_rating', 'idx_course_rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
