<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $defaultImage = env("APP_URL") . '/default/profile.jpeg';
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'birth' => $this->birth,
            'profile_image' => $this->getFirstMediaUrl('patient_profile') ?: $defaultImage,
        ];
    }
}
