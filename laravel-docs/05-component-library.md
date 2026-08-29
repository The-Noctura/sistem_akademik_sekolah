# 05 — Component Library

**Aturan pemakaian:** sebelum minta AI generate elemen UI baru (button, form, card, dst), cek dulu apakah sudah ada di sini. Kalau sudah ada, minta AI **pakai** komponen ini, bukan generate versi baru dari nol. Ini yang mencegah 4 orang punya 4 versi tombol berbeda.

Semua komponen di bawah pakai token dari `01-design-system.md` lewat Tailwind (`accent`, `surface`, dst) — kalau Tailwind belum displit sesuai config di sana, komponen ini tidak akan tampil benar.

Lokasi file: `resources/views/components/`

---

## 1. Layout Dasar

`resources/views/layouts/app.blade.php`

```blade
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Akademik')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
</head>
<body class="bg-white text-slate-900 font-sans">
    @include('components.navbar')

    <main class="max-w-6xl mx-auto px-4 py-8">
        @if (session('success'))
            <x-alert type="success" :message="session('success')" />
        @endif
        @if ($errors->any())
            <x-alert type="error" :message="$errors->first()" />
        @endif

        @yield('content')
    </main>
</body>
</html>
```

Semua halaman: `@extends('layouts.app')` lalu isi `@section('content')`.

---

## 2. Navbar

`resources/views/components/navbar.blade.php`

```blade
<nav class="border-b border-slate-200 bg-white">
    <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
        <span class="font-semibold text-lg">Sistem Akademik</span>

        @auth
        <div class="flex items-center gap-4 text-sm">
            <span class="text-slate-500">{{ auth()->user()->nama }} · {{ ucfirst(auth()->user()->role) }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-slate-500 hover:text-accent">Keluar</button>
            </form>
        </div>
        @endauth
    </div>
</nav>
```

Tidak ada menu navigasi kompleks di navbar — navigasi per role cukup lewat dashboard masing-masing (lihat komponen Dashboard Card di bawah). Ini sengaja disederhanakan supaya tidak perlu logic dropdown/mobile-menu yang makan waktu.

---

## 3. Button

`resources/views/components/button.blade.php`

```blade
@props(['variant' => 'primary', 'type' => 'button'])

@php
$classes = match($variant) {
    'primary'   => 'bg-accent text-white hover:bg-accent-hover',
    'secondary' => 'bg-transparent border border-slate-200 text-slate-900 hover:bg-surface',
    'danger'    => 'bg-red-600 text-white hover:bg-red-700',
};
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => "px-4 py-2 rounded-md text-sm font-medium transition-colors $classes"]) }}>
    {{ $slot }}
</button>
```

**Pemakaian:**
```blade
<x-button variant="primary" type="submit">Simpan Nilai</x-button>
<x-button variant="secondary" type="button" onclick="history.back()">Batal</x-button>
<x-button variant="danger" type="submit">Hapus</x-button>
```

---

## 4. Card

`resources/views/components/card.blade.php`

```blade
@props(['title' => null])

<div {{ $attributes->merge(['class' => 'bg-surface border border-slate-200 rounded-lg p-6 shadow-sm']) }}>
    @if($title)
        <h3 class="text-lg font-semibold mb-4">{{ $title }}</h3>
    @endif
    {{ $slot }}
</div>
```

**Pemakaian:**
```blade
<x-card title="Ringkasan Nilai">
    <p class="text-sm text-slate-500">Rata-rata: 85.5</p>
</x-card>
```

---

## 5. Form Input

`resources/views/components/form-input.blade.php`

```blade
@props(['name', 'label', 'type' => 'text', 'value' => null])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium mb-1">{{ $label }}</label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        {{ $attributes->merge(['class' => 'w-full border rounded-sm px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent-soft focus:border-accent ' . ($errors->has($name) ? 'border-red-600' : 'border-slate-200')]) }}
    >
    @error($name)
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
```

**Pemakaian:**
```blade
<x-form-input name="nama" label="Nama Lengkap" />
<x-form-input name="nilai" label="Nilai" type="number" />
```

---

## 6. Select Dropdown

`resources/views/components/form-select.blade.php`

```blade
@props(['name', 'label', 'options' => [], 'selected' => null])

<div class="mb-4">
    <label for="{{ $name }}" class="block text-sm font-medium mb-1">{{ $label }}</label>
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->merge(['class' => 'w-full border rounded-sm px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent-soft focus:border-accent ' . ($errors->has($name) ? 'border-red-600' : 'border-slate-200')]) }}
    >
        <option value="">-- Pilih --</option>
        @foreach($options as $value => $label)
            <option value="{{ $value }}" @selected(old($name, $selected) == $value)>{{ $label }}</option>
        @endforeach
    </select>
    @error($name)
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
```

**Pemakaian (contoh dropdown kelas di form input nilai):**
```blade
<x-form-select name="kelas_id" label="Pilih Kelas" :options="$kelasList->pluck('nama_kelas', 'id')" />
```

---

## 7. Tabel Data

`resources/views/components/table.blade.php`

Komponen ini cuma bungkus struktur — isi `<thead>`/`<tbody>` tetap ditulis manual per halaman karena kolomnya beda-beda (tabel nilai vs tabel absensi vs tabel user).

```blade
<div class="overflow-x-auto border border-slate-200 rounded-lg">
    <table class="w-full text-sm">
        <thead class="bg-surface border-b border-slate-200">
            {{ $head }}
        </thead>
        <tbody class="divide-y divide-slate-200">
            {{ $slot }}
        </tbody>
    </table>
</div>
```

**Pemakaian (contoh tabel input nilai per kelas — Role Nabil):**
```blade
<x-table>
    <x-slot:head>
        <tr>
            <th class="text-left px-4 py-3 font-medium">Nama Siswa</th>
            <th class="text-left px-4 py-3 font-medium">NIS</th>
            <th class="text-left px-4 py-3 font-medium">Nilai</th>
        </tr>
    </x-slot:head>

    @foreach($siswaList as $siswa)
    <tr class="hover:bg-surface">
        <td class="px-4 py-3">{{ $siswa->nama }}</td>
        <td class="px-4 py-3 text-slate-500">{{ $siswa->nis }}</td>
        <td class="px-4 py-3">
            <input type="number" name="nilai[{{ $siswa->id }}]" min="0" max="100"
                   class="w-20 border border-slate-200 rounded-sm px-2 py-1">
        </td>
    </tr>
    @endforeach
</x-table>
```

---

## 8. Badge

`resources/views/components/badge.blade.php`

```blade
@props(['variant' => 'default'])

@php
$classes = match($variant) {
    'success' => 'bg-green-50 text-green-700',
    'error'   => 'bg-red-50 text-red-700',
    'warning' => 'bg-amber-50 text-amber-700',
    default   => 'bg-accent-soft text-accent',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-block px-2 py-1 rounded-sm text-xs font-medium $classes"]) }}>
    {{ $slot }}
</span>
```

**Pemakaian (contoh status absensi — Role Hermanus):**
```blade
<x-badge variant="{{ $status === 'hadir' ? 'success' : ($status === 'alpa' ? 'error' : 'warning') }}">
    {{ ucfirst($status) }}
</x-badge>
```

---

## 9. Alert (Notifikasi Sukses/Gagal)

`resources/views/components/alert.blade.php`

```blade
@props(['type' => 'success', 'message'])

@php
$classes = $type === 'success' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200';
@endphp

<div class="border {{ $classes }} rounded-md px-4 py-3 mb-6 text-sm">
    {{ $message }}
</div>
```

Dipakai otomatis lewat `layouts.app.blade.php` — tidak perlu dipanggil manual di tiap halaman, cukup pakai `session('success')` atau validasi error Laravel standar dan alert akan muncul otomatis.

---

## 10. Dashboard Card (Navigasi per Role)

`resources/views/components/dashboard-link.blade.php`

Dipakai di halaman dashboard tiap role sebagai pengganti menu navbar kompleks.

```blade
@props(['href', 'title', 'description'])

<a href="{{ $href }}" class="block bg-surface border border-slate-200 rounded-lg p-6 hover:shadow-md hover:border-accent transition-all">
    <h3 class="font-semibold mb-1">{{ $title }}</h3>
    <p class="text-sm text-slate-500">{{ $description }}</p>
</a>
```

**Pemakaian (contoh dashboard guru — Role Nabil/Hermanus/Noctura kontribusi masing-masing):**
```blade
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <x-dashboard-link href="{{ route('guru.nilai.index') }}" title="Input Nilai" description="Input nilai per kelas yang diajar" />
    <x-dashboard-link href="{{ route('guru.absensi.index') }}" title="Input Absensi" description="Tandai kehadiran siswa" />
    <x-dashboard-link href="{{ route('guru.jadwal.index') }}" title="Jadwal Mengajar" description="Lihat jadwal mengajar" />
</div>
```

---

## Cara Prompting AI untuk Pakai Komponen Ini

Contoh prompt yang benar:

> "Buatkan halaman form input nilai untuk guru. Pakai komponen `<x-table>`, `<x-form-select>`, dan `<x-button>` dari `05-component-library.md`. Layout ikuti `layouts.app`. Kolom nilai harus input number dengan `name="nilai[{siswa_id}]"` karena akan di-submit sebagai array untuk diproses backend per siswa."

Contoh prompt yang salah (bikin AI reinvent komponen baru):

> "Buatkan halaman form input nilai yang bagus untuk guru."

Semakin eksplisit menyebut nama komponen yang harus dipakai, semakin kecil kemungkinan hasilnya tidak konsisten dengan 3 modul lain.
