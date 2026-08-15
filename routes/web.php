<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('welcome');
});

Route::get('/admin/jadwal/', function () {
  return view('admin.jadwal.index');
});
