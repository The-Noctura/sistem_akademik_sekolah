{{-- View Jadwal — untuk role GURU --}}
@extends('layouts.app')

@section('title', 'Jadwal Mengajar')

@section('content')
    <h1 class="text-xl font-semibold mb-6">Jadwal Mengajar</h1>

    <x-table>
        <x-slot:head>
            <tr>
                <th class="text-left px-4 py-3 font-medium">Hari</th>
                <th class="text-left px-4 py-3 font-medium">Jam</th>
                <th class="text-left px-4 py-3 font-medium">Ruangan</th>
                <th class="text-left px-4 py-3 font-medium">Mapel</th>
                <th class="text-left px-4 py-3 font-medium">Kelas</th>
            </tr>
        </x-slot:head>

        @forelse($jadwalList as $jadwal)
            <tr class="hover:bg-surface">
                <td class="px-4 py-3">{{ ucfirst($jadwal->hari) }}</td>
                <td class="px-4 py-3 text-slate-500">
                    {{ \Illuminate\Support\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                    &ndash;
                    {{ \Illuminate\Support\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                </td>
                <td class="px-4 py-3">{{ $jadwal->ruangan }}</td>
                <td class="px-4 py-3">{{ $jadwal->mengajar->mapel->nama_mapel }}</td>
                <td class="px-4 py-3">{{ $jadwal->mengajar->kelas->nama_kelas }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-4 py-6 text-center text-slate-500">
                    Belum ada jadwal mengajar.
                </td>
            </tr>
        @endforelse
    </x-table>
@endsection
