<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Firm extends Model
{
    protected $fillable = [
        'profile_id',
        'is_registered',
        'firm_crd',
        'individual_crd',
        'firm_aum',
        'state',
        'city',
        'zip',
        'address',
        'explanation_if_not_registered',
    ];


    public function profile()
    {
        return $this->belongsTo(Profiles::class, 'profile_id');
    }
}
