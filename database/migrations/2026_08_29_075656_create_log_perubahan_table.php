<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_perubahan', function (Blueprint $table) {
            $table->id();
            $table->string('tabel');
            $table->unsignedBigInteger('record_id');
            $table->enum('aksi', ['INSERT', 'UPDATE', 'DELETE']);
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->timestamp('waktu')->useCurrent();
            $table->json('data_lama')->nullable();
            $table->json('data_baru')->nullable();
            $table->index(['tabel', 'record_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_perubahan');
    }
};
