<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplianceAcknowledgment extends Model
{
    protected $fillable = [
        'user_id',
        'terms_agreed',
        'terms_agreed_at',
        'privacy_agreed',
        'privacy_agreed_at',
        'investor_acknowledgment',
        'investor_acknowledgment_at',
        'confidentiality_agreed',
        'confidentiality_agreed_at',
        'marketing_opt_in',
        'marketing_opt_in_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'terms_agreed' => 'boolean',
        'privacy_agreed' => 'boolean',
        'investor_acknowledgment' => 'boolean',
        'confidentiality_agreed' => 'boolean',
        'marketing_opt_in' => 'boolean',
        'terms_agreed_at' => 'datetime',
        'privacy_agreed_at' => 'datetime',
        'investor_acknowledgment_at' => 'datetime',
        'confidentiality_agreed_at' => 'datetime',
        'marketing_opt_in_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
