<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MapelController extends Controller
{
  public function index()
  {
    $mapelList = Mapel::latest()->get();

    return view('admin.mapel.index', compact('mapelList'));
  }

  public function create()
  {
    return view('admin.mapel.create');
  }

  public function store(Request $request)
  {
    $validated = $request->validate([
      'nama_mapel' => ['required', 'string', 'max:255'],
      'kode_mapel' => ['required', 'string', 'max:255', 'unique:mapel,kode_mapel'],
    ]);

    Mapel::create($validated);

    return Redirect::route('admin.mapel.index')->with('success', 'Mapel berhasil ditambahkan.');
  }

  public function edit(Mapel $mapel)
  {
    return view('admin.mapel.edit', compact('mapel'));
  }

  public function update(Request $request, Mapel $mapel)
  {
    $validated = $request->validate([
      'nama_mapel' => ['required', 'string', 'max:255'],
      'kode_mapel' => ['required', 'string', 'max:255', 'unique:mapel,kode_mapel,' . $mapel->id],
    ]);

    $mapel->update($validated);

    return Redirect::route('admin.mapel.index')->with('success', 'Mapel berhasil diperbarui.');
  }

  public function destroy(Mapel $mapel)
  {
    $mapel->delete();

    return Redirect::route('admin.mapel.index')->with('success', 'Mapel berhasil dihapus.');
  }
}
