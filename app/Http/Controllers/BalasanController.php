<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class BalasanController extends Controller
{
    public function index(): View
    {
        return view('keluhan.list-keluhan-petugas');
    }
}
