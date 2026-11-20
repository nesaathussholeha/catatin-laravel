<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // category_id sudah dibuat pada migration create_catatans_table.
    }

    public function down(): void
    {
        // Tidak perlu menghapus category_id.
    }
};