<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('login');
    }

    public function home(): View
    {
        return view('home', [
            'info' => Pengumuman::query()->orderBy('id', 'desc')->get(),
        ]);
    }
}
