@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Daftar Mapel</h1>
            </div>
            <a href="{{ route('admin.mapel.create') }}">
                <x-button variant="primary">Tambah Mapel</x-button>
            </a>
        </div>

        <x-card>
            <x-table>
                <x-slot:head>
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-slate-700">Nama Mapel</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-700">Kode Mapel</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-700">Aksi</th>
                    </tr>
                </x-slot:head>

                @foreach ($mapelList as $mapel)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-slate-800">{{ $mapel->nama_mapel }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $mapel->kode_mapel }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.mapel.edit', $mapel) }}">
                                    <x-button variant="secondary">Edit</x-button>
                                </a>
                                <form action="{{ route('admin.mapel.destroy', $mapel) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus mapel ini?')">
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
