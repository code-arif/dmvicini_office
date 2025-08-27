<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentImage extends Model
{
    protected $fillable = [
        'investment_id',
        'image_url'
    ];
}
