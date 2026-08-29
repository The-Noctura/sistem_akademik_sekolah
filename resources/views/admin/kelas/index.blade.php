@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Daftar Kelas</h1>
            </div>
            <a href="{{ route('admin.kelas.create') }}">
                <x-button variant="primary">Tambah Kelas</x-button>
            </a>
        </div>

        <x-card>
            <x-table>
                <x-slot:head>
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-slate-700">Nama Kelas</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-700">Tingkat</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-700">Wali Kelas</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-700">Tahun Ajaran</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-700">Aksi</th>
                    </tr>
                </x-slot:head>

                @foreach ($kelasList as $kelas)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-slate-800">{{ $kelas->nama_kelas }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $kelas->tingkat }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $kelas->waliKelas?->nama ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $kelas->tahun_ajaran }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.kelas.edit', $kelas) }}">
                                    <x-button variant="secondary">Edit</x-button>
                                </a>
                                <form action="{{ route('admin.kelas.destroy', $kelas) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus kelas ini?')">
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
