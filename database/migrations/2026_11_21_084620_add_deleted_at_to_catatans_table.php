<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Dicek dulu, kalau-kalau migrasi teman sudah menambahkannya
        if (!Schema::hasColumn('catatans', 'deleted_at')) {
            Schema::table('catatans', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('catatans', 'deleted_at')) {
            Schema::table('catatans', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};