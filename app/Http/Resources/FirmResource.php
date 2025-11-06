<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FirmResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                            => $this->id,
            'is_registered'                 => $this->is_registered,
            'firm_crd'                      => $this->firm_crd,
            'individual_crd'                => $this->individual_crd,
            'firm_aum_min'                  => $this->firm_aum_min,
            'firm_aum_max'                  => $this->firm_aum_max,
            'address'                       => $this->address,
            'explanation_if_not_registered' => $this->explanation_if_not_registered,
            'created_at'                    => $this->created_at?->toISOString(),
            'updated_at'                    => $this->updated_at?->toISOString(),
        ];
    }
}
