<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapNilai extends Model
{
    use HasFactory;

    protected $table = 'rekap_nilai';

    protected $fillable = [
        'siswa_id',
        'mengajar_id',
        'semester',
        'rata_rata',
        'updated_at',
    ];

    protected $casts = [
        'rata_rata' => 'decimal:2',
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
