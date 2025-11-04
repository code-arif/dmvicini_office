<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{

    use HasFactory, Notifiable, Billable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    protected $fillable = [
        'email',
        'password',
        'email_verified_at',
        'access_level',
        'is_active',
        'provisional_expires_at',
        'role',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'provisional_expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function profile()
    {
        return $this->hasOne(Profiles::class);
    }

    public function complianceAcknowledgment()
    {
        return $this->hasOne(ComplianceAcknowledgment::class);
    }

    public function accessRequests()
    {
        return $this->hasMany(AccessRequest::class);
    }
}
