<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'guru';

    /**
     * Kolom yang boleh diisi massal.
     */
    protected $fillable = [
        'user_id',      // foreign key ke users
        'nip',
        'nama',
        'alamat',
        'no_hp',
        // tambahkan kolom lain sesuai kebutuhan
    ];

    /**
     * Relasi ke User (setiap guru adalah user).
     * Foreign key: user_id
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Jadwal (guru mengajar banyak jadwal).
     * Foreign key di tabel jadwal: guru_id
     */
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'guru_id');
    }

    /**
     * Relasi ke Nilai (guru memberi nilai melalui jadwal).
     * Tidak langsung, tapi bisa melalui jadwal atau langsung jika ada foreign key guru_id di nilai.
     * Jika tabel nilai memiliki kolom guru_id, tambahkan relasi ini.
     */
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'guru_id');
    }

    public function mapel()
    {
        return $this->hasManyThrough(Mapel::class, Jadwal::class, 'jadwal', 'guru_id', 'mata_pelajaran_id');
    }
}
