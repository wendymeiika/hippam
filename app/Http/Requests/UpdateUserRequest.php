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
        return [
            'nama' => 'required',
            'username' => ['required', Rule::unique(User::class, 'username')->ignore($this->user())],
            'alamat' => 'required',
            // 'rt' => 'required',
            // 'rw' => 'required',
            'tlp' => ['required', 'digits:12', Rule::unique(User::class, 'tlp')->ignore($this->user())],
        ];
    }
}
