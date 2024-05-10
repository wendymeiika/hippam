<?php

namespace App\Http\Requests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleUserRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nama' => 'required',
            'username' => ['required', Rule::unique(User::class, 'username')->ignore($this->role_user, 'id')],
            'alamat' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'tlp' => ['required', 'numeric', Rule::unique(User::class, 'tlp')->ignore($this->role_user, 'id')],
            'role_id' => ['required', Rule::exists(Role::class, 'id')], // Tambahkan validasi untuk peran
        ];
    }
}
