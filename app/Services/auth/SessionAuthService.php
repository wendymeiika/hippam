<?php
namespace App\Services\auth;


use App\Models\User;
use Illuminate\Http\Request;
use App\Interfaces\auth\AuthServiceInterface;

class SessionAuthService implements AuthServiceInterface
{
 
    private User $user;
    private $password_client;

    public function login($username, $password)
    {
        $this->user = User::where('username', $username)->orWhere('tlp', $username)->first();
        if ($this->user) {
            if (password_verify($password, $this->user->password)) {
                $this->password_client = $this->user->get_password_client();
                return $this->user;
            }
        }
        return false;
    }

    public function logout(Request $request)
    {
    }
}