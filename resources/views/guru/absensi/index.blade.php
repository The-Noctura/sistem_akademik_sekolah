@extends('layouts.app')

@section('title', 'Input Absensi')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-semibold text-slate-900">Input Absensi</h1>
    <p class="text-sm text-slate-500 mt-1">Pilih kelas dan mata pelajaran untuk menginput absensi</p>
</div>

@if ($mengajarList->isEmpty())
    <x-card>
        <p class="text-slate-500 text-center py-8">Anda belum memiliki jadwal mengajar.</p>
    </x-card>
@else
    <x-card>
        <x-table>
            <x-slot:head>
                <tr>
                    <th class="text-left px-4 py-3 font-medium">Mapel</th>
                    <th class="text-left px-4 py-3 font-medium">Kelas</th>
                    <th class="text-left px-4 py-3 font-medium">Tahun Ajaran</th>
                    <th class="text-left px-4 py-3 font-medium">Semester</th>
                    <th class="text-left px-4 py-3 font-medium">Aksi</th>
                </tr>
            </x-slot:head>

            @foreach($mengajarList as $mengajar)
            <tr class="hover:bg-surface">
                <td class="px-4 py-3">{{ $mengajar->mapel->nama_mapel }}</td>
                <td class="px-4 py-3">{{ $mengajar->kelas->nama_kelas }}</td>
                <td class="px-4 py-3 text-slate-500">{{ $mengajar->tahun_ajaran }}</td>
                <td class="px-4 py-3 text-slate-500">{{ $mengajar->semester }}</td>
                <td class="px-4 py-3">
                    <a href="{{ route('guru.absensi.form', $mengajar->id) }}"
                       class="text-accent hover:text-accent-hover text-sm font-medium">
                        Input Absensi
                    </a>
                </td>
            </tr>
            @endforeach
        </x-table>
    </x-card>
@endif
@endsection