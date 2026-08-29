@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold text-slate-900 mb-6">Tambah Data Mengajar</h1>

        <x-card>
            <form method="POST" action="{{ route('admin.mengajar.store') }}">
                @csrf

                <x-form-select name="guru_id" label="Guru" :options="$guruList->pluck('nama', 'id')->toArray()" :selected="old('guru_id')" />

                <x-form-select name="mapel_id" label="Mapel" :options="$mapelList->pluck('nama_mapel', 'id')->toArray()" :selected="old('mapel_id')" />

                <x-form-select name="kelas_id" label="Kelas" :options="$kelasList->pluck('nama_kelas', 'id')->toArray()" :selected="old('kelas_id')" />

                <x-form-input name="tahun_ajaran" label="Tahun Ajaran" :value="old('tahun_ajaran')" />
                <x-form-input name="semester" label="Semester" :value="old('semester')" />

                <div class="flex gap-3 pt-2">
                    <x-button variant="primary" type="submit">Simpan</x-button>
                    <a href="{{ route('admin.mengajar.index') }}">
                        <x-button variant="secondary" type="button">Batal</x-button>
                    </a>
                </div>
            </form>
        </x-card>
    </div>
@endsection
