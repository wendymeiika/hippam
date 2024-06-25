<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UpdateUserPasswordRequest extends FormRequest
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
    // public function rules(): array
    // {
    //     return [
    //         'old_password' => [
    //             'required',
    //             fn (string $attribute, mixed $value, Closure $fail) => Hash::check(
    //                 $value,
    //                 $this->user()->password
    //             ) ? $fail('Password lama tidak valid.') : true,
    //         ],
    //         'password' => ['required', 'confirmed', Password::min(8)/* ->letters()->mixedCase()->numbers()->symbols()->uncompromised() */],
    //     ];
    // }

    public function rules(): array
{
    return [
        'old_password' => [
            'required',
            function (string $attribute, mixed $value, Closure $fail) {
                if (!Hash::check($value, $this->user()->password)) {
                    return $fail('Password lama tidak valid.');
                }
            },
        ],
        'password' => [
            'required',
            'confirmed',
            Password::min(8)
            // ->letters()
            // ->mixedCase()
            // ->numbers()
            // ->symbols()
            // ->uncompromised()
        ],
    ];
}

}
