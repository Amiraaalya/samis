<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengumpulan_id')
                  ->unique()
                  ->constrained('pengumpulan_tugas')
                  ->cascadeOnDelete();
            $table->foreignId('dosen_id')
                  ->constrained('users')
                  ->restrictOnDelete();
            $table->decimal('nilai', 5, 2);
            $table->text('feedback')->nullable();
            $table->dateTime('dinilai_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};