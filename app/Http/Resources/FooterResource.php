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
            'slogan_line' => $this->slogan_line,
            'subscribe_title' => $this->subscribe_title,
            'subscribe_description' => $this->subscribe_description,
            'copyright' => $this->copyright,
            'disclaimer' => $this->disclaimer,
            // 'social_links' => $this->social_links, // already has icon_url
            'social_links' => $this->formatSocialLinks($this->social_links),
        ];
    }


    private function formatSocialLinks($socialLinks)
    {
        if (!$socialLinks) {
            return [];
        }

        $links = is_string($socialLinks)
            ? json_decode($socialLinks, true)
            : $socialLinks;

        return collect($links)->map(function ($link) {
            return [
                'url' => $link['url'] ?? null,
                'icon' => isset($link['icon']) ? asset($link['icon']) : null,
                'platform' => $link['platform'] ?? null,
            ];
        })->toArray();
    }
}
