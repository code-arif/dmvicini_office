<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegistrationAttempt extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'email',
        'ip_address',
        'attempted_at',
    ];

    protected $dates = ['attempted_at'];
}
