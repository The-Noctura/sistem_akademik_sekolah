<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Jadwal;
use Illuminate\Support\Facades\Auth;

// Controller Jadwal — untuk role SISWA
// Menampilkan jadwal pelajaran berdasarkan kelas siswa yang sedang login
class JadwalController extends Controller
{
  public function index()
  {
    $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();

    $jadwalList = Jadwal::with(['mengajar.mapel', 'mengajar.guru'])
      ->whereHas('mengajar', function ($query) use ($siswa) {
        $query->where('kelas_id', $siswa->kelas_id);
      })
      ->orderByRaw("FIELD(hari, 'senin','selasa','rabu','kamis','jumat','sabtu')")
      ->orderBy('jam_mulai')
      ->get();

    return view('siswa.jadwal.index', compact('jadwalList'));
  }
}
