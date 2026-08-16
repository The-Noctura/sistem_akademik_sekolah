@extends('layouts.app')

@section('title', 'Kelola Jadwal')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-xl font-semibold">Kelola Jadwal Pelajaran</h1>
        <x-button variant="primary" onclick="window.location.href='{{ route('admin.jadwal.create') }}'">
            Tambah Jadwal
        </x-button>
    </div>

    <x-table>
        <x-slot:head>
            <tr>
                <th class="text-left px-4 py-3 font-medium">Hari</th>
                <th class="text-left px-4 py-3 font-medium">Jam</th>
                <th class="text-left px-4 py-3 font-medium">Ruangan</th>
                <th class="text-left px-4 py-3 font-medium">Guru</th>
                <th class="text-left px-4 py-3 font-medium">Mapel</th>
                <th class="text-left px-4 py-3 font-medium">Kelas</th>
                <th class="text-left px-4 py-3 font-medium">Aksi</th>
            </tr>
        </x-slot:head>

        @forelse($jadwalList as $jadwal)
            <tr class="hover:bg-surface">
                <td class="px-4 py-3">{{ ucfirst($jadwal->hari) }}</td>
                <td class="px-4 py-3 text-slate-500">
                    {{ substr($jadwal->jam_mulai, 0, 5) }} – {{ substr($jadwal->jam_selesai, 0, 5) }}
                </td>
                <td class="px-4 py-3">{{ $jadwal->ruangan }}</td>
                <td class="px-4 py-3">{{ $jadwal->mengajar->guru->nama ?? '-' }}</td>
                <td class="px-4 py-3">{{ $jadwal->mengajar->mapel->nama_mapel ?? '-' }}</td>
                <td class="px-4 py-3">{{ $jadwal->mengajar->kelas->nama_kelas ?? '-' }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <x-button variant="secondary"
                            onclick="window.location.href='{{ route('admin.jadwal.edit', $jadwal->id) }}'">
                            Edit
                        </x-button>

                        <form method="POST" action="{{ route('admin.jadwal.destroy', $jadwal->id) }}"
                            onsubmit="return confirm('Hapus jadwal ini?');">
                            @csrf
                            @method('DELETE')
                            <x-button variant="danger" type="submit">Hapus</x-button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-4 py-6 text-center text-slate-500">
                    Belum ada jadwal. Klik "Tambah Jadwal" untuk mulai.
                </td>
            </tr>
        @endforelse
    </x-table>
@endsection
