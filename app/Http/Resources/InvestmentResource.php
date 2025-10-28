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
            'highlight' => $this->whenLoaded('highlight', function () {
                $targetedReturns = json_decode($this->highlight->targeted_returns, true);
                $fees = json_decode($this->highlight->fees, true);

                return [
                    'overview' => $this->highlight->overview,

                    'targeted_returns' => collect($targetedReturns)->map(function ($value, $key) {
                        return ['key' => $key, 'value' => $value];
                    })->values()->all(),

                    'fees' => collect($fees)->map(function ($value, $key) {
                        return ['key' => $key, 'value' => $value];
                    })->values()->all(),
                ];
            }),

            'documents' => $this->whenLoaded('documents', function () {
                return $this->documents->map(fn($doc) => [
                    'name' => $doc->name,
                    'file' => url($doc->file_path),
                ]);
            }),
            'risks' => $this->whenLoaded('risks', function () {
                return [
                    'title'       => $this->risks->title,
                    'description' => $this->risks->description,
                ];
            }),
            'asset_class' => $this->whenLoaded('assetClass', function () {
                return [
                    'name' => $this->assetClass->name,
                    'description' => $this->assetClass->description,
                ];
            }),
            'investment_type' => $this->whenLoaded('investmentType', function () {
                return [
                    'name' => $this->investmentType->name,
                    'description' => $this->investmentType->description,
                ];
            }),
            'investment_strategies' => $this->whenLoaded('strategy', function () {
                return [
                    'name' => $this->strategy->name,
                    'description' => $this->strategy->description,
                ];
            }),

        ];
    }
}
