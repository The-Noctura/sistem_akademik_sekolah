<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\RekapNilai;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index()
    {
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();

        $nilaiPerMengajar = Nilai::with(['mengajar.mapel', 'mengajar.kelas'])
            ->where('siswa_id', $siswa->id)
            ->get()
            ->groupBy('mengajar_id');

        $rekapMap = RekapNilai::where('siswa_id', $siswa->id)
            ->get()
            ->keyBy('mengajar_id');

        $mengajarData = [];
        foreach ($nilaiPerMengajar as $mengajarId => $nilaiList) {
            $mengajar = $nilaiList->first()->mengajar;
            $rekap = $rekapMap->get($mengajarId);

            if ($rekap && $rekap->rata_rata !== null) {
                $rataRata = $rekap->rata_rata;
                $predikat = $rekap->predikat;
            } else {
                $rataRata = $nilaiList->avg('nilai');
                $predikat = $this->predikatDariRata($rataRata);
            }

            $mengajarData[] = [
                'mengajar' => $mengajar,
                'nilai' => $nilaiList,
                'rata_rata' => round($rataRata, 2),
                'predikat' => $predikat,
            ];
        }

        return view('siswa.nilai.index', compact('mengajarData'));
    }

    private function predikatDariRata($rataRata): string
    {
        if ($rataRata >= 85) {
            return 'A';
        }
        if ($rataRata >= 75) {
            return 'B';
        }
        if ($rataRata >= 65) {
            return 'C';
        }
        if ($rataRata >= 55) {
            return 'D';
        }

        return 'E';
    }
}
