<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Mengajar;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
  /**
   * Daftar hari yang valid, dipakai bareng di view (dropdown) dan validasi.
   */
  private const HARI_OPTIONS = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];

  public function index()
  {
    $jadwalList = Jadwal::with(['mengajar.guru', 'mengajar.mapel', 'mengajar.kelas'])
      ->orderByRaw("FIELD(hari, 'senin','selasa','rabu','kamis','jumat','sabtu')")
      ->orderBy('jam_mulai')
      ->get();

    return view('admin.jadwal.index', compact('jadwalList'));
  }

  public function create()
  {
    $mengajarList = $this->getMengajarOptions();
    $hariOptions = $this->getHariOptions();

    return view('admin.jadwal.create', compact('mengajarList', 'hariOptions'));
  }

  public function store(Request $request)
  {
    $validated = $this->validateJadwal($request);

    Jadwal::create($validated);

    return redirect()
      ->route('admin.jadwal.index')
      ->with('success', 'Jadwal berhasil ditambahkan.');
  }

  public function edit(Jadwal $jadwal)
  {
    $mengajarList = $this->getMengajarOptions();
    $hariOptions = $this->getHariOptions();

    return view('admin.jadwal.edit', compact('jadwal', 'mengajarList', 'hariOptions'));
  }

  public function update(Request $request, Jadwal $jadwal)
  {
    $validated = $this->validateJadwal($request, $jadwal->id);

    $jadwal->update($validated);

    return redirect()
      ->route('admin.jadwal.index')
      ->with('success', 'Jadwal berhasil diperbarui.');
  }

  public function destroy(Jadwal $jadwal)
  {
    $jadwal->delete();

    return redirect()
      ->route('admin.jadwal.index')
      ->with('success', 'Jadwal berhasil dihapus.');
  }

  /**
   * Validasi request jadwal. $ignoreId dipakai saat update supaya
   * pengecekan bentrok jadwal tidak mentok ke baris jadwal itu sendiri.
   */
  private function validateJadwal(Request $request, ?int $ignoreId = null): array
  {
    $validated = $request->validate([
      'mengajar_id' => ['required', 'exists:mengajar,id'],
      'hari'        => ['required', 'in:' . implode(',', self::HARI_OPTIONS)],
      'jam_mulai'   => ['required', 'date_format:H:i'],
      'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
      'ruangan'     => ['required', 'string', 'max:50'],
    ], [
      'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
    ]);

    // Bentrok ruangan: ruangan sama, hari sama, rentang jam beririsan.
    // Ini validasi tambahan di level backend, TIDAK mengubah skema/unique key.
    $bentrok = Jadwal::where('hari', $validated['hari'])
      ->where('ruangan', $validated['ruangan'])
      ->when($ignoreId, fn($query) => $query->where('id', '!=', $ignoreId))
      ->where('jam_mulai', '<', $validated['jam_selesai'])
      ->where('jam_selesai', '>', $validated['jam_mulai'])
      ->exists();

    if ($bentrok) {
      throw \Illuminate\Validation\ValidationException::withMessages([
        'ruangan' => 'Ruangan sudah dipakai jadwal lain pada hari dan jam yang beririsan.',
      ]);
    }

    return $validated;
  }

  /**
   * Opsi dropdown mengajar_id: gabungan nama guru + mapel + kelas
   * supaya admin tidak memilih berdasarkan id mentah.
   */
  private function getMengajarOptions(): array
  {
    return Mengajar::with(['guru', 'mapel', 'kelas'])
      ->get()
      ->mapWithKeys(function (Mengajar $mengajar) {
        $label = sprintf(
          '%s — %s (%s)',
          $mengajar->guru->nama ?? '-',
          $mengajar->mapel->nama_mapel ?? '-',
          $mengajar->kelas->nama_kelas ?? '-'
        );

        return [$mengajar->id => $label];
      })
      ->toArray();
  }

  private function getHariOptions(): array
  {
    return array_combine(self::HARI_OPTIONS, array_map('ucfirst', self::HARI_OPTIONS));
  }
}
