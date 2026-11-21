<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('note_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catatan_id')->constrained('catatans')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('judul', 100);
            $table->string('jenis', 50); // 'File Rusak', 'Salah Mata Kuliah', 'Spam'
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
            $table->timestamps();

            $table->unique(['user_id', 'catatan_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('note_reports');
    }
};