<?php

namespace App\Http\Requests\Prediction;

use App\Enums\QuestionChoice;
use Illuminate\Foundation\Http\FormRequest;

class PredictionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        $cases = array_map(fn($case) => $case->value, QuestionChoice::cases());
        return [
            "interest-q1" => ['required', 'string','in:' . implode(',', $cases)],
            "interest-q2" => ['required', 'string','in:' . implode(',', $cases)],
            "interest-q3" => ['required', 'string','in:' . implode(',', $cases)],
            "interest-q4" => ['required', 'string','in:' . implode(',', $cases)],
            "interest-q5" => ['required', 'string','in:' . implode(',', $cases)],
            "interest-q6" => ['required', 'string','in:' . implode(',', $cases)],
            "interest-q7" => ['required', 'string','in:' . implode(',', $cases)],
            "interest-q8" => ['required', 'string','in:' . implode(',', $cases)],
            "interest-q9" => ['required', 'string','in:' . implode(',', $cases)],
            "text_input" => ['required', 'string'],
        ];
    }
}
