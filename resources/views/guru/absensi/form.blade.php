@extends('layouts.app')

@section('title', 'Input Absensi')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-semibold text-slate-900">Input Absensi</h1>
    <p class="text-sm text-slate-500 mt-1">{{ $mengajar->kelas->nama_kelas }} - {{ $mengajar->mapel->nama_mapel }}</p>
</div>

<x-card title="Form Absensi - {{ $mengajar->kelas->nama_kelas }} ({{ $mengajar->mapel->nama_mapel }})">
    <form method="POST" action="{{ route('guru.absensi.store', $mengajar->id) }}" class="space-y-4">
        @csrf

        {{-- Input Tanggal --}}
        <x-form-input name="tanggal" label="Tanggal" type="date" :value="$tanggal" />

        {{-- Tabel siswa --}}
        <x-table>
            <x-slot:head>
                <tr>
                    <th class="text-left px-4 py-3 font-medium">No</th>
                    <th class="text-left px-4 py-3 font-medium">Nama Siswa</th>
                    <th class="text-left px-4 py-3 font-medium">NIS</th>
                    <th class="text-left px-4 py-3 font-medium">Status</th>
                </tr>
            </x-slot:head>

            @foreach($siswaList as $index => $siswa)
            <tr>
                <td class="px-4 py-3">{{ $index + 1 }}</td>
                <td class="px-4 py-3">{{ $siswa->nama }}</td>
                <td class="px-4 py-3 text-slate-500">{{ $siswa->nis }}</td>
                <td class="px-4 py-3">
                    <select name="status[{{ $siswa->id }}]" class="w-full border border-slate-200 rounded-sm px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent-soft focus:border-accent">
                        <option value="hadir" {{ old("status.{$siswa->id}") === 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="izin" {{ old("status.{$siswa->id}") === 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ old("status.{$siswa->id}") === 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="alpa" {{ old("status.{$siswa->id}") === 'alpa' ? 'selected' : '' }}>Alpa</option>
                    </select>
                </td>
            </tr>
            @endforeach
        </x-table>

        <div class="flex justify-end pt-4">
            <x-button variant="primary" type="submit">
                Simpan Absensi
            </x-button>
        </div>
    </form>
</x-card>
@endsection