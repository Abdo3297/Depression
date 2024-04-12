<?php

namespace App\Http\Requests\Auth;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CheckOTPDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'email'=>['required','email',Rule::exists('doctors','email')],
            'otp'=>['required','min:'.config('depression_constant.LENGTH')]
        ];
    }
}
