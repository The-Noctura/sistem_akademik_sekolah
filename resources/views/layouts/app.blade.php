<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Akademik')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    {{-- SEMENTARA: Pakai CDN karena Vite belum setup --}}
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body class="bg-white text-slate-900 font-sans">
    @include('components.navbar')

    <main class="max-w-6xl mx-auto px-4 py-8">
        @if (session('success'))
            <x-alert type="success" :message="session('success')" />
        @endif
        @if ($errors->any())
            <x-alert type="error" :message="$errors->first()" />
        @endif

        @yield('content')
    </main>
</body>

</html>
