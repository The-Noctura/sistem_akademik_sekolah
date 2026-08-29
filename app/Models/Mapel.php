<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    // Specify custom table name if it's not the default plural ('mapels')
    protected $table = 'mapel';

    // Mass-assignable attributes
    protected $fillable = [
        'nama_mapel',
        'kode_mapel',
    ];

    
}