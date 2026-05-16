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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('resource', 100)->nullable(); // 'video', 'course', 'meeting', 'user'
            $table->string('action', 50)->nullable();    // 'create', 'read', 'update', 'delete', 'manage', 'upload'
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['resource', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
