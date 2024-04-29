<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestMeetingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            "patient_id"=> $this->patient_id,
            "doctor_id"=> $this->doctor_id,
            "topic"=> $this->topic,
            "status"=> $this->status,
        ];
    }
}
