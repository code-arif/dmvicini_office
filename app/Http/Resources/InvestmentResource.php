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
            'investment_details'         => $this->investment_details,
            'status'          => $this->status,
            'mountain_image'       => $this->mountain_image ? url($this->mountain_image) : null,
            'location'        => [
                'address'  => $this->address,
                'city'     => $this->city,
                'state'    => $this->state,
                'country'  => $this->country,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ],
            'highlight' => $this->whenLoaded('highlight', function () {
                $targetedReturns = json_decode($this->highlight->targeted_returns, true);
                $fees = json_decode($this->highlight->fees, true);

                return [
                    'overview' => $this->highlight->overview,
                    'targeted_irr' => $this->highlight->overview,
                    'tax_doc' => $this->highlight->overview,
                    'investor_waterfall' => $this->highlight->overview,
                    'promoted_interest' => $this->highlight->overview,
                    'asset_management_fee' => $this->highlight->overview,
                    'organizational_and_offering_fee' => $this->highlight->overview,
                    'acquisition_fee' => $this->highlight->overview,
                    'disposition_fee' => $this->highlight->overview,
                    'fund_administration_fee' => $this->highlight->overview,
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
            'tax_strategies' => $this->whenLoaded('tax_strategies', function () {
                return [
                    'name' => $this->tax_strategies->name,
                    'description' => $this->tax_strategies->description,
                ];
            }),
            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(function ($img) {
                    return [
                        'image_url' => $img->image_url ? url($img->image_url) : null,
                    ];
                });
            }),

        ];
    }
}
