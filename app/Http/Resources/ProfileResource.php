<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                    => $this->id,
            'first_name'            => $this->first_name,
            'last_name'             => $this->last_name,
            'full_name'             => $this->full_name, // accessor
            'title'                 => $this->title,
            'firm_name'             => $this->firm_name,
            'phone'                 => $this->phone,
            'country'               => $this->country,
            'investor_type'         => $this->investor_type,
            'investor_type_other'   => $this->investor_type_other,
            'created_at'            => $this->created_at?->diffForHumans(),

            // Nested Firm
            'firm' => $this->whenLoaded('firm', fn() => new FirmResource($this->firm)),
        ];
    }
}
