@extends('layouts.app')

@section('title', 'Input Nilai')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-semibold text-slate-900">Input Nilai</h1>
    <p class="text-sm text-slate-500 mt-1">{{ $mengajar->kelas->nama_kelas }} - {{ $mengajar->mapel->nama_mapel }}</p>
</div>

<x-card title="Form Nilai - {{ $mengajar->kelas->nama_kelas }} ({{ $mengajar->mapel->nama_mapel }})">
    <form method="POST" action="{{ route('guru.nilai.store', $mengajar->id) }}" class="space-y-4">
        @csrf

        {{-- Pilih jenis nilai --}}
        <x-form-select name="jenis" label="Jenis Nilai" :options="['tugas' => 'Tugas', 'uts' => 'UTS', 'uas' => 'UAS']" />

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

            @foreach($siswaList as $index => $siswa)
            <tr>
                <td class="px-4 py-3">{{ $index + 1 }}</td>
                <td class="px-4 py-3">{{ $siswa->nama }}</td>
                <td class="px-4 py-3 text-slate-500">{{ $siswa->nis }}</td>
                <td class="px-4 py-3">
                    <input type="number"
                           name="nilai[{{ $siswa->id }}]"
                           min="0" max="100"
                           step="0.1"
                           placeholder="0-100"
                           class="w-24 border border-slate-200 rounded-sm px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-accent-soft focus:border-accent">
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