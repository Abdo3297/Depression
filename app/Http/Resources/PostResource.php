<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->whenHas('id'),
            'text_body' => $this->whenHas('text_body'),
            'post_image' => $this->whenHas($this->getFirstMediaUrl('post_image')),
            'post_created' => $this->whenHas('created_at'),
            'doctor'=>DoctorResource::make($this->whenLoaded('doctor')),
        ];
    }
}
