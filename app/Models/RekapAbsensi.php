<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapAbsensi extends Model
{
    use HasFactory;

    protected $table = 'rekap_absensi';

    protected $fillable = [
        'siswa_id',
        'mengajar_id',
        'semester',
        'total_hadir',
        'total_izin',
        'total_sakit',
        'total_alpa',
        'persentase_hadir',
        'updated_at',
    ];

    protected $casts = [
        'persentase_hadir' => 'decimal:2',
        'updated_at' => 'datetime',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function mengajar()
    {
        return $this->belongsTo(Mengajar::class, 'mengajar_id');
    }
}
