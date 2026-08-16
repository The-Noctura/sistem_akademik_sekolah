@extends('layouts.app')

@section('title', 'Edit Jadwal')

@section('content')
    <h1 class="text-xl font-semibold mb-6">Edit Jadwal Pelajaran</h1>

    <x-card>
        <form method="POST" action="{{ route('admin.jadwal.update', $jadwal->id) }}">
            @csrf
            @method('PUT')

            <x-form-select name="mengajar_id" label="Guru — Mapel (Kelas)" :options="$mengajarList" :selected="$jadwal->mengajar_id" />

            <x-form-select name="hari" label="Hari" :options="$hariOptions" :selected="$jadwal->hari" />

            <x-form-input name="jam_mulai" label="Jam Mulai" type="time" :value="substr($jadwal->jam_mulai, 0, 5)" />
            <x-form-input name="jam_selesai" label="Jam Selesai" type="time" :value="substr($jadwal->jam_selesai, 0, 5)" />
            <x-form-input name="ruangan" label="Ruangan" type="text" :value="$jadwal->ruangan" />

            <div class="flex items-center gap-3 mt-6">
                <x-button variant="primary" type="submit">Simpan Perubahan</x-button>
                <x-button variant="secondary" type="button" onclick="history.back()">Batal</x-button>
            </div>
        </form>
    </x-card>
@endsection
