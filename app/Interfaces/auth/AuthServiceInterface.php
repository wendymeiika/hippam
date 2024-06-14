<?php
namespace App\Interfaces\auth;

use Illuminate\Http\Request;

interface AuthServiceInterface{
    public function login(string $username,string $password);
    public function logout(Request $request);
}