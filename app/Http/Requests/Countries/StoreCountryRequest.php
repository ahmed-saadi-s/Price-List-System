<?php

namespace App\Http\Requests\Countries;

use Illuminate\Foundation\Http\FormRequest;

class StoreCountryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:10', 'unique:countries,code'],
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
      return [
        'code.required' => __('validation.required_code'),
        'code.unique' => __('validation.unique_code'),
        'code.max' => __('validation.max_code'),
        
        'name.required' => __('validation.required_name'),
        'name.string' => __('validation.string_name'),
        'name.max' => __('validation.max_name'),
    ];
    }
}