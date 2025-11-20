<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Investment extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'asset_class_id',
        'investment_type_id',
        'investments_strategy_id',
        'tax_strategie_id',

        'term',
        'min_investment',
        'mountain_image',
        'investment_details',

        'country',
        'city',
        'state',
        'address',
        'latitude',
        'longitude',

        'status',

        'sponsor',
        'fund_name',
        'target_equity',
        'target_raise',
        'launch_date',
        'close_date',
        'property_type',
        'unit_count',
        'market_overview',
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

    public function highlight()
    {
        return $this->hasOne(InvestmentHighlight::class);
    }

    public function documents()
    {
        return $this->hasMany(InvestmentDocument::class);
    }


    public function risks()
    {
        return $this->hasOne(InvestmentDisclaimer::class);
    }


    public function images()
    {
        return $this->hasMany(InvestmentImage::class);
    }
}
