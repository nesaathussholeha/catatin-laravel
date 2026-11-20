<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
<<<<<<< Updated upstream
        // category_id sudah dibuat pada migration create_catatans_table.
=======
        if (Schema::hasColumn('catatans', 'tipe')) {
            Schema::table('catatans', function (Blueprint $table) {
                $table->dropColumn('tipe');
            });
        }

        if (! Schema::hasColumn('catatans', 'category_id')) {
            Schema::table('catatans', function (Blueprint $table) {
                $table->foreignId('category_id')->after('jurusan')->constrained('categories');
            });
        }
>>>>>>> Stashed changes
    }

    public function down(): void
    {
<<<<<<< Updated upstream
        // Tidak perlu menghapus category_id.
=======
        // Sengaja kosong: category_id kini dibuat oleh create_catatans_table.
>>>>>>> Stashed changes
    }
};