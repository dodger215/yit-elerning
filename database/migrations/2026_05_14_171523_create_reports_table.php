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
        Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->string('type'); // 'platform', 'instructor', 'content', 'student'
            $table->json('filters')->nullable();
            $table->json('data')->nullable(); // Cached results
            $table->foreignUuid('generated_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->index(['type', 'generated_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
