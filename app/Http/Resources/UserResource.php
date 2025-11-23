<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'email'      => $this->email,
            'role'       => $this->role,
            'avatar'     => $this->avatar
                ? asset('/' . $this->avatar)
                : asset('default/profile.jpg'),
            'access_level' => $this->access_level,
            'created_at' => $this->created_at?->diffForHumans(),

            // Correct: Use ProfileResource with loaded firm
            'profile' => $this->whenLoaded('profile', fn() => new ProfileResource($this->profile)),
        ];
    }
}
