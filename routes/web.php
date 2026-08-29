<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guru\NilaiController; // Import controller Nilai


Route::resource('nilai', NilaiController::class);
// ==========================================
// ROUTE 1: Halaman Utama (Welcome)
// ==========================================
Route::get('/', function () {
    return view('welcome');
});

// ==========================================
// ROUTE 2: Halaman Admin Jadwal (Punya Noctura)
// ==========================================
Route::get('/admin/jadwal/', function () {
    return view('admin.jadwal.index');
});

// ==========================================
// ROUTE 3 & 4: ROUTE TESTING MODUL NILAI (Punya Nabil)
// ==========================================
Route::get('/test-guru-nilai', function () {
    return view('guru.nilai.form');
});

Route::get('/test-siswa-nilai', function () {
    return view('siswa.nilai.index');
});

// ==========================================
// ROUTE DUMMY UNTUK TESTING (Nabil)
// Nanti dihapus setelah Iki setup auth
// ==========================================
Route::get('/login', function () {
    return 'Halaman Login (dummy)';
})->name('login');

Route::post('/logout', function () {
    return 'Logout (dummy)';
})->name('logout');