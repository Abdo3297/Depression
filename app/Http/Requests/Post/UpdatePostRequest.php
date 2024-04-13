<?php

namespace App\Http\Requests\Post;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'text_body' => ['sometimes','string'],
            'image' => ['sometimes','image','mimes:png,jpg','max:90000'],
            'doctor_id'=>['required',Rule::exists('doctors','id')]
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'doctor_id' => auth('doctor_api')->user()->id,
        ]);
    }
}
