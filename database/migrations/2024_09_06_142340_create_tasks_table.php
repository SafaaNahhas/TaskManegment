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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            $table->timestamp('due_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed','overdue'])->default('pending');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->timestamp('created_on')->nullable();
            $table->timestamp('updated_on')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
