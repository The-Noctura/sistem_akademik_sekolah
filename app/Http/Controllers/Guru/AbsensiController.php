<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Mengajar;
use App\Models\Siswa;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function index()
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();

        $mengajarList = Mengajar::with(['kelas', 'mapel'])
            ->where('guru_id', $guru->id)
            ->get();

        return view('guru.absensi.index', compact('mengajarList'));
    }

    public function form($mengajarId)
    {
        $mengajar = Mengajar::with(['kelas', 'mapel', 'guru'])->findOrFail($mengajarId);

        $this->authorizeMengajar($mengajar);

        $siswaList = Siswa::where('kelas_id', $mengajar->kelas_id)->get();

        $tanggal = request()->query('tanggal', now()->format('Y-m-d'));

        return view('guru.absensi.form', compact('mengajar', 'siswaList', 'tanggal'));
    }

    public function store(Request $request, $mengajarId)
    {
        $mengajar = Mengajar::with(['kelas', 'mapel'])->findOrFail($mengajarId);

        $this->authorizeMengajar($mengajar);

        $validated = $request->validate([
            'tanggal' => ['required', 'date'],
            'status' => ['required', 'array'],
            'status.*' => ['required', 'in:hadir,izin,sakit,alpa'],
        ]);

        $siswaIds = array_keys($validated['status']);

        $validSiswa = Siswa::whereIn('id', $siswaIds)
            ->where('kelas_id', $mengajar->kelas_id)
            ->pluck('id')
            ->toArray();

        $invalidSiswa = array_diff($siswaIds, $validSiswa);
        if (! empty($invalidSiswa)) {
            return back()->withErrors([
                'status' => 'Beberapa siswa tidak terdaftar di kelas ini.',
            ])->withInput();
        }

        try {
            DB::beginTransaction();

            foreach ($validated['status'] as $siswaId => $status) {
                Absensi::create([
                    'siswa_id' => $siswaId,
                    'mengajar_id' => $mengajarId,
                    'tanggal' => $validated['tanggal'],
                    'status' => $status,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('guru.absensi.form', [$mengajarId, 'tanggal' => $validated['tanggal']])
                ->with('success', 'Absensi berhasil disimpan.');
        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->getCode() === '23000') {
                return back()->withErrors([
                    'error' => 'Absensi untuk tanggal ini sudah ada. Gunakan form yang sama untuk memperbarui.',
                ])->withInput();
            }

            return back()->withErrors([
                'error' => 'Gagal menyimpan absensi: '.$e->getMessage(),
            ])->withInput();
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors([
                'error' => 'Gagal menyimpan absensi: '.$e->getMessage(),
            ])->withInput();
        }
    }

    private function authorizeMengajar($mengajar)
    {
        $guru = Guru::where('user_id', Auth::id())->firstOrFail();
        if ($mengajar->guru_id !== $guru->id) {
            abort(403, 'Anda tidak mengajar kelas ini.');
        }
    }
}
