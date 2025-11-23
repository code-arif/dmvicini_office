<?php

namespace App\Services;

use App\Models\Firm;
use Illuminate\Support\Str;

class AccessLevelService
{
    /**
     * Determine access level based on firm and payload
     */
    public function determineAccessLevel(Firm $firm, array $payload): string
    {
        // Review Queue - unclear or high-risk
        if ($this->requiresReview($firm, $payload)) {
            return 'review';
        }

        // Limited Access - Not accredited / non-U.S. retail
        if ($this->isLimitedAccess($firm, $payload)) {
            return 'limited';
        }

        // Provisional Full Access - Institutional/RIA/BD with registration
        if ($this->isProvisionalAccess($firm, $payload)) {
            return 'provisional';
        }

        // Default to review
        return 'review';
    }

    private function requiresReview(Firm $firm, array $payload): bool
    {
        // High-risk countries
        $highRiskCountries = ['IR', 'KP', 'SY', 'CU']; // Example
        if (in_array($payload['country'], $highRiskCountries)) {
            return true;
        }

        // No registration but claiming institutional
        if (
            !$firm->is_registered &&
            in_array($payload['investor_type'], ['institutional', 'fund_manager'])
        ) {
            return true;
        }

        // Suspicious patterns
        if ($firm->is_registered && empty($firm->firm_crd)) {
            return true;
        }

        return false;
    }

    private function isLimitedAccess(Firm $firm, array $payload): bool
    {
        // Non-U.S. retail without registration
        $nonUSCountries = !in_array($payload['country'], ['US']);
        $notRegistered = !$firm->is_registered;
        $retailType = in_array($payload['investor_type'], ['other']);

        return $nonUSCountries && $notRegistered && $retailType;
    }

    private function isProvisionalAccess(Firm $firm, array $payload): bool
    {
        // Registered firms
        if ($firm->is_registered && !empty($firm->firm_crd)) {
            return true;
        }

        // Institutional with proper explanation
        if (
            in_array($payload['investor_type'], ['institutional', 'family_office']) &&
            !empty($firm->explanation_if_not_registered)
        ) {
            return true;
        }

        return false;
    }

    /**
     * Generate secure token
     */
    public function generateSecureToken(): string
    {
        return hash_hmac(
            'sha256',
            Str::random(64) . microtime(true) . random_bytes(32),
            config('app.key')
        );
    }
}
