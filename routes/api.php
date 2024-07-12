<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\KeluhanController;
use App\Http\Controllers\Api\PengumumanController;
use App\Http\Controllers\Api\PembayaranNewController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route for login
Route::post('login', [AuthController::class, 'login']);

// Route for logout (protected by auth:sanctum middleware)
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Routes for Pembayaran API (protected by auth:sanctum middleware)
// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('pembayaran', [PembayaranController::class, 'index']);
//     Route::get('pembayaran/list', [PembayaranController::class, 'list']);
//     Route::post('pembayaran/bayar', [PembayaranController::class, 'bayar']);
//     Route::post('pembayaran/bayar-by-rt', [PembayaranController::class, 'bayarHippamByRT']);
//     Route::post('pembayaran/upload-ulang/{id}', [PembayaranController::class, 'uploadUlang']);
//     Route::get('pembayaran/laporan', [PembayaranController::class, 'laporan']);
//     Route::get('pembayaran/laporan-belum-bayar', [PembayaranController::class, 'laporanBelumBayar']);
//     Route::get('pembayaran/rthistory', [PembayaranController::class, 'rtHistory']);
// });


Route::middleware('auth:sanctum')->group(function () {
    //pembayaran
    Route::get('/pembayaran', [PembayaranNewController::class, 'index']);
    Route::get('/pembayaran/list', [PembayaranNewController::class, 'list']);
    Route::get('/pembayaran/bayar', [PembayaranNewController::class, 'bayar']);
    Route::post('/pembayaran/bayar-hippam', [PembayaranNewController::class, 'bayarHippam']);
    Route::post('/pembayaran/bayar-hippam-rt', [PembayaranNewController::class, 'bayarHippamByRT']);
    Route::post('/pembayaran/validating/{pembayaran}', [PembayaranNewController::class, 'validating']);
    Route::get('/pembayaran/riwayat', [PembayaranNewController::class, 'riwayat']);
    Route::post('/pembayaran/bukti/{pembayaran}', [PembayaranNewController::class, 'bukti']);
    Route::delete('/pembayaran/{pembayaran}', [PembayaranNewController::class, 'destroy']);
    //user
    Route::get('users', [UserController::class, 'userList']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::delete('users/{user}', [UserController::class, 'destroy']);
    Route::put('profile', [UserController::class, 'updateProfile']);
    Route::put('password', [UserController::class, 'updatePassword']);

    //keluhan
    Route::post('/keluhan/tambah', [KeluhanController::class, 'tambah']);
    Route::put('/keluhan/{id}', [KeluhanController::class, 'update']);
    Route::delete('/keluhan/{id}', [KeluhanController::class, 'delete']);
    Route::post('/keluhan/{keluhan}/balas', [KeluhanController::class, 'balas']);
    Route::get('/keluhan', [KeluhanController::class, 'keluhanList']);

    //pengumuman
    Route::get('/pengumuman', [PengumumanController::class, 'list']);
    Route::post('/pengumuman/tambah', [PengumumanController::class, 'tambah']);
    Route::put('/pengumuman/{id}', [PengumumanController::class, 'update']);
    Route::delete('/pengumuman/{id}', [PengumumanController::class, 'delete']);
});
