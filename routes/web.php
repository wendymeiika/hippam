<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KeluhanController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoleUserController;
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

Route::get('/', [HomeController::class, 'index'])->name('index.login')->middleware('guest');
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'home']);

    // profile
    Route::get('/profile/edit-profile', [UserController::class, 'editProfile']);
    Route::put('/profile/edit-profile', [UserController::class, 'updateProfile']);
    Route::get('/profile/ganti-password', [UserController::class, 'gantiPassword']);
    Route::put('/profile/ganti-password', [UserController::class, 'updatePassword']);

    // keluhan
    Route::get('/keluhan', [KeluhanController::class, 'index'])->name('keluhan.index');
    Route::post('/keluhan/list', [KeluhanController::class, 'keluhanList']);
    Route::post('/keluhan/tambah', [KeluhanController::class, 'tambah']);
    Route::put('/keluhan/update/{id}', [KeluhanController::class, 'update']);
    Route::delete('/keluhan/{id}', [KeluhanController::class, 'delete'])->name('keluhan.destroy');

    // pembayaran
    Route::prefix('pembayaran')->name('pembayaran.')->controller(PembayaranController::class)->group(function () {
        Route::get('/', 'index')->name('index');

        Route::get('list', 'list')->middleware('petugas')->name('list');

        Route::middleware(['roles:pelanggan,ketuart'])->group(function () {
            Route::get('/bayar', 'bayar')->name('bayar');
        });

        Route::post('/bayar', 'bayarHippam')->name('hippam');
        Route::post('/bayar-by-rt', 'bayarHippamByRT')->name('hippamByRT');

        Route::put('validate/{pembayaran}', 'validating')->name('validate');
        // Route::post('/valid/{id}', 'valid')->name('validate');
        // Route::post('/tolak/{id}', 'tolak')->name('reject');
        Route::get('/riwayat', 'riwayat')->name('history');
        Route::post('/riwayat/upload-ulang/{id}', 'uploadUlang')->name('reupload');
    });

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
    });

    // laporan
    Route::post('/laporan/list', [PembayaranController::class, 'listLaporan']);
    Route::get('/laporan', [PembayaranController::class, 'laporan']);
    Route::post('/laporan', [PembayaranController::class, 'laporan']);

    // atur role
    Route::get('role/datatable', [RoleController::class, 'datatable'])->name('role.datatable');
    Route::resource('role', RoleController::class)->only(['index', 'store', 'update', 'destroy']);

    // Atur akses setiap user
    Route::get('/role-user/list', [RoleUserController::class, 'roleList'])->name('role-user.datatable');
    Route::resource('role-user', RoleUserController::class)->only(['index', 'store', 'update', 'destroy']);
    // Route::get('/role', [RoleUserController::class, 'index']);
    // Route::post('/role/tambah', [RoleUserController::class, 'tambahRole']);
    // Route::put('/role/{id}', [RoleUserController::class, 'updateRole'])->name('role.update');
    // Route::delete('/role/delete/{id}', [RoleUserController::class, 'deleteRole']);
});
