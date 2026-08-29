# 00 — Project Overview

**Baca ini dulu sebelum ngerjain bagian apa pun.** File ini konteks besar — kalau prompting AI untuk generate kode, lampirkan ringkasan file ini supaya AI tidak salah asumsi soal siapa user-nya dan apa yang penting.

---

## Tujuan Project

Website sekolah modul akademik — mengelola nilai, absensi, dan jadwal pelajaran secara digital. Menggantikan pencatatan manual (buku nilai, absen kertas) dengan sistem yang bisa diakses guru dan siswa langsung.

**Bukan tujuannya:** bukan sistem informasi sekolah lengkap (tidak ada PPDB, keuangan, perpustakaan, dll). Fokus sempit ke 3 hal: nilai, absensi, jadwal.

---

## Target User (3 Role)

| Role | Siapa | Kebutuhan utama |
|---|---|---|
| **Admin** | Staf TU/operator sekolah | Kelola data dasar: user, kelas, mapel, siapa-ngajar-apa, jadwal |
| **Guru** | Pengajar mapel | Input nilai & absensi per kelas yang diajar, lihat jadwal mengajar sendiri |
| **Siswa** | Murid | Lihat nilai & rekap absensi sendiri, lihat jadwal pelajaran |

**Catatan penting:** tidak ada role orang tua terpisah. Kalau orang tua perlu akses, sementara pakai akun siswa yang sama — bukan celah, tapi keputusan scope yang disengaja untuk versi ini.

---

## Scope Fitur

### Must Have (wajib selesai)
1. Auth & role-based login (admin/guru/siswa)
2. Input & Lihat Nilai (guru input, siswa lihat)
3. Input & Lihat Absensi (guru input, siswa lihat)
4. Lihat Jadwal Pelajaran (read-only)
5. Manajemen data dasar oleh admin (user, kelas, mapel, mengajar, jadwal)

### Nice to Have (boleh skip minggu ini)
Rapor PDF otomatis, dashboard analitik visual, notifikasi, sistem kelulusan otomatis, halaman log perubahan, multi-tahun-ajaran penuh, role orang tua terpisah, export Excel/CSV.

*(Detail lengkap alur pakai & elemen tampilan tiap fitur ada di dokumen scope website terpisah — file ini cuma ringkasan konteks.)*

---

## Constraint Project

- **Tim:** 4 orang (Iki, Nabil, Hermanus, Noctura sebagai PM), level pemula, part-time
- **Deadline:** 1 minggu
- **Stack:** Laravel + Blade + MySQL (lihat `02-tech-conventions.md`)
- **Metode kerja:** dibantu AI — tim fokus prompting, review, dan integrasi, bukan nulis kode dari nol
- **Skema database sudah final** duluan sebelum fitur website dirancang — artinya struktur tabel, stored procedure, trigger, function TIDAK BOLEH diubah sembarangan oleh AI saat generate migration/model. Kalau AI menyarankan ubah skema, itu tanda harus didiskusikan ke tim dulu, bukan langsung dieksekusi.

---

## Kenapa File Ini Penting untuk Prompting AI

Kalau prompt ke AI cuma bilang "buatkan halaman input nilai", AI tidak tahu:
- Siapa yang boleh akses halaman ini (role guru, bukan siswa)
- Bahwa nilai harus melalui stored procedure `sp_input_nilai_kelas`, bukan `Model::create()` biasa
- Bahwa ini bagian dari sistem sekolah asli, bukan demo/prototype bebas gaya

Lampirkan file ini (atau ringkasannya) di awal prompt supaya AI generate kode yang nyambung ke konteks, bukan kode generik yang harus dirombak ulang.
