@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold text-slate-900 mb-6">Edit User</h1>

        <x-card>
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <x-form-input name="nama" label="Nama" :value="old('nama', $user->nama)" />
                <x-form-input name="email" label="Email" type="email" :value="old('email', $user->email)" />
                <x-form-input name="password" label="Password Baru" type="password" />

                <x-form-select name="role" label="Role" :options="['admin' => 'Admin', 'guru' => 'Guru', 'siswa' => 'Siswa']" :selected="old('role', $user->role)" />

                <div class="flex gap-3 pt-2">
                    <x-button variant="primary" type="submit">Simpan</x-button>
                    <a href="{{ route('admin.users.index') }}">
                        <x-button variant="secondary" type="button">Batal</x-button>
                    </a>
                </div>
            </form>
        </x-card>
    </div>
@endsection
