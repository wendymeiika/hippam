<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Android\AuthController;
use App\Http\Controllers\Android\PembayaranController;

Route::prefix('/v1')->group(function(){
    // rute
    Log::info('masuk route apiv1');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);

    // pelanggan
    Route::get('pembayaran', [PembayaranController::class, 'index']);

});

Route::middleware(['auth:api'])->group(function(){
    // Log::info('masuk route api/logout');
    // Route::get('/logout', [AuthController::class, 'logout']);
    // Route::get('/test', function() {
    //     Log::info('masuk route api/test');
    //     return 'test';
    // });
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
