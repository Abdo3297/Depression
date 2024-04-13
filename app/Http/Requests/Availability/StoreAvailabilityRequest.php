<?php

namespace App\Http\Requests\Availability;

use App\Enums\Availability;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAvailabilityRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $cases = array_map(fn($case) => $case->value, Availability::cases());
        return [
            'day' => ['required', 'string','in:' . implode(',', $cases)],
            'from' => ['required'],
            'to' => ['required'],
            'doctor_id' => ['required',Rule::exists('doctors','id')],
        ];
    }
    protected function prepareForValidation()
    {
        $this->merge([
            'doctor_id' => auth('doctor_api')->user()->id,
        ]);
    }
}
