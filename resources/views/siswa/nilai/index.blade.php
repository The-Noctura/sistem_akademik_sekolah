{{-- 
FILE: resources/views/siswa/nilai/index.blade.php
TUJUAN: Halaman siswa lihat nilai (VERSI MOCK)
--}}

@extends('layouts.app')

@section('title', 'Lihat Nilai')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-semibold text-slate-900">Nilai Saya</h1>
    <p class="text-sm text-slate-500 mt-1">Rekap nilai per mata pelajaran</p>
</div>

{{-- DATA DUMMY --}}
@php
    $nilaiPerMapel = [
        (object) [
            'mapel' => 'Matematika',
            'nilai' => [
                (object) ['jenis' => 'Tugas', 'nilai' => 85],
                (object) ['jenis' => 'UTS', 'nilai' => 80],
                (object) ['jenis' => 'UAS', 'nilai' => 90],
            ],
            'rata_rata' => 85
        ],
    ];
@endphp

@foreach($nilaiPerMapel as $data)
<x-card title="{{ $data->mapel }}" class="mb-6">
    <x-table>
        <x-slot:head>
            <tr>
                <th class="text-left px-4 py-3 font-medium">Jenis</th>
                <th class="text-left px-4 py-3 font-medium">Nilai</th>
            </tr>
        </x-slot:head>

        @foreach($data->nilai as $item)
        <tr>
            <td class="px-4 py-3">{{ $item->jenis }}</td>
            <td class="px-4 py-3 font-medium text-accent">{{ $item->nilai }}</td>
        </tr>
        @endforeach

        <tr class="bg-surface font-medium">
            <td class="px-4 py-3">Rata-rata</td>
            <td class="px-4 py-3 text-accent">{{ $data->rata_rata }}</td>
        </tr>
    </x-table>
</x-card>
@endforeach
@endsection