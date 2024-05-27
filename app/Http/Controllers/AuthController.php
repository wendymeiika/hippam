<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $user = User::where('username', $request->username)->orWhere('tlp', $request->username)->first();

            if ($user) {
                // if (Hash::check($request->password, $user->password)) {
                Auth::login($user);

                return redirect()->intended('home');
                // } else {
                //     return back()->with('error', 'Password salah');
                // }
            } else {
                return back()->with('error', 'Akun belum terdaftar');
            }
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/');
        } catch (Exception $e) {
            return view('error');
            dd($e->getMessage());
        }
    }
}
