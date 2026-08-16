@extends('layouts.app')

@section('title', 'Tambah Jadwal')

@section('content')
    <h1 class="text-xl font-semibold mb-6">Tambah Jadwal Pelajaran</h1>

    <x-card>
        <form method="POST" action="{{ route('admin.jadwal.store') }}">
            @csrf

            <x-form-select name="mengajar_id" label="Guru — Mapel (Kelas)" :options="$mengajarList" />

            <x-form-select name="hari" label="Hari" :options="$hariOptions" />

            <x-form-input name="jam_mulai" label="Jam Mulai" type="time" />
            <x-form-input name="jam_selesai" label="Jam Selesai" type="time" />
            <x-form-input name="ruangan" label="Ruangan" type="text" />

            <div class="flex items-center gap-3 mt-6">
                <x-button variant="primary" type="submit">Simpan Jadwal</x-button>
                <x-button variant="secondary" type="button" onclick="history.back()">Batal</x-button>
            </div>
        </form>
    </x-card>
@endsection
