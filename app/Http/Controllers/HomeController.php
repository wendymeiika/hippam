<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('login');
    }

    public function home(Request $request): View
    {
        return view('home', array_merge([
            'info' => Pengumuman::query()->latest()->get(),
        ], $request->user()->role->name == 'admin' ? $this->administrate() : []
        ));
    }

    protected function administrate(): array
    {
        // Menghitung jumlah pelanggan
        $total = User::query()
            ->whereHas('role', fn ($query) => $query->where('name', 'pelanggan'))
            ->count();

        // Menghitung jumlah pelanggan yang sudah membayar
        $sudah = User::query()
            ->whereHas('role', fn ($query) => $query->where('name', 'pelanggan'))
            ->whereHas(
                'pembayarans',
                fn ($query) => $query->where('status', 'success')->where('tahun', now()->year)->where('bulan', Str::padLeft(now()->month, 2, 0))
            )
            ->count();

        // Menghitung jumlah pelanggan yang belum membayar
        $belum = $total - $sudah;

        // Menghitung jumlah petugas
        $jumlahPetugas = User::query()
            ->whereHas('role', fn ($query) => $query->where('name', 'petugas'))
            ->count();

        // Menghitung jumlah ketua RT
        $jumlahKetuaRT = User::query()
            ->whereHas('role', fn ($query) => $query->where('name', 'ketuart'))
            ->count();

        // menghitung jumlah admin
        $jumlahAdmin = User::query()
        ->whereHas('role', fn ($query) => $query->where('name', 'admin'))
        ->count();

        return [
            'total' => $total,
            'sudah' => $sudah,
            'belum' => $belum,
            'jumlahPetugas' => $jumlahPetugas,
            'jumlahKetuaRT' => $jumlahKetuaRT,
            'jumlahAdmin' => $jumlahAdmin,
        ];
    }
}
