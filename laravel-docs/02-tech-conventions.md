# 02 — Tech Conventions

**Tujuan file ini:** supaya kode yang digenerate AI oleh 4 orang berbeda punya struktur, penamaan, dan gaya yang sama — bisa saling baca kerjaan orang lain tanpa bingung.

Saat prompting AI, tempel bagian relevan dari file ini ke prompt (atau bilang "ikuti konvensi di 02-tech-conventions.md").

---

## Struktur Folder

Pakai struktur default Laravel, dengan penambahan berikut:

```
app/
  Http/
    Controllers/
      Admin/          → controller khusus role admin (UserController, KelasController, dst)
      Guru/            → controller khusus role guru (NilaiController, AbsensiController)
      Siswa/           → controller khusus role siswa (NilaiSiswaController, dst)
  Models/              → satu file per tabel, tanpa subfolder
database/
  migrations/          → urutan sesuai dependency (users dulu, baru siswa/guru, baru mengajar, dst)
  seeders/             → data dummy untuk testing tiap role
resources/
  views/
    layouts/           → layout dasar (app.blade.php, dst)
    components/        → komponen reusable (lihat 05-component-library.md)
    admin/             → view khusus admin
    guru/               → view khusus guru
    siswa/              → view khusus siswa
    auth/               → login, dst (biasanya sudah digenerate Breeze)
routes/
  web.php              → SEMUA route di sini, dikelompokkan per role pakai comment + middleware group
sql/
  procedures.sql        → semua CREATE PROCEDURE (dijalankan manual atau via migration DB::unprepared)
  triggers.sql           → semua CREATE TRIGGER
  functions.sql           → semua CREATE FUNCTION
```

**Kenapa controller dipisah per folder role (Admin/Guru/Siswa):** supaya jelas controller mana yang perlu middleware apa, dan menghindari 1 controller mega yang menangani 3 role sekaligus (rawan bug authorization).

---

## Naming Convention

| Jenis | Aturan | Contoh |
|---|---|---|
| Model | Singular, PascalCase | `Siswa`, `Mengajar`, `RekapNilai` |
| Tabel | Plural, snake_case | `siswa` *(exception: tetap singular, ikuti skema existing)*, `rekap_nilai` |
| Controller | PascalCase + `Controller` suffix | `NilaiController`, `AbsensiController` |
| Route name | kebab-case, prefix role | `guru.nilai.index`, `admin.kelas.create` |
| Blade view file | kebab-case | `input-nilai.blade.php`, `lihat-jadwal.blade.php` |
| Blade component | kebab-case, prefix `x-` saat dipakai | `<x-form-input>`, file di `components/form-input.blade.php` |
| Variable PHP | camelCase | `$rataRataNilai`, `$daftarSiswa` |
| Method controller | camelCase, kata kerja | `index()`, `store()`, `inputNilai()` |

**Catatan soal nama tabel:** ikuti persis nama tabel yang sudah ada di `03-database-schema.md` (`nilai`, `absensi`, `mengajar`, dst) — JANGAN diubah ke bentuk plural Laravel default kalau beda dari skema asli, karena procedure/trigger sudah reference nama tabel spesifik.

---

## Aturan Blade

**Layout inheritance:** semua halaman extend dari 1 layout dasar.

```blade
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html>
<head>...</head>
<body>
  @include('components.navbar')
  <main>@yield('content')</main>
</body>
</html>
```

```blade
{{-- contoh halaman turunan --}}
@extends('layouts.app')
@section('content')
  ...
@endsection
```

**Kapan pakai `@include` vs `@component`/`<x-...>`:**
- `@include('components.navbar')` — untuk elemen statis tanpa parameter (navbar, footer)
- `<x-form-input name="nilai" type="number" />` — untuk komponen yang butuh parameter/props (form field, card, button). Lihat `05-component-library.md` untuk daftar lengkap.

**Jangan** tulis HTML mentah berulang di banyak file untuk elemen yang sama (misal form input dengan style sama di 5 halaman berbeda) — itu justru bikin AI generate 5 versi berbeda-beda. Selalu cek dulu apakah komponennya sudah ada di `05-component-library.md` sebelum generate baru.

---

## Coding Style

- Ikuti **PSR-12** untuk PHP (default Laravel sudah PSR-12, tidak perlu setup tambahan)
- Indentasi Blade: 2 spasi (bukan tab, bukan 4 spasi) — biar gampang dibaca campuran HTML+PHP
- Query database: **selalu** pakai Eloquent atau query builder (`DB::table()`), tidak ada raw string SQL kecuali untuk manggil procedure/function (`DB::select('CALL sp_input_nilai_kelas(?, ?, ?, ?, ?)', [...])`)
- Validasi input: pakai Form Request class (`php artisan make:request`) untuk form yang lebih dari 3 field, biar controller tidak penuh kode validasi

---

## Transaction Handling (Wajib untuk Nilai & Absensi)

Modul Nilai (Nabil) dan Absensi (Hermanus) WAJIB pakai pola ini saat submit data per kelas:

```php
DB::beginTransaction();
try {
    foreach ($siswaList as $siswa) {
        DB::statement('CALL sp_input_nilai_kelas(?, ?, ?, ?, ?)', [
            $mengajarId, $jenis, $siswa->id, $nilai, auth()->id()
        ]);
    }
    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();
    return back()->withErrors(['error' => 'Gagal simpan nilai: ' . $e->getMessage()]);
}
```

Jangan skip try-catch atau rollback — ini poin yang paling sering dilewatkan AI kalau prompt tidak eksplisit minta transaction handling.
