<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Basic
            'email'      => 'required|email|unique:users,email|max:255',
            'password'   => 'required|string|min:8|confirmed',

            // Profile
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'firm_name'  => 'required|string|max:255',
            'phone'      => 'required|string|max:50',
            'country'    => 'required|string|max:100',

            // Investor Type
            'investor_type'        => 'nullable|string',
            'investor_type_other'  => 'nullable|string|max:255',

            // Firm Details
            'is_registered'                 => 'required|boolean',
            'firm_crd'                      => 'nullable|string',
            'individual_crd'                => 'nullable|string',
            'firm_aum'                      => 'nullable|numeric|min:0',
            'address'                       => 'nullable|string|max:500',
            'city'                          => 'nullable|string|max:100',
            'state'                         => 'nullable|string|max:100',
            'zip'                           => 'nullable|string|max:20',
            'explanation_if_not_registered' => 'required_if:is_registered,0|string|nullable',

            // Compliance
            'terms_agreed'           => 'required|accepted',
            'privacy_agreed'         => 'required|accepted',
            'investor_acknowledgment' => 'required|accepted',
            'confidentiality_agreed' => 'required|accepted',
            'marketing_opt_in'       => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already registered.',
            'terms_agreed.accepted' => 'You must agree to the Terms of Service.',
            'investor_type_other.required_if' => 'Please specify your investor type if selecting "Other".',
            'explanation_if_not_registered.required_if' => 'Please explain why your firm is not registered.',
        ];
    }
}
