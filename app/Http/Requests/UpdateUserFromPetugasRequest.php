<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserFromPetugasRequest extends FormRequest
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
            'username' => ['required', Rule::unique(User::class, 'username')->ignore($this->user)],
            'alamat' => 'required',
            'rt' => 'required|string',
            'rw' => 'required|string',
            'tlp' => ['required', 'numeric', Rule::unique(User::class, 'tlp')->ignore($this->user)],
        ];
    }
}
