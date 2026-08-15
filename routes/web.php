<?php

use Illuminate\Support\Facades\Route;

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