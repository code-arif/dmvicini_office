<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationAttempt extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'email',
        'ip_address',
        'attempted_at',
    ];

    protected $casts = [
        'attempted_at' => 'datetime',
    ];
}
