<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'role' => $this->role,
            'avatar' => $this->avatar ? asset('' . $this->avatar) : asset('default/default_image.jpg'),
            'created_at' => $this->created_at ? $this->created_at->diffForHumans() : null,
        ];
    }
}
