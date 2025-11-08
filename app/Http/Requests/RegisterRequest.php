<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Basic Info
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'title' => ['nullable', 'string', 'max:100'],
            'firm_name' => ['required', 'string', 'max:200'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string'], // E.164 format
            'country' => ['required', 'string'], // ISO 3166-1 alpha-2
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            // Firm Registration
            'is_registered' => ['required', 'boolean'],
            'firm_crd' => ['required_if:is_registered,true', 'nullable', 'string', 'max:50'],
            'individual_crd' => ['nullable', 'string', 'max:50'],
            'firm_aum' => ['nullable', 'integer', 'min:0'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'zip' => ['nullable', 'integer', 'min:0'],
            'explain_not_registered' => ['required_if:is_registered,false', 'nullable', 'string', 'max:1000'],

            // Investor Type
            'investor_type' => ['nullable', 'string'],
            'investor_type_other' => ['nullable', 'string'],

            // Compliance (all required except marketing)
            'terms_agreed' => ['required', 'accepted'],
            'privacy_agreed' => ['required', 'accepted'],
            'investor_acknowledgment' => ['required', 'accepted'],
            'confidentiality_agreed' => ['required', 'accepted'],
            'marketing_opt_in' => ['nullable', 'boolean'],
        ];
    }
}
