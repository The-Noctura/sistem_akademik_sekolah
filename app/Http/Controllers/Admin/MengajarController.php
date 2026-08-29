<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Mengajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MengajarController extends Controller
{
    public function index()
    {
        $mengajarList = Mengajar::with(['guru', 'mapel', 'kelas'])->latest()->get();

        return view('admin.mengajar.index', compact('mengajarList'));
    }

    public function create()
    {
        $guruList = Guru::orderBy('nama')->get();
        $mapelList = Mapel::orderBy('nama_mapel')->get();
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('admin.mengajar.create', compact('guruList', 'mapelList', 'kelasList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guru_id' => ['required', 'exists:guru,id'],
            'mapel_id' => ['required', 'exists:mapel,id'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'tahun_ajaran' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'string', 'max:255'],
        ]);

        Mengajar::create($validated);

        return Redirect::route('admin.mengajar.index')->with('success', 'Data mengajar berhasil ditambahkan.');
    }

    public function edit(Mengajar $mengajar)
    {
        $guruList = Guru::orderBy('nama')->get();
        $mapelList = Mapel::orderBy('nama_mapel')->get();
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('admin.mengajar.edit', compact('mengajar', 'guruList', 'mapelList', 'kelasList'));
    }

    public function update(Request $request, Mengajar $mengajar)
    {
        $validated = $request->validate([
            'guru_id' => ['required', 'exists:guru,id'],
            'mapel_id' => ['required', 'exists:mapel,id'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'tahun_ajaran' => ['required', 'string', 'max:255'],
            'semester' => ['required', 'string', 'max:255'],
        ]);

        $mengajar->update($validated);

        return Redirect::route('admin.mengajar.index')->with('success', 'Data mengajar berhasil diperbarui.');
    }

    public function destroy(Mengajar $mengajar)
    {
        $mengajar->delete();

        return Redirect::route('admin.mengajar.index')->with('success', 'Data mengajar berhasil dihapus.');
    }
}
