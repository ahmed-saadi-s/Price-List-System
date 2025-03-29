<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'base_price' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99', // decimal(8, 2) max value
                'regex:/^\d{1,8}(\.\d{1,2})?$/'
            ],
            'description' => ['nullable', 'string'],
            'price_lists' => ['nullable', 'array'],
            'price_lists.*.country_code' => ['nullable', 'string', 'exists:countries,code'],
            'price_lists.*.currency_code' => ['nullable', 'string', 'exists:currencies,code'],
            'price_lists.*.price' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
                'regex:/^\d{1,8}(\.\d{1,2})?$/'
            ],
            'price_lists.*.start_date' => [
                'nullable',
                'date',
                'required_with:price_lists.*.end_date'
            ],
            'price_lists.*.end_date' => [
                'nullable',
                'date',
                'after_or_equal:price_lists.*.start_date',
                'required_with:price_lists.*.start_date'
            ],
            'price_lists.*.priority' => ['nullable', 'integer', 'in:0,1,2'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('validation.required_name'),
            'name.string' => __('validation.string_name'),
            'name.max' => __('validation.max_name'),
            
            'base_price.required' => __('validation.required_base_price'),
            'base_price.numeric' => __('validation.numeric_base_price'),
            'base_price.min' => __('validation.min_base_price'),
            'base_price.max' => __('validation.max_base_price'),
            'base_price.regex' => __('validation.regex_base_price'),
            
            'description.string' => __('validation.string_description'),
            
            'price_lists.*.country_code.exists' => __('validation.exists_country_code'),
            'price_lists.*.currency_code.exists' => __('validation.exists_currency_code'),
            
            'price_lists.*.price.required' => __('validation.required_price'),
            'price_lists.*.price.numeric' => __('validation.numeric_price'),
            'price_lists.*.price.min' => __('validation.min_price'),
            'price_lists.*.price.max' => __('validation.max_price'),
            'price_lists.*.price.regex' => __('validation.regex_price'),
            
            'price_lists.*.start_date.date' => __('validation.date_start_date'),
            'price_lists.*.start_date.required_with' => __('validation.required_with_start_date'),
            
            'price_lists.*.end_date.date' => __('validation.date_end_date'),
            'price_lists.*.end_date.after_or_equal' => __('validation.after_or_equal_end_date'),
            'price_lists.*.end_date.required_with' => __('validation.required_with_end_date'),
            
            'price_lists.*.priority.integer' => __('validation.integer_priority'),
            'price_lists.*.priority.in' => __('validation.in_priority'),
        ];
    }
}