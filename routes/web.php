<?php

use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MapelController;
use App\Http\Controllers\Admin\MengajarController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Guru\JadwalController as GuruJadwalController;
use App\Http\Controllers\Siswa\JadwalController as SiswaJadwalController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
  return view('welcome');
});

Route::middleware('guest')->group(function () {
  Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
  Route::post('register', [RegisteredUserController::class, 'store']);

  Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
  Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
  Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

  Route::get('/dashboard', function () {
    $user = Auth::user();

    if ($user && $user->role === 'admin') {
      return view('dashboard', ['role' => 'admin']);
    }

    if ($user && $user->role === 'guru') {
      return view('dashboard', ['role' => 'guru']);
    }

    return view('dashboard', ['role' => 'siswa']);
  })->name('dashboard');

  Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('kelas', KelasController::class);
    Route::resource('mapel', MapelController::class);
    Route::resource('mengajar', MengajarController::class);
    Route::resource('jadwal', JadwalController::class);
  });

  Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('jadwal', [GuruJadwalController::class, 'index'])->name('jadwal.index');
  });

  Route::middleware(['role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('jadwal', [SiswaJadwalController::class, 'index'])->name('jadwal.index');
  });
});

Route::get('/test-guru-nilai', function () {
  return view('guru.nilai.form');
});

Route::get('/test-siswa-nilai', function () {
  return view('siswa.nilai.index');
});
