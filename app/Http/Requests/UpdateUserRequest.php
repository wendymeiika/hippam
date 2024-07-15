<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user(); // Mengambil instance user saat ini dari request

        return [
            'nama' => 'required',
            'username' => [
                'required',
                Rule::unique(User::class, 'username')->ignore($user), // Mengabaikan pengguna saat ini
            ],
            'alamat' => 'required',
            'tlp' => [
                'required',
                'digits:12',
                Rule::unique(User::class, 'tlp')->ignore($user), // Mengabaikan pengguna saat ini
            ],
            'rt' => [
                'required_if:rw,null', // Membutuhkan rt jika rw kosong
                Rule::unique(User::class, 'rt')->ignore($user), // Mengabaikan pengguna saat ini
            ],
            'rw' => [
                'required_if:rt,null', // Membutuhkan rw jika rt kosong
                Rule::unique(User::class, 'rw')->ignore($user), // Mengabaikan pengguna saat ini
            ],
        ];
    }
}
