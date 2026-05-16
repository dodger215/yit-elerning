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
        Schema::create('video_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('video_id')->constrained('videos')->cascadeOnDelete();
            $table->string('interaction_type', 20); // 'view', 'like', 'share', 'save', 'report'
            $table->json('interaction_value')->nullable(); // e.g. report reason
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'video_id', 'interaction_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_interactions');
    }
};
