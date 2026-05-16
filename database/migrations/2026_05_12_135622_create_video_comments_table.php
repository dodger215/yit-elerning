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
        Schema::create('video_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('video_id')->constrained('videos')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('parent_comment_id')->nullable()->constrained('video_comments')->nullOnDelete();
            $table->text('content');
            $table->integer('like_count')->default(0);
            $table->boolean('is_pinned')->default(false);
            $table->timestamps();

            $table->index(['video_id', 'created_at'], 'idx_video_comments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_comments');
    }
};
