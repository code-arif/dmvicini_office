<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    protected $fillable = [
        'title',
        'asset_class_id',
        'investment_type_id',
        'investments_strategy_id',
        'term',
        'min_investment',
        'targeted_irr',
        'thumbnail',
        'summary',
        'country',
        'city',
        'state',
        'address',
        'map_url',
        'latitude',
        'longitude',
        'banker_phone',
        'banker_email',
        'status',
    ];

    public function assetClass()
    {
        return $this->belongsTo(AssetClass::class);
    }
    public function investmentType()
    {
        return $this->belongsTo(InvestmentTypes::class);
    }
    public function strategy()
    {
        return $this->belongsTo(InvestmentStrategy::class, 'investments_strategy_id');
    }
}
