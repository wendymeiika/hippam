<?php

namespace App\Http\Requests;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoleUserRequest extends FormRequest
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
            'username' => 'required|unique:user',
            'alamat' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'tlp' => 'required|digits:12|unique:user',
            'role_id' => ['required', Rule::exists(Role::class, 'id')],
        ];
    }
}
