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
        Schema::create('meeting_poll_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained('meeting_polls')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('selected_option')->nullable(); // Index of selected option
            $table->timestamp('responded_at')->useCurrent();

            $table->unique(['poll_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_poll_responses');
    }
};
