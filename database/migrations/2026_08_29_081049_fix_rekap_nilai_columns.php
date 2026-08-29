<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekap_nilai', function (Blueprint $table) {
            $table->decimal('rata_rata', 5, 2)->nullable()->after('semester');
            $table->dropColumn(['nilai_akhir', 'predikat', 'tahun_ajaran', 'status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('rekap_nilai', function (Blueprint $table) {
            $table->decimal('nilai_akhir', 5, 2)->nullable()->after('semester');
            $table->string('predikat', 10)->nullable()->after('nilai_akhir');
            $table->string('tahun_ajaran', 20)->after('predikat');
            $table->string('status', 20)->nullable()->after('tahun_ajaran');
            $table->timestamp('created_at')->nullable()->after('status');
            $table->dropColumn('rata_rata');
        });
    }
};
