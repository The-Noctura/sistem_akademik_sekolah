@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold text-slate-900 mb-6">Tambah Kelas</h1>

        <x-card>
            <form method="POST" action="{{ route('admin.kelas.store') }}">
                @csrf

                <x-form-input name="nama_kelas" label="Nama Kelas" :value="old('nama_kelas')" />
                <x-form-input name="tingkat" label="Tingkat" :value="old('tingkat')" />
                <x-form-select name="wali_kelas_id" label="Wali Kelas" :options="$guruList->pluck('nama', 'id')->toArray()" :selected="old('wali_kelas_id')" />
                <x-form-input name="tahun_ajaran" label="Tahun Ajaran" :value="old('tahun_ajaran')" />

                <div class="flex gap-3 pt-2">
                    <x-button variant="primary" type="submit">Simpan</x-button>
                    <a href="{{ route('admin.kelas.index') }}">
                        <x-button variant="secondary" type="button">Batal</x-button>
                    </a>
                </div>
            </form>
        </x-card>
    </div>
@endsection
