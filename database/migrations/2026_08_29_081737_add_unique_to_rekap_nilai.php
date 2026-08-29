<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekap_nilai', function (Blueprint $table) {
            $table->unique(['siswa_id', 'mengajar_id', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::table('rekap_nilai', function (Blueprint $table) {
            $table->dropUnique(['siswa_id', 'mengajar_id', 'semester']);
        });
    }
};
