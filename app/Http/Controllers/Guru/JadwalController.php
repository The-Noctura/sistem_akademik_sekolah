<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;

// Controller Jadwal — untuk role GURU
// Menampilkan jadwal mengajar milik guru yang sedang login
class JadwalController extends Controller
{
  public function index()
  {
    $guru = Guru::where('user_id', Auth::id())->firstOrFail();

    $jadwalList = Jadwal::with(['mengajar.mapel', 'mengajar.kelas'])
      ->whereHas('mengajar', function ($query) use ($guru) {
        $query->where('guru_id', $guru->id);
      })
      ->orderByRaw("FIELD(hari, 'senin','selasa','rabu','kamis','jumat','sabtu')")
      ->orderBy('jam_mulai')
      ->get();

    return view('guru.jadwal.index', compact('jadwalList'));
  }
}
