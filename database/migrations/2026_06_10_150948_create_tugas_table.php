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
            $table->foreignId('kelas_id')
                  ->nullable()
                  ->constrained('kelas')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('judul', 200);
            $table->text('deskripsi')->nullable();
            $table->enum('jenis_tugas', ['kelas', 'pribadi'])->default('kelas');
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi'])->default('sedang');
            $table->enum('status', [
                'belum_dikerjakan',
                'sedang_dikerjakan',
                'selesai',
                'terlambat',
            ])->default('belum_dikerjakan');
            $table->unsignedTinyInteger('progres')->default(0);
            $table->dateTime('deadline');
            $table->timestamps();

            $table->index('kelas_id');
            $table->index('deadline');
            $table->index('jenis_tugas');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};