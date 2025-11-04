<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'title',
        'firm_name',
        'phone',
        'country',
        'investor_type',
        'investor_type_other',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function firm()
    {
        return $this->hasOne(Firm::class);
    }
}
