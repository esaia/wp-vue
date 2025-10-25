<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use NjoguAmos\Turnstile\Rules\TurnstileRule;

class ResetUserPasswordRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed:confirmPassword',
            'cfTurnstileResponse' => ['required', new TurnstileRule()],
        ];
    }


    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cfTurnstileResponse.required' => 'Cloudflare CAPTCHA validation failed.',
        ];
    }
}
