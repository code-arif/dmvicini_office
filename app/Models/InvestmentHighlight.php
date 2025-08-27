<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentHighlight extends Model
{
    protected $fillable = [
        'investment_id',
        'overview',
        'targeted_returns',
        'fees',
    ];

    protected $casts = [
        'targeted_returns' => 'array',
        'fees' => 'array',
    ];
}
