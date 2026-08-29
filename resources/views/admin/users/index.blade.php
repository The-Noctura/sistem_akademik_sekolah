@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Daftar User</h1>
            </div>
            <a href="{{ route('admin.users.create') }}">
                <x-button variant="primary">Tambah User</x-button>
            </a>
        </div>

        <x-card>
            <x-table>
                <x-slot:head>
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-slate-700">Nama</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-700">Email</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-700">Role</th>
                        <th class="text-left px-4 py-3 font-medium text-slate-700">Aksi</th>
                    </tr>
                </x-slot:head>

                @foreach ($users as $user)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 text-slate-800">{{ $user->nama }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <x-badge variant="default">{{ ucfirst($user->role) }}</x-badge>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}">
                                    <x-button variant="secondary">Edit</x-button>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus user ini?')">
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
