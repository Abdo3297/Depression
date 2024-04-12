<?php

namespace App\Http\Requests\Auth;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CheckOTPatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'email'=>['required','email',Rule::exists('patients','email')],
            'otp'=>['required','min:'.config('depression_constant.LENGTH')]
        ];
    }
}
