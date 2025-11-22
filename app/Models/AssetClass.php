<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetClass extends Model
{
    protected $fillable = [
        "name",
        "asset_type",
        "description",
        "order"
    ];
}
