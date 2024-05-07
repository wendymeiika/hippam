<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KeluhanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('index.login');
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'home']);

    // profile
    Route::get('/profile/edit-profile', [UserController::class, 'editProfile']);
    Route::put('/profile/edit-profile', [UserController::class, 'updateProfile']);
    Route::get('/profile/ganti-password', [UserController::class, 'gantiPassword']);
    Route::put('/profile/ganti-password', [UserController::class, 'updatePassword']);

    // keluhan
    Route::get('/keluhan', [KeluhanController::class, 'index']);
    Route::post('/keluhan/list', [KeluhanController::class, 'keluhanList']);
    Route::post('/keluhan/tambah', [KeluhanController::class, 'tambah']);
    Route::put('/keluhan/update/{id}', [KeluhanController::class, 'update']);
    Route::delete('/keluhan/delete/{id}', [KeluhanController::class, 'delete']);

    // pembayaran
    Route::get('/pembayaran', [PembayaranController::class, 'index']);

    Route::middleware(['roles:pelanggan,ketuart'])->group(function () {
        Route::get('/pembayaran/bayar', [PembayaranController::class, 'bayar']);
    });

    Route::post('/pembayaran/bayar', [PembayaranController::class, 'bayarHippam']);
    Route::post('/pembayaran/bayar-by-rt', [PembayaranController::class, 'bayarHippamByRT']);
    Route::post('/pembayaran/valid/{id}', [PembayaranController::class, 'valid']);
    Route::post('/pembayaran/tolak/{id}', [PembayaranController::class, 'tolak']);
    Route::get('/pembayaran/riwayat', [PembayaranController::class, 'riwayat']);
    Route::post('/pembayaran/riwayat/upload-ulang/{id}', [PembayaranController::class, 'uploadUlang']);

    // notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index']);
    Route::post('/notifikasi/list', [NotifikasiController::class, 'list']);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(['petugas'])->group(function () {
        // user
        Route::get('/user', [UserController::class, 'user']);
        Route::post('/user/list', [UserController::class, 'userList']);
        Route::post('/user/tambah', [UserController::class, 'tambahUser']);
        Route::put('/user/update/{id}', [UserController::class, 'updateUser']);
        Route::delete('/user/delete/{id}', [UserController::class, 'deleteUser']);

        // pengumuman
        Route::get('/pengumuman', [PengumumanController::class, 'index']);
        Route::post('/pengumuman/list', [PengumumanController::class, 'list']);
        Route::post('/pengumuman/tambah', [PengumumanController::class, 'tambah']);
        Route::put('/pengumuman/update/{id}', [PengumumanController::class, 'update']);
        Route::delete('/pengumuman/delete/{id}', [PengumumanController::class, 'delete']);

        // pembayaran
        Route::post('/pembayaran/list', [PembayaranController::class, 'list']);

        // laporan
        Route::post('/laporan/list', [PembayaranController::class, 'listLaporan']);
        Route::get('/laporan', [PembayaranController::class, 'laporan']);
        Route::post('/laporan', [PembayaranController::class, 'laporan']);

    });

});
