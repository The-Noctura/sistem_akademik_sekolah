@extends('layouts.app')

@section('title', 'Lihat Nilai')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-semibold text-slate-900">Nilai Saya</h1>
    <p class="text-sm text-slate-500 mt-1">Rekap nilai per mata pelajaran</p>
</div>

@if (empty($mengajarData))
    <x-card>
        <p class="text-slate-500 text-center py-8">Belum ada nilai yang tersedia.</p>
    </x-card>
@else
    @foreach($mengajarData as $data)
    <x-card title="{{ $data['mengajar']->mapel->nama_mapel }} ({{ $data['mengajar']->kelas->nama_kelas }})" class="mb-6">
        <x-table>
            <x-slot:head>
                <tr>
                    <th class="text-left px-4 py-3 font-medium">Jenis</th>
                    <th class="text-left px-4 py-3 font-medium">Nilai</th>
                </tr>
            </x-slot:head>

            @foreach($data['nilai'] as $nilai)
            <tr>
                <td class="px-4 py-3">{{ ucfirst($nilai->jenis) }}</td>
                <td class="px-4 py-3 font-medium text-accent">{{ $nilai->nilai }}</td>
            </tr>
            @endforeach

            <tr class="bg-surface font-medium">
                <td class="px-4 py-3">Rata-rata</td>
                <td class="px-4 py-3 text-accent">{{ $data['rata_rata'] }} <x-badge variant="default">{{ $data['predikat'] }}</x-badge></td>
            </tr>
        </x-table>
    </x-card>
    @endforeach
@endif
@endsection