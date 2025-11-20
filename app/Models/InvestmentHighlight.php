<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentHighlight extends Model
{
    protected $fillable = [
        'investment_id',
        'overview',
        'targeted_irr',
        'tax_doc',
        'investor_waterfall',
        'promoted_interest',
        'asset_management_fee',
        'organizational_and_offering_fee',
        'acquisition_fee',
        'disposition_fee',
        'fund_administration_fee',
    ];

    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }
}
