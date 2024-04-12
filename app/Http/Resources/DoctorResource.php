<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $defaultImage =env("APP_URL").'/default/profile.jpeg';
        return [
            'id' => $this->whenHas('id'),
            'name' => $this->whenHas('name'),
            'email' => $this->whenHas('email'),
            'birth' => $this->whenHas('birth'),
            'profile_image' => $this->getFirstMediaUrl('doctor_profile')?:$defaultImage,
            'posts'=>PostResource::collection($this->whenLoaded('posts')),
            'availabilities'=>AvailabilityResource::collection($this->whenLoaded('availabilities')),
        ];
    }
}
