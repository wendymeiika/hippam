<?php
namespace App\Http\Controllers\Android;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Interfaces\auth\AuthServiceInterface;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authServiceInterface)
    {
        $this->authService = $authServiceInterface;
    }

    public function login(Request $request)
    {
        Log::info('masuk route authcontroller/login');
        return $this->authService->login($request->username, $request->password);
    }

    public function logout(Request $request)
    {
        Log::info('masuk authController/logout');
        // return $this->authService->logout($request);
        return response()->json('ok');
        // return Auth::user()->token();
    }
}
