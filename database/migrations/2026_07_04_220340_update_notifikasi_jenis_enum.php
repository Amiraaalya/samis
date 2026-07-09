<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE notifikasi MODIFY COLUMN jenis ENUM('h7','h3','h1','hari_h','terlambat','selesai') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE notifikasi MODIFY COLUMN jenis ENUM('h7','h3','h1','hari_h','terlambat') NOT NULL");
    }
};