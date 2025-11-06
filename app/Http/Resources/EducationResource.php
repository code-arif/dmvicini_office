<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EducationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'sub_title'   => $this->sub_title,
            'description' => $this->description,
            'image'       => $this->image ? url('/' . $this->image) : null,
            'created_at'  => $this->created_at?->diffForHumans(),
            'updated_at'  => $this->updated_at?->diffForHumans(),
            'category'    => $this->whenLoaded('category', fn() => [
                'id'    => $this->category->id,
                'title' => $this->category->title,
            ]),
        ];
    }
}
