<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->foreignId('mengajar_id')->constrained('mengajar')->after('siswa_id');
            $table->enum('jenis', ['tugas', 'uts', 'uas'])->after('mengajar_id');
            $table->timestamp('tanggal_input')->after('jenis');
            $table->foreignId('diinput_oleh')->constrained('users')->after('tanggal_input');
            $table->unique(['siswa_id', 'mengajar_id', 'jenis']);
        });

        Schema::table('nilai', function (Blueprint $table) {
            $table->dropForeign(['mata_pelajaran_id']);
            $table->dropColumn('mata_pelajaran_id');
            $table->dropColumn('predikat');
        });
    }

    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->foreignId('mata_pelajaran_id')->constrained('mapel')->after('siswa_id');
            $table->string('predikat', 10)->nullable()->after('nilai');
            $table->dropUnique(['siswa_id', 'mengajar_id', 'jenis']);
            $table->dropForeign(['mengajar_id']);
            $table->dropColumn(['mengajar_id', 'jenis', 'tanggal_input', 'diinput_oleh']);
        });
    }
};
