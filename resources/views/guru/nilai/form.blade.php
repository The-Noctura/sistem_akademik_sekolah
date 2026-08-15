{{-- 
FILE: resources/views/guru/nilai/form.blade.php
TUJUAN: Form input nilai (VERSI MOCK/DATA DUMMY)
--}}

@extends('layouts.app')

@section('title', 'Input Nilai')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-semibold text-slate-900">Input Nilai</h1>
    <p class="text-sm text-slate-500 mt-1">Masukkan nilai siswa</p>
</div>

{{-- DATA DUMMY (sementara) --}}
@php
    $daftarSiswa = [
        (object) ['id' => 1, 'nama' => 'Ahmad Fauzi', 'nis' => '2024001'],
        (object) ['id' => 2, 'nama' => 'Budi Santoso', 'nis' => '2024002'],
        (object) ['id' => 3, 'nama' => 'Citra Dewi', 'nis' => '2024003'],
    ];
    
    $mengajar = (object) [
        'id' => 1,
        'kelas' => (object) ['nama_kelas' => 'X IPA 1'],
        'mapel' => (object) ['nama_mapel' => 'Matematika'],
    ];
@endphp

<x-card title="Form Nilai - {{ $mengajar->kelas->nama_kelas }} ({{ $mengajar->mapel->nama_mapel }})">
    <form method="POST" action="#" class="space-y-4">
        @csrf

        {{-- Pilih jenis nilai --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">Jenis Nilai</label>
            <select name="jenis" class="w-full border border-slate-200 rounded-sm px-3 py-2 text-sm">
                <option value="tugas">Tugas</option>
                <option value="uts">UTS</option>
                <option value="uas">UAS</option>
            </select>
        </div>

        {{-- Tabel siswa --}}
        <x-table>
            <x-slot:head>
                <tr>
                    <th class="text-left px-4 py-3 font-medium">No</th>
                    <th class="text-left px-4 py-3 font-medium">Nama Siswa</th>
                    <th class="text-left px-4 py-3 font-medium">NIS</th>
                    <th class="text-left px-4 py-3 font-medium">Nilai</th>
                </tr>
            </x-slot:head>

            @foreach($daftarSiswa as $index => $siswa)
            <tr>
                <td class="px-4 py-3">{{ $index + 1 }}</td>
                <td class="px-4 py-3">{{ $siswa->nama }}</td>
                <td class="px-4 py-3 text-slate-500">{{ $siswa->nis }}</td>
                <td class="px-4 py-3">
                    <input type="number" 
                           name="nilai[{{ $siswa->id }}]" 
                           min="0" max="100"
                           placeholder="0-100"
                           class="w-24 border border-slate-200 rounded-sm px-2 py-1 text-sm">
                </td>
            </tr>
            @endforeach
        </x-table>

        <div class="flex justify-end pt-4">
            <x-button variant="primary" type="submit">
                Simpan Nilai
            </x-button>
        </div>
    </form>
</x-card>
@endsection