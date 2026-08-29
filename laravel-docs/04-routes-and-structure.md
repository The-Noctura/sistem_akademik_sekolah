# 04 — Routes & Structure

**Tujuan:** daftar route yang direncanakan supaya tidak ada 2 orang menggarap route yang sama, dan supaya AI generate route dengan penamaan konsisten. Update file ini kalau ada route baru yang disepakati — jangan biarkan route "liar" yang tidak tercatat di sini.

Semua route didaftar di `routes/web.php`, dikelompokkan per role pakai route group + middleware.

---

## Struktur Middleware Group

```php
// routes/web.php

Route::middleware(['auth'])->group(function () {

    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // route admin di sini
    });

    Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
        // route guru di sini
    });

    Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
        // route siswa di sini
    });

});
```

*(Middleware `role` dibuat Iki sebagai bagian dari modul Auth — cek `app/Http/Middleware/` setelah modul A selesai.)*

---

## Daftar Route

### Auth (Role A — Iki, biasanya sudah tergenerate dari Laravel Breeze)

| Route | Method | Ke Controller | Keterangan |
|---|---|---|---|
| `/login` | GET, POST | Breeze default | |
| `/logout` | POST | Breeze default | |
| `/dashboard` | GET | `DashboardController@index` | Redirect isi beda tergantung role |

### Admin (Role A — Iki)

| Route | Method | Name | Controller |
|---|---|---|---|
| `/admin/users` | GET, POST | `admin.users.*` | `Admin\UserController` |
| `/admin/users/{id}/edit` | GET, PUT | `admin.users.edit/update` | `Admin\UserController` |
| `/admin/kelas` | GET, POST | `admin.kelas.*` | `Admin\KelasController` |
| `/admin/mapel` | GET, POST | `admin.mapel.*` | `Admin\MapelController` |
| `/admin/mengajar` | GET, POST | `admin.mengajar.*` | `Admin\MengajarController` |

Gunakan `Route::resource()` untuk tiap controller di atas — sudah cover index/create/store/edit/update/destroy sekaligus, tidak perlu daftar manual satu-satu.

### Nilai (Role B — Nabil)

| Route | Method | Name | Controller |
|---|---|---|---|
| `/guru/nilai` | GET | `guru.nilai.index` | `Guru\NilaiController@index` — pilih kelas/mapel |
| `/guru/nilai/{mengajar_id}` | GET | `guru.nilai.form` | `Guru\NilaiController@form` — form input per kelas |
| `/guru/nilai/{mengajar_id}` | POST | `guru.nilai.store` | `Guru\NilaiController@store` — submit batch nilai sekelas |
| `/siswa/nilai` | GET | `siswa.nilai.index` | `Siswa\NilaiController@index` — lihat nilai sendiri |

### Absensi (Role C — Hermanus)

| Route | Method | Name | Controller |
|---|---|---|---|
| `/guru/absensi` | GET | `guru.absensi.index` | `Guru\AbsensiController@index` — pilih kelas/mapel/tanggal |
| `/guru/absensi/{mengajar_id}` | GET | `guru.absensi.form` | `Guru\AbsensiController@form` |
| `/guru/absensi/{mengajar_id}` | POST | `guru.absensi.store` | `Guru\AbsensiController@store` — submit batch absensi sekelas |
| `/siswa/absensi` | GET | `siswa.absensi.index` | `Siswa\AbsensiController@index` — lihat rekap sendiri |

### Jadwal (Role D — Noctura)

| Route | Method | Name | Controller |
|---|---|---|---|
| `/admin/jadwal` | GET, POST | `admin.jadwal.*` | `Admin\JadwalController` — CRUD (pakai `Route::resource`) |
| `/guru/jadwal` | GET | `guru.jadwal.index` | `Guru\JadwalController@index` — read-only, filter guru login |
| `/siswa/jadwal` | GET | `siswa.jadwal.index` | `Siswa\JadwalController@index` — read-only, filter kelas siswa |

---

## Siapa Pegang Apa (Ringkasan)

| Prefix route | Role pengerjaan |
|---|---|
| `/admin/users`, `/admin/kelas`, `/admin/mapel`, `/admin/mengajar` | Iki |
| `/guru/nilai`, `/siswa/nilai` | Nabil |
| `/guru/absensi`, `/siswa/absensi` | Hermanus |
| `/admin/jadwal`, `/guru/jadwal`, `/siswa/jadwal` | Noctura |
| `/login`, `/logout`, `/dashboard`, middleware `role` | Iki |

Kalau ada route baru yang dibutuhkan di tengah jalan (misal endpoint AJAX kecil), tambahkan ke tabel di atas dan broadcast ke tim — supaya tidak ada 2 orang bikin route yang sama tanpa sadar.

---

## Urutan Dependency (Penting untuk H1-H2)

```
1. Middleware `role` + auth (Iki)     ← blocker semua route lain
2. Tabel `mengajar` harus ada (Iki)   ← blocker route nilai, absensi, jadwal guru/siswa
3. Baru setelah itu: Nabil, Hermanus, Noctura bisa mulai route masing-masing secara paralel
```

Kalau di H2 modul A belum selesai, Nabil/Hermanus/Noctura tetap bisa mulai bikin Blade view dengan data dummy (`@php $siswa = collect([...])@endphp` atau semacamnya) — jangan nunggu total, cukup jangan submit form ke database sungguhan dulu sampai modul A siap.
