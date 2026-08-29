<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class KelasController extends Controller
{
    public function index()
    {
        $kelasList = Kelas::with('waliKelas')->latest()->get();

        return view('admin.kelas.index', compact('kelasList'));
    }

    public function create()
    {
        $guruList = Guru::orderBy('nama')->get();

        return view('admin.kelas.create', compact('guruList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255'],
            'tingkat' => ['required', 'string', 'max:255'],
            'wali_kelas_id' => ['nullable', 'exists:guru,id'],
            'tahun_ajaran' => ['required', 'string', 'max:255'],
        ]);

        Kelas::create($validated);

        return Redirect::route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        $guruList = Guru::orderBy('nama')->get();

        return view('admin.kelas.edit', compact('kelas', 'guruList'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:255'],
            'tingkat' => ['required', 'string', 'max:255'],
            'wali_kelas_id' => ['nullable', 'exists:guru,id'],
            'tahun_ajaran' => ['required', 'string', 'max:255'],
        ]);

        $kelas->update($validated);

        return Redirect::route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        return Redirect::route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
