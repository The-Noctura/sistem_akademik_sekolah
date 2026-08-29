@extends('layouts.app')

@section('title', 'Lihat Absensi')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-semibold text-slate-900">Rekap Absensi</h1>
    <p class="text-sm text-slate-500 mt-1">Rekap kehadiran per mata pelajaran</p>
</div>

@if (empty($mengajarData))
    <x-card>
        <p class="text-slate-500 text-center py-8">Belum ada data absensi yang tersedia.</p>
    </x-card>
@else
    @foreach($mengajarData as $data)
    <x-card title="{{ $data['mengajar']->mapel->nama_mapel }} ({{ $data['mengajar']->kelas->nama_kelas }})" class="mb-6">
        <div class="mb-4">
            <span class="text-2xl font-semibold text-accent">{{ $data['persentase'] }}%</span>
            <span class="text-slate-500 ml-2">Kehadiran</span>
        </div>

        <x-table>
            <x-slot:head>
                <tr>
                    <th class="text-left px-4 py-3 font-medium">Status</th>
                    <th class="text-left px-4 py-3 font-medium">Total</th>
                </tr>
            </x-slot:head>

            <tr>
                <td class="px-4 py-3"><x-badge variant="success">Hadir</x-badge></td>
                <td class="px-4 py-3 font-medium">{{ $data['rekap']->total_hadir }}</td>
            </tr>
            <tr>
                <td class="px-4 py-3"><x-badge variant="warning">Izin</x-badge></td>
                <td class="px-4 py-3 font-medium">{{ $data['rekap']->total_izin }}</td>
            </tr>
            <tr>
                <td class="px-4 py-3"><x-badge variant="warning">Sakit</x-badge></td>
                <td class="px-4 py-3 font-medium">{{ $data['rekap']->total_sakit }}</td>
            </tr>
            <tr>
                <td class="px-4 py-3"><x-badge variant="error">Alpa</x-badge></td>
                <td class="px-4 py-3 font-medium">{{ $data['rekap']->total_alpa }}</td>
            </tr>
        </x-table>
    </x-card>
    @endforeach
@endif
@endsection