<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    protected $fillable = [
        'user_id',
        'asset_class_id',
        'title',
        'sub_title',
        'description',
        'image',
        'status'
    ];

    //reation with asset_class table
    public function asset_class()
    {
        return $this->BelongsTo(AssetClass::class);
    }
}
