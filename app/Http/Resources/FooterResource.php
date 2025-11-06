<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FooterResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'logo' => asset($this->logo), // full URL
            'slogan_line1' => $this->slogan_line1,
            'slogan_line2' => $this->slogan_line2,
            'subscribe_title' => $this->subscribe_title,
            'subscribe_description' => $this->subscribe_description,
            'copyright' => $this->copyright,
            'disclaimer' => $this->disclaimer,
            'social_links' => $this->social_links, // already has icon_url
            'footer_links' => $this->footer_links,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->format('Y-m-d H:i:A'),
        ];
    }
}
