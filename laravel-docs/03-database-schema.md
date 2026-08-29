# 03 — Database Schema

**Sumber:** diringkas dari `rancangan-sistem-akademik-sekolah_1_.md` (dokumen teknis lengkap, termasuk isi SQL procedure/trigger/function penuh ada di sana).

**Aturan penting:** skema di file ini FINAL. Kalau prompting AI untuk generate migration/model dan AI menyarankan ubah struktur (tambah kolom, ubah tipe data, dst), diskusikan dulu ke tim — jangan langsung diterapkan. Procedure/trigger/function sudah ditulis mengacu ke struktur persis seperti ini.

---

## Daftar Tabel

### Master Data

| Tabel | Kolom | Keterangan |
|---|---|---|
| `users` | id, nama, email, password, role, created_at | `role` ENUM('admin','guru','siswa') |
| `siswa` | id, user_id (FK), nis, nama, kelas_id (FK), jenis_kelamin, tanggal_lahir | 1-1 ke `users` |
| `guru` | id, user_id (FK), nip, nama, no_hp | 1-1 ke `users` |
| `kelas` | id, nama_kelas, tingkat, wali_kelas_id (FK → guru.id), tahun_ajaran | |
| `mapel` | id, nama_mapel, kode_mapel | |

### Relasi Pengajaran

| Tabel | Kolom | Keterangan |
|---|---|---|
| `mengajar` | id, guru_id (FK), mapel_id (FK), kelas_id (FK), tahun_ajaran, semester | Satu baris = satu kombinasi guru+mapel+kelas+semester. Semua tabel di bawah reference ke sini, bukan ke guru/mapel/kelas langsung. |

### Modul Inti

| Tabel | Kolom | UNIQUE KEY |
|---|---|---|
| `jadwal` | id, mengajar_id (FK), hari, jam_mulai, jam_selesai, ruangan | — |
| `nilai` | id, siswa_id (FK), mengajar_id (FK), jenis, nilai, tanggal_input, diinput_oleh (FK → users.id) | `(siswa_id, mengajar_id, jenis)` — **wajib**, dipakai `ON DUPLICATE KEY UPDATE` |
| `absensi` | id, siswa_id (FK), mengajar_id (FK), tanggal, status | `(siswa_id, mengajar_id, tanggal)` — **wajib**, cegah absen dobel |

`jenis` ENUM('tugas','uts','uas') · `status` ENUM('hadir','izin','sakit','alpa') · `hari` ENUM('senin'..'sabtu')

### Rekap (Denormalisasi — Auto-generate, Jangan Diedit Manual)

| Tabel | Kolom | UNIQUE KEY |
|---|---|---|
| `rekap_nilai` | id, siswa_id (FK), mengajar_id (FK), semester, rata_rata, nilai_akhir, predikat, updated_at | `(siswa_id, mengajar_id, semester)` |
| `rekap_absensi` | id, siswa_id (FK), mengajar_id (FK), semester, total_hadir, total_izin, total_sakit, total_alpa, persentase_hadir, updated_at | `(siswa_id, mengajar_id, semester)` |

Kedua tabel ini terisi **otomatis lewat trigger** — jangan buat form CRUD manual untuk tabel ini.

### Audit (Opsional)

| Tabel | Kolom |
|---|---|
| `log_perubahan` | id, tabel, record_id, aksi, user_id (FK), waktu, data_lama (JSON), data_baru (JSON) |

Saat ini trigger log baru ada untuk `nilai` (`trg_log_nilai_update`). Belum ada untuk `absensi`.

---

## ERD Ringkas

```
users 1───1 siswa
users 1───1 guru
kelas 1───N siswa
kelas 1───1 guru        (wali_kelas_id — CATATAN: beda dari "guru yang mengajar di kelas itu")
guru  1───N mengajar
mapel 1───N mengajar
kelas 1───N mengajar
mengajar 1───N jadwal
mengajar 1───N nilai
mengajar 1───N absensi
siswa 1───N nilai
siswa 1───N absensi
siswa 1───N rekap_nilai
siswa 1───N rekap_absensi
```

**Catatan krusial soal `wali_kelas_id` vs `mengajar`:** wali kelas (di tabel `kelas`) BUKAN berarti guru itu yang mengajar mapel di kelas tersebut. Dashboard guru harus query lewat tabel `mengajar`, bukan `kelas.wali_kelas_id`, untuk menentukan kelas mana yang sedang diajar guru tersebut.

---

## Objek Database Selain Tabel

Detail SQL lengkap ada di `sql/procedures.sql`, `sql/triggers.sql`, `sql/functions.sql` (lihat `02-tech-conventions.md` untuk lokasi file). Ringkasan:

| Nama | Jenis | Dipakai di |
|---|---|---|
| `sp_input_nilai_kelas` | Procedure | Modul Nilai — input/update nilai per siswa |
| `sp_rekap_absensi` | Procedure | Dipanggil otomatis oleh `trg_absensi_insert`, jangan panggil manual dari backend |
| `fn_rata_rata_nilai` | Function | Dipakai trigger nilai, dan fallback tampilan kalau `rekap_nilai` belum ke-generate |
| `fn_persentase_hadir` | Function | Fallback tampilan kalau `rekap_absensi` belum ke-generate |
| `trg_rekap_nilai_insert` | Trigger (AFTER INSERT ON nilai) | Update `rekap_nilai` saat nilai baru masuk |
| `trg_rekap_nilai_update` | Trigger (AFTER UPDATE ON nilai) | Update `rekap_nilai` saat nilai diedit — **paling sering lupa dibuat, cek manual** |
| `trg_log_nilai_update` | Trigger (AFTER UPDATE ON nilai) | Catat audit trail ke `log_perubahan` |
| `trg_absensi_insert` | Trigger (AFTER INSERT ON absensi) | Otomatis panggil `sp_rekap_absensi` |

---

## Validasi yang TIDAK Dijamin oleh Skema (Wajib di Backend)

Tidak ada FK constraint yang mencegah ini — kalau AI generate controller/form tanpa validasi eksplisit, celah ini akan lolos:

1. **Rentang nilai** — `nilai` harus `0 ≤ x ≤ 100`, tidak divalidasi oleh tipe data `DECIMAL(5,2)` saja
2. **Kecocokan siswa-kelas** — pastikan `siswa.kelas_id = mengajar.kelas_id` sebelum insert ke `nilai`/`absensi`. Siswa dari kelas lain bisa ke-input kalau tidak dicek manual di backend.
3. **Role di endpoint** — misal endpoint input nilai harus ditolak kalau yang login role `siswa`, ini bukan dijamin oleh tabel `users.role` sendiri, harus di-enforce lewat middleware

---

## Data Testing yang Dibutuhkan Semua Modul

Role A (Iki) bertanggung jawab bikin seeder minimal ini dan broadcast ke tim begitu selesai:
- 1 akun admin
- 2 akun guru, masing-masing punya minimal 1 baris di `mengajar`
- 1 kelas dengan 5-10 siswa
- 1-2 mapel

Tanpa data ini, modul B/C/D (Nilai, Absensi, Jadwal) tidak bisa mulai testing form.
