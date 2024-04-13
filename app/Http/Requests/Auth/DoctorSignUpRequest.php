<?php

namespace App\Http\Requests\Auth;

use App\Models\Doctor;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class DoctorSignUpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'email' => ['required', 'email', 'unique:' . Doctor::class],
            'password' => [
                'required',
                'confirmed',
                    Password::min(8)
                                ->mixedCase()
                                ->numbers()
                                ->symbols()
                ],
            'birth' => ['required', 'date'],
        ];
    }
}
