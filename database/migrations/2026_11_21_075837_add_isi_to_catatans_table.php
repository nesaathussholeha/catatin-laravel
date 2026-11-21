<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('catatans', function (Blueprint $table) {
            $table->string('tipe_isi', 10)->default('tautan')->after('category_id');

            // Tautan dan deskripsi boleh kosong
            $table->string('tautan', 500)->nullable()->change();
            $table->string('deskripsi', 255)->nullable()->change();

            // Mengubah default status yang sudah ada
            $table->string('status')->default('Terbit')->change();
        });
    }

    public function down(): void
    {
        Schema::table('catatans', function (Blueprint $table) {
            $table->dropColumn(['tipe_isi',  'isi_teks']);
        });
    }
};
