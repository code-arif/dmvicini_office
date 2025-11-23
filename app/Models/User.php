<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Cashier\Billable;
use Illuminate\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{

    use HasFactory, Notifiable, Billable, MustVerifyEmail;

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
        'avatar',
        'is_active',
        'reset_password_token',
        'reset_password_token_expire_at',
        'role',
        'otp',
        'otp_expires_at',
        'email_verification_token',
        'email_verification_token_expires_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
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

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get the user's registration attempts
     */
    public function registrationAttempts()
    {
        return $this->hasMany(RegistrationAttempt::class, 'email', 'email');
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for users with full access
     */
    public function scopeFullAccess($query)
    {
        return $query->where('access_level', 'full');
    }

    /**
     * Scope for investors only
     */
    public function scopeInvestors($query)
    {
        return $query->where('role', 'user');
    }

    /**
     * Check if user has full access
     */
    public function hasFullAccess()
    {
        return $this->is_active && $this->access_level === 'full';
    }
}
