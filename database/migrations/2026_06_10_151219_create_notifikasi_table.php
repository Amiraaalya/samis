<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('tugas_id')
                  ->constrained('tugas')
                  ->cascadeOnDelete();
            $table->enum('jenis', ['h7', 'h3', 'h1', 'hari_h', 'terlambat']);
            $table->string('pesan', 500);
            $table->boolean('is_read')->default(false);
            $table->date('tanggal_kirim');
            $table->timestamps();

            $table->unique(
                ['user_id', 'tugas_id', 'jenis', 'tanggal_kirim'],
                'uq_notifikasi'
            );
            $table->index(['user_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};