<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\User;
use App\Models\Jadwal;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    /**
     * Tampilkan daftar semua nilai.
     */
    public function index()
    {
        $nilai = Nilai::with(['siswa', 'mataPelajaran'])->get();
        return view('nilai.index', compact('nilai'));
    }

    /**
     * Tampilkan form tambah nilai.
     */
    public function create()
    {
        // Ambil semua user yang berperan sebagai siswa
        // Jika tidak ada kolom role, gunakan User::all()
        $siswa = User::where('role', 'siswa')->get();
        $mapel = Jadwal::all();
        return view('nilai.create', compact('siswa', 'mapel'));
    }

    /**
     * Simpan nilai baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'siswa_id'          => 'required|exists:users,id',
            'mata_pelajaran_id' => 'required|exists:jadwal,id',
            'nilai'             => 'required|numeric|min:0|max:100',
            'predikat'          => 'nullable|string|max:10',
        ]);

        // Cegah duplikasi nilai untuk siswa dan mapel yang sama
        $exists = Nilai::where('siswa_id', $validated['siswa_id'])
                       ->where('mata_pelajaran_id', $validated['mata_pelajaran_id'])
                       ->exists();

        if ($exists) {
            return back()->withErrors(['siswa_id' => 'Nilai untuk siswa dan mata pelajaran ini sudah ada.'])->withInput();
        }

        try {
            Nilai::create($validated);
            return redirect()->route('nilai.index')
                             ->with('success', 'Nilai berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan nilai: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Tampilkan detail satu nilai.
     */
    public function show($id)
    {
        $nilai = Nilai::with(['siswa', 'mataPelajaran'])->findOrFail($id);
        return view('nilai.show', compact('nilai'));
    }

    /**
     * Tampilkan form edit nilai.
     */
    public function edit($id)
    {
        $nilai = Nilai::findOrFail($id);
        $siswa = User::where('role', 'siswa')->get();
        $mapel = Jadwal::all();
        return view('nilai.edit', compact('nilai', 'siswa', 'mapel'));
    }

    /**
     * Perbarui nilai yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'siswa_id'          => 'required|exists:users,id',
            'mata_pelajaran_id' => 'required|exists:jadwal,id',
            'nilai'             => 'required|numeric|min:0|max:100',
            'predikat'          => 'nullable|string|max:10',
        ]);

        // Cegah duplikasi kecuali untuk data yang sedang diedit
        $exists = Nilai::where('siswa_id', $validated['siswa_id'])
                       ->where('mata_pelajaran_id', $validated['mata_pelajaran_id'])
                       ->where('id', '!=', $id)
                       ->exists();

        if ($exists) {
            return back()->withErrors(['siswa_id' => 'Nilai untuk siswa dan mata pelajaran ini sudah ada.'])->withInput();
        }

        try {
            $nilai = Nilai::findOrFail($id);
            $nilai->update($validated);
            return redirect()->route('nilai.index')
                             ->with('success', 'Nilai berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui nilai: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Hapus nilai.
     */
    public function destroy($id)
    {
        try {
            $nilai = Nilai::findOrFail($id);
            $nilai->delete();
            return redirect()->route('nilai.index')
                             ->with('success', 'Nilai berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus nilai: ' . $e->getMessage());
        }
    }
}