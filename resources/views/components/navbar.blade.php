{{-- 
FILE: resources/views/components/navbar.blade.php
KOMPONEN: Navbar
FUNGSI: Navigasi atas (sementara untuk testing)
--}}

<nav class="border-b border-slate-200 bg-white">
    <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
        <span class="font-semibold text-lg text-slate-900">Sistem Akademik</span>

        {{-- Bagian kanan: jika user login tampilkan nama dan logout --}}
        @auth
        <div class="flex items-center gap-4 text-sm">
            <span class="text-slate-500">{{ auth()->user()->nama ?? 'User' }} · {{ ucfirst(auth()->user()->role ?? '') }}</span>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-slate-500 hover:text-accent transition-colors">Keluar</button>
            </form>
        </div>
        @else
        {{-- Jika belum login, tampilkan link login --}}
        <div>
            <a href="{{ route('login') }}" class="text-sm text-accent hover:underline">Login</a>
        </div>
        @endauth
    </div>
</nav>