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
    'siswa_id',
    'mata_pelajaran_id',
    'nilai',
    'predikat',
  ];

  protected $casts = [
    'nilai' => 'integer',
  ];

  public function siswa()
  {
    return $this->belongsTo(Siswa::class, 'siswa_id');
  }

  public function mataPelajaran()
  {
    return $this->belongsTo(Mapel::class, 'mata_pelajaran_id');
  }
}
