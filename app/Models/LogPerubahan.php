<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPerubahan extends Model
{
    use HasFactory;

    protected $table = 'log_perubahan';

    protected $fillable = [
        'tabel',
        'record_id',
        'aksi',
        'user_id',
        'waktu',
        'data_lama',
        'data_baru',
    ];

    protected $casts = [
        'waktu' => 'datetime',
        'data_lama' => 'json',
        'data_baru' => 'json',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
