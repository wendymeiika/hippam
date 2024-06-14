<?php

namespace App\Providers\auth;

use Illuminate\Support\ServiceProvider;
use App\Interfaces\auth\AuthServiceInterface;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AuthServiceInterface::class, function($app){
            $authType = request()->input('auth_type', 'token'); // Default ke 'Token' jika tidak diberikan
            $authServiceClass = "App\\Services\\auth\\" . ucfirst($authType) . "AuthService";

            if (class_exists($authServiceClass)) {
                return new $authServiceClass();
            } else {
                throw new \Exception("Layanan autentikasi tidak ditemukan");
            }
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
