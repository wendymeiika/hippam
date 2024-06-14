<?php

namespace App\Services\auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\auth\AuthServiceInterface;

class TokenAuthService implements AuthServiceInterface
{
    private User $user;
    private $password_client;

    public function __construct()
    {
        $this->user = new User();
        $this->password_client = $this->user->get_password_client();
    }

    public function login(string $username, string $password)
    {
        $user = $this->user->where('username', $username)->orWhere('tlp', $username)->first();
        if (!$user) {
            return response()->json(['message' => 'Username atau password tidak valid'], Response::HTTP_UNAUTHORIZED);
        }

        // Pastikan bahwa pengguna yang mencoba masuk benar-benar terautentikasi
        if (!password_verify($password, $user->password)) {
            return response()->json(['message' => 'Username atau password tidak valid'], Response::HTTP_UNAUTHORIZED);
        }

        // Mengonfigurasi properti klien OAuth
        $client_properties = [
            'grant_type' => 'password',
            'client_id' => $this->password_client->id,
            'client_secret' => $this->password_client->secret,
            'username' => $username,
            'password' => $password,
            'role' => $user->role ?? "petugas"
        ];

        // Menambahkan properti klien OAuth ke permintaan
        $request = Request::create('oauth/token', 'POST', $client_properties);
        $response = app()->handle($request);

        // Mengembalikan respons sesuai dengan hasil
        if ($response->getStatusCode() == Response::HTTP_OK) {
            // Jika berhasil, tambahkan data pengguna ke respons
            $decoded_response = json_decode($response->getContent(), true);
            $decoded_response['user'] = $user;
            return response()->json($decoded_response);
        } else {
            // Jika gagal, kembalikan pesan kesalahan
            return response()->json(['message' => 'Username atau password tidak valid'], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        // $request->user()->token()->revoke();
        
    }
}
