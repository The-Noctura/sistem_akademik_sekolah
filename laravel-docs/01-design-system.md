# 01 — Design System

**File paling kritis di dokumentasi ini.** Kalau 4 orang generate UI dengan token beda-beda (warna beda, spacing beda, radius beda), hasilnya bakal keliatan seperti digabung dari 4 project berbeda. Selalu lampirkan file ini di prompt AI setiap kali minta generate tampilan.

**Arah visual:** Modern minimalis — putih bersih, 1 warna aksen, banyak whitespace. Tidak ada gradient, tidak ada bayangan berlebihan, tidak ada dekorasi yang tidak fungsional.

---

## Design Tokens

### Warna

```css
/* Base */
--color-bg:            #FFFFFF;   /* background utama */
--color-surface:       #F8FAFC;   /* background card/section, sedikit beda dari bg utama */
--color-border:        #E2E8F0;   /* border tipis, divider */

/* Teks */
--color-text:          #0F172A;   /* teks utama, hampir hitam */
--color-text-muted:    #64748B;   /* teks sekunder, label, caption */
--color-text-inverse:  #FFFFFF;   /* teks di atas warna gelap/aksen */

/* Aksen (SATU warna, dipakai konsisten di semua elemen interaktif) */
--color-accent:        #2563EB;   /* biru — tombol utama, link, elemen aktif */
--color-accent-hover:  #1D4ED8;   /* versi lebih gelap untuk hover */
--color-accent-soft:   #EFF6FF;   /* versi sangat muda untuk background badge/highlight ringan */

/* Status (dipakai TERBATAS — hanya untuk feedback, bukan dekorasi) */
--color-success:       #16A34A;   /* notifikasi berhasil simpan nilai/absensi */
--color-error:         #DC2626;   /* validasi gagal, error form */
--color-warning:       #D97706;   /* peringatan non-blocking */
```

**Kenapa biru (`#2563EB`) sebagai aksen:** netral, tidak keliru asosiasi (bukan merah=bahaya atau hijau=bisa disalahartikan status), umum dipakai untuk web institusional/edukasi, dan cukup kontras di atas putih untuk aksesibilitas.

**Aturan pemakaian warna status:** JANGAN dipakai untuk elemen dekoratif atau kategori (misal jangan warnai badge role admin/guru/siswa pakai success/error/warning). Warna status HANYA untuk feedback aksi (berhasil simpan, gagal validasi).

### Tipografi

```css
--font-family: 'Inter', -apple-system, sans-serif;   /* Google Fonts */

--text-xs:    0.75rem;   /* 12px — caption, label kecil */
--text-sm:    0.875rem;  /* 14px — teks body sekunder, tabel */
--text-base:  1rem;      /* 16px — teks body utama */
--text-lg:    1.125rem;  /* 18px — subheading */
--text-xl:    1.5rem;    /* 24px — heading halaman (h1) */
--text-2xl:   2rem;      /* 32px — jarang dipakai, hanya landing/dashboard utama */

--font-normal:   400;
--font-medium:   500;
--font-semibold: 600;   /* dipakai untuk heading dan label penting, JANGAN bold (700) */
```

**Aturan:** tidak pakai `font-weight: 700` (bold penuh) di mana pun — `semibold` (600) sudah cukup untuk penekanan, dan lebih konsisten dengan gaya minimalis.

### Spacing

Pakai skala 4px sebagai basis (`0.25rem`), ikuti Tailwind default agar tidak perlu custom config:

```
spacing-1 = 0.25rem (4px)
spacing-2 = 0.5rem  (8px)
spacing-3 = 0.75rem (12px)
spacing-4 = 1rem    (16px)   ← default gap antar elemen form
spacing-6 = 1.5rem  (24px)   ← default padding card
spacing-8 = 2rem    (32px)   ← jarak antar section
spacing-12 = 3rem   (48px)   ← jarak antar section besar di halaman dashboard
```

### Radius & Shadow

```css
--radius-sm:  0.375rem;  /* 6px — input, badge kecil */
--radius-md:  0.5rem;    /* 8px — button, card kecil */
--radius-lg:  0.75rem;   /* 12px — card besar, modal */

--shadow-sm:  0 1px 2px rgba(15, 23, 42, 0.05);   /* card default */
--shadow-md:  0 4px 12px rgba(15, 23, 42, 0.08);  /* card hover, dropdown */
```

**Aturan:** tidak ada shadow lebih berat dari `shadow-md` di mana pun — gaya minimalis tidak butuh drop shadow dramatis.

### Breakpoint

Pakai default Tailwind (tidak perlu custom):

```
sm:  640px
md:  768px
lg:  1024px
xl:  1280px
```

**Prioritas responsif:** halaman guru/admin diasumsikan dibuka di desktop/laptop (form input nilai/absensi butuh tabel lebar). Halaman siswa (lihat nilai/absensi/jadwal) harus tetap nyaman di `sm` karena kemungkinan diakses dari HP.

---

## Setup Tailwind (Kalau Dipakai)

Kalau tim pakai Tailwind (disarankan, karena lebih cepat untuk AI generate konsisten dibanding custom CSS), taruh token di atas ke `tailwind.config.js`:

```js
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      colors: {
        accent: {
          DEFAULT: '#2563EB',
          hover: '#1D4ED8',
          soft: '#EFF6FF',
        },
        surface: '#F8FAFC',
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
    },
  },
};
```

Setelah ini, dipakai sebagai `bg-accent`, `text-accent`, `hover:bg-accent-hover`, dst — jangan tulis hex code langsung di class Blade (`bg-[#2563EB]`), selalu lewat token supaya kalau warna aksen berubah, cukup ubah 1 tempat.

---

## Komponen UI Reusable (Deskripsi Perilaku)

*(Kode Blade lengkap tiap komponen ada di `05-component-library.md` — bagian ini cuma spesifikasi visual/perilaku.)*

### Button

- **Primary** (aksi utama: Simpan, Submit): background `accent`, teks putih, radius `md`
- **Secondary** (aksi sekunder: Batal, Kembali): background transparan, border `color-border`, teks `color-text`
- **Danger** (aksi hapus): background `color-error`, teks putih — dipakai terbatas, hanya untuk delete/hapus data

Semua button: padding vertikal `spacing-2`, horizontal `spacing-4`, radius `radius-md`, transisi hover 150ms.

### Card

Background `surface`, border `color-border` tipis 1px, radius `radius-lg`, padding `spacing-6`, shadow `shadow-sm` (naik ke `shadow-md` saat hover kalau card bersifat clickable).

### Form Input

- Border `color-border`, radius `radius-sm`, padding `spacing-3`
- Focus state: border berubah ke `accent`, tanpa outline browser default (`focus:ring` tipis warna `accent-soft`)
- Error state: border `color-error`, pesan error di bawah input pakai `text-sm` warna `color-error`

### Tabel

Dipakai di form input nilai/absensi (tabel siswa) dan tampilan lihat nilai/absensi. Header row background `surface`, border bawah tiap baris tipis `color-border`, hover row sedikit highlight `surface`.

### Badge

Dipakai untuk status (role user, status absensi, jenis nilai). Radius `radius-sm` (bukan pill penuh, konsisten sama radius lain), padding kecil, teks `text-xs` `font-medium`.

---

## Hal yang HARUS Dihindari

Supaya tetap terasa satu produk, bukan digabung dari beberapa gaya:

- **Jangan** tambah warna aksen kedua/ketiga — kalau butuh variasi, pakai `accent-soft` atau `text-muted`, bukan warna baru
- **Jangan** pakai gradient di background atau tombol
- **Jangan** pakai font selain Inter (kalau AI generate dengan Poppins/Roboto/dst secara default, ganti manual ke Inter)
- **Jangan** pakai border-radius lebih dari `radius-lg` (12px) — tidak ada elemen bulat penuh (`rounded-full`) kecuali avatar/ikon kecil
- **Jangan** campur icon set berbeda (pilih 1: disarankan [Lucide](https://lucide.dev) karena ringan dan gampang dipakai di Blade lewat CDN/package)
