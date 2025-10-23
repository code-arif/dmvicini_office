<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvestmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'title'           => $this->title,
            'term'            => $this->term,
            'min_investment'  => $this->min_investment,
            'targeted_irr'    => $this->targeted_irr,
            'targeted_eps'    => $this->targeted_eps,
            'summary'         => $this->summary,
            'status'          => $this->status,
            'thumbnail'       => $this->thumbnail ? url($this->thumbnail) : null,
            'p_strategy'      => optional($this->strategy)->name,
            'asset_class'     => optional($this->assetClass)->name,
            'investment_type' => optional($this->investmentType)->name,
            'location'        => [
                'address'  => $this->address,
                'city'     => $this->city,
                'state'    => $this->state,
                'country'  => $this->country,
                'map_url'  => $this->map_url,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ],
            'banker' => [
                'phone' => $this->banker_phone,
                'email' => $this->banker_email,
            ],
            'highlights' => $this->whenLoaded('highlights', function () {
                return $this->highlights->map(fn($h) => [
                    'overview'        => $h->overview,
                    'targeted_returns' => $h->targeted_returns,
                    'fees'            => $h->fees,
                ]);
            }),
            'documents' => $this->whenLoaded('documents', function () {
                return $this->documents->map(fn($doc) => [
                    'name' => $doc->name,
                    'file' => url($doc->file_path),
                ]);
            }),
            'risks' => $this->whenLoaded('risks', function () {
                return $this->risks->map(fn($r) => [
                    'title'       => $r->title,
                    'description' => $r->description,
                    'risk_level'  => $r->risk_level,
                ]);
            }),
        ];
    }
}
