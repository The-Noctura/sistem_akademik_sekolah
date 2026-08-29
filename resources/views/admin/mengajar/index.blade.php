@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Daftar Mengajar</h1>
            </div>
            <a href="{{ route('admin.mengajar.create') }}">
                <x-button variant="primary">Tambah Mengajar</x-button>
            </a>
        </div>

        <x-card>
            <x-table>
                <x-slot:head>
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-slate-700">Nama Guru</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-700">Mapel</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-700">Kelas</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-700">Tahun Ajaran</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-700">Semester</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-700">Aksi</th>
                    </tr>
                </x-slot:head>

                @foreach ($mengajarList as $item)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-slate-800">{{ $item->guru?->nama ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->mapel?->nama_mapel ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->kelas?->nama_kelas ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->tahun_ajaran }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->semester }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.mengajar.edit', $item) }}">
                                    <x-button variant="secondary">Edit</x-button>
                                </a>
                                <form action="{{ route('admin.mengajar.destroy', $item) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus data mengajar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="danger" type="submit">Hapus</x-button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
    </div>
@endsection
