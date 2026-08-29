<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\RekapAbsensi;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index()
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();

        $rekapPerMengajar = RekapAbsensi::with(['mengajar.mapel', 'mengajar.kelas'])
            ->where('siswa_id', $siswa->id)
            ->get()
            ->groupBy('mengajar_id');

        $mengajarData = [];
        foreach ($rekapPerMengajar as $mengajarId => $rekapList) {
            $rekap = $rekapList->first();

            $total = $rekap->total_hadir + $rekap->total_izin + $rekap->total_sakit + $rekap->total_alpa;
            $persentase = $rekap->persentase_hadir ?? ($total > 0 ? round(($rekap->total_hadir / $total) * 100, 2) : 0);

            $mengajarData[] = [
                'mengajar' => $rekap->mengajar,
                'rekap' => $rekap,
                'persentase' => $persentase,
            ];
        }

        return view('siswa.absensi.index', compact('mengajarData'));
    }
}
