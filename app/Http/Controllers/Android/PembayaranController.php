<?php

namespace App\Http\Controllers\Android;

use App\Http\Controllers\Controller;
use App\Http\Resources\PembayaranRecource;
use App\Models\Pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index() {
        $pembayaranpelanggan = Pembayaran::all();
    
        return PembayaranRecource::collection($pembayaranpelanggan);
    }
}
