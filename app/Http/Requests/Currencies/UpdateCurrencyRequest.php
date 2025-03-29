<?php

namespace App\Http\Requests\Currencies;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCurrencyRequest extends FormRequest
{
    public function rules(): array
    {
        $currencyId = $this->input('id'); // Get the ID from the form input
        return [
            'code' => ['required', 'string', 'max:10', 'unique:currencies,code,' . $currencyId], // Excluding current currency
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
