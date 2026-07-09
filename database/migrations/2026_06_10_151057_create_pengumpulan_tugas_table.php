<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')
                  ->constrained('tugas')
                  ->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('file_path', 500);
            $table->string('file_name', 255);
            $table->unsignedBigInteger('file_size');
            $table->string('mime_type', 100)->nullable();
            $table->text('catatan')->nullable();
            $table->dateTime('submitted_at');
            $table->timestamps();

            $table->unique(['tugas_id', 'mahasiswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};