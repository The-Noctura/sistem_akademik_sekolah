<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    /**
     * Tampilkan daftar nilai siswa yang sedang login.
     */
    public function index()
    {
        // Ambil ID siswa yang sedang login
        $siswaId = Auth::id();

        // Ambil semua nilai milik siswa tersebut, lengkap dengan relasi mata pelajaran
        $nilai = Nilai::with('mataPelajaran')
                      ->where('siswa_id', $siswaId)
                      ->get();

        return view('siswa.nilai.index', compact('nilai'));
    }

    /**
     * Tampilkan detail satu nilai (hanya jika milik siswa yang login).
     */
    public function show($id)
    {
        $siswaId = Auth::id();

        // Cari nilai, pastikan dimiliki oleh siswa yang login
        $nilai = Nilai::with('mataPelajaran')
                      ->where('id', $id)
                      ->where('siswa_id', $siswaId)
                      ->firstOrFail();

        return view('siswa.nilai.show', compact('nilai'));
    }

    /**
     * Tampilkan rekap nilai (opsional).
     * Bisa menampilkan rata-rata atau total nilai per semester.
     */
    public function rekap()
    {
        $siswaId = Auth::id();

        $nilai = Nilai::where('siswa_id', $siswaId)->get();

        // Contoh: rata-rata semua nilai
        $rataRata = $nilai->avg('nilai');

        // Kelompokkan berdasarkan mata pelajaran atau semester (jika ada)
        // Sesuaikan dengan kebutuhan

        return view('siswa.nilai.rekap', compact('nilai', 'rataRata'));
    }
}