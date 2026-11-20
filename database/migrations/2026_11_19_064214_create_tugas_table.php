
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('judul', 150);
            $table->text('deskripsi')->nullable();
            $table->date('deadline')->nullable();

            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi'])
                ->default('sedang');

            $table->enum('status', ['belum_selesai', 'selesai'])
                ->default('belum_selesai');

            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
