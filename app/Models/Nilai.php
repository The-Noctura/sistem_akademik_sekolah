<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Jadwal;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai';

    protected $fillable = [
        'siswa_id',          // foreign key ke users.id
        'mata_pelajaran_id', // foreign key ke jadwal.id
        'nilai',
        'predikat',
    ];

    protected $casts = [
        'nilai' => 'integer',
    ];

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(Jadwal::class, 'mata_pelajaran_id');
    }
}