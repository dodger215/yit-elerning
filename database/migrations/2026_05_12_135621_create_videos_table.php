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
        Schema::create('videos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->text('video_url'); // CDN URL or storage path
            $table->text('thumbnail_url')->nullable();
            $table->integer('duration')->nullable(); // seconds
            $table->bigInteger('file_size')->nullable(); // bytes
            $table->string('video_type', 20)->nullable(); // 'short', 'long'
            $table->string('resolution', 20)->nullable(); // '720p', '1080p', '4K'
            $table->string('status', 20)->default('pending'); // 'pending', 'approved', 'rejected', 'published'

            // Metrics
            $table->unsignedBigInteger('view_count')->default(0);
            $table->unsignedBigInteger('like_count')->default(0);
            $table->unsignedBigInteger('comment_count')->default(0);
            $table->unsignedBigInteger('share_count')->default(0);

            // Relations
            $table->foreignUuid('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('video_categories')->nullOnDelete();

            // Short video (reels) fields
            $table->string('music_track', 255)->nullable();
            $table->json('effect_filters')->nullable();

            // Long video chapters
            $table->json('chapters')->nullable(); // [{timestamp: 120, title: "Introduction"}]

            $table->timestamps();
            $table->timestamp('published_at')->nullable();

            $table->index(['video_type', 'status'], 'idx_video_type_status');
            $table->index('uploaded_by', 'idx_uploaded_by');
            $table->index('created_at', 'idx_videos_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
