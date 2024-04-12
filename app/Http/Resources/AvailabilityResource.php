<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvailabilityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->whenHas('id'),
            'day' => $this->whenHas('day'),
            'from' => $this->whenHas('from'),
            'to' => $this->whenHas('to'),
        ];
    }
}
