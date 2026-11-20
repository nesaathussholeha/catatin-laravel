
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

            // Relasi dengan pengguna yang membuat tugas
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Informasi tugas
            $table->string('title', 150);
            $table->text('description')->nullable();

            // Deadline tugas
            $table->date('deadline')->nullable();

            // Prioritas tugas
            $table->enum('priority', [
                'low',
                'medium',
                'high'
            ])->default('medium');

            // Status pengerjaan
            $table->enum('status', [
                'pending',
                'completed'
            ])->default('pending');

            $table->timestamps();

            // Index untuk pencarian tugas pengguna
            $table->index(['user_id', 'status']);
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
