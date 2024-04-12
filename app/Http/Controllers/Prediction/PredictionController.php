<?php

namespace App\Http\Controllers\Prediction;

use App\Models\Patient;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\Prediction\PredictionRequest;

class PredictionController extends Controller
{
    public function predict(PredictionRequest $request)
    {
        $data = $request->validated();
        $response = Http::post(config("depression_constant.FLASK"), $data);
        if ($response->successful()) {
            $responseData = json_decode($response->body(), true);
            $result = $responseData['result'] ?? null;
            $patient = auth('patient_api')->user();
            $patient = Patient::find($patient->id);
            $patient->update([
                'result' => $result
            ]);
            return $this->okResponse('model result based on patient answers', ['result' => $result]);

        } else {
            return response()->json(['error' => 'Failed to fetch data from Flask API'], 500);
        }
    }
}
