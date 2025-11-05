<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Profiler\Profile;

class Firm extends Model
{
    protected $fillable = [
        'profile_id',
        'is_registered',
        'firm_crd',
        'individual_crd',
        'firm_aum_min',
        'firm_aum_max',
        'address',
        'explanation_if_not_registered',
    ];

    // Boolean cast
    protected $casts = [
        'is_registered' => 'boolean',
        'firm_aum_min' => 'integer',
        'firm_aum_max' => 'integer',
    ];

    public function profile()
    {
        return $this->belongsTo(Profiles::class, 'profile_id');
    }
}
