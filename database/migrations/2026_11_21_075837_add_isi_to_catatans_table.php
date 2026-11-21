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
            $table->longText('isi')->nullable()->after('deskripsi');                  
            $table->longText('isi_teks')->nullable()->after('isi');                   

            // Sekarang boleh kosong: tautan hanya untuk mode link, deskripsi opsional
            $table->string('tautan', 500)->nullable()->change();
            $table->string('deskripsi', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('catatans', function (Blueprint $table) {
            $table->dropColumn(['tipe_isi', 'isi', 'isi_teks']);
        });
    }
};