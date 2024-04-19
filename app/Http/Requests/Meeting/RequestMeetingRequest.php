<?php

namespace App\Http\Requests\Meeting;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RequestMeetingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'patient_id' => ['required',Rule::exists('doctors','id')],
            'doctor_id' => ['required',Rule::exists('doctors','id')],
            'topic' => ['required','string'],
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'patient_id' => auth('patient_api')->user()->id,
        ]);
    }
}
