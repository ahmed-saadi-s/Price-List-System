<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8'],
        ];
    }
    public function messages(): array
    {
        return [
            'username.exists' => __('validation.username_not_registered'),

            'password.required' => __('validation.password_required'),
            'password.min' => __('validation.password_min'),
        ];
    }
}
