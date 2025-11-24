<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterestedUser extends Model
{
    protected $fillable = [
        'user_id',
        'investment_id',
        'email',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function investment()
    {
        return $this->belongsTo(Investment::class);
    }
}
