<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvestmentDocument extends Model
{
    protected $fillable = [
        'investment_id',
        'name',
        'file_path'
    ];

    public function investment(){
        return $this->belongsTo(Investment::class);
    }
}
