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
            'phone' => ['required', 'string', 'regex:/^\+[1-9]\d{1,14}$/'], // E.164
            'country' => ['required', 'string', 'size:2'], // ISO 3166-1 alpha-2
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            // Firm Registration
            'is_registered' => ['required', 'boolean'],
            'firm_crd' => ['required_if:is_registered,true', 'nullable', 'string', 'max:50'],
            'individual_crd' => ['nullable', 'string', 'max:50'],
            'firm_aum_min' => ['nullable', 'integer', 'min:0'],
            'firm_aum_max' => ['nullable', 'integer', 'min:0', 'gte:firm_aum_min'],
            'address' => ['nullable', 'string', 'max:500'],
            'explain_not_registered' => ['required_if:is_registered,false', 'nullable', 'string', 'max:1000'],

            // Investor Type
            'investor_type' => ['required', Rule::in([
                'ria_adviser',
                'broker_dealer',
                'family_office',
                'institutional',
                'fund_manager',
                'other'
            ])],
            'investor_type_other' => ['required_if:investor_type,other', 'nullable', 'string', 'max:200'],

            // Compliance (all required except marketing)
            'terms_agreed' => ['required', 'accepted'],
            'privacy_agreed' => ['required', 'accepted'],
            'investor_acknowledgment' => ['required', 'accepted'],
            'confidentiality_agreed' => ['required', 'accepted'],
            'marketing_opt_in' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Phone number must be in E.164 format (e.g., +1234567890)',
            'country.size' => 'Country code must be 2 characters (ISO 3166-1 alpha-2)',
            'firm_crd.required_if' => 'Firm CRD is required for registered firms',
            'explain_not_registered.required_if' => 'Please explain your firm registration status',
            'investor_type_other.required_if' => 'Please specify your investor type',
            '*.accepted' => 'You must agree to all compliance requirements',
        ];
    }
}
