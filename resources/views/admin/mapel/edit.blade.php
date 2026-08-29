@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold text-slate-900 mb-6">Edit Mapel</h1>

        <x-card>
            <form method="POST" action="{{ route('admin.mapel.update', $mapel) }}">
                @csrf
                @method('PUT')

                <x-form-input name="nama_mapel" label="Nama Mapel" :value="old('nama_mapel', $mapel->nama_mapel)" />
                <x-form-input name="kode_mapel" label="Kode Mapel" :value="old('kode_mapel', $mapel->kode_mapel)" />

                <div class="flex gap-3 pt-2">
                    <x-button variant="primary" type="submit">Simpan</x-button>
                    <a href="{{ route('admin.mapel.index') }}">
                        <x-button variant="secondary" type="button">Batal</x-button>
                    </a>
                </div>
            </form>
        </x-card>
    </div>
@endsection
