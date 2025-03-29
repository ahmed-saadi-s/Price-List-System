<?php

namespace App\Http\Requests\API;

use App\Traits\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ShowProductRequest extends FormRequest
{
    use ApiResponse; 

    public function rules(): array
    {
        return [
            'date' => 'nullable|date_format:Y-m-d', 
            'country_code' => 'nullable|string|max:10|exists:countries,code',
            'currency_code' => 'nullable|string|max:10|exists:currencies,code',
        ];
    }

    public function messages(): array
    {
        return [
            'date.date_format' => __('validation.date_format'),
            'country_code.string' => __('validation.string_country_code'),
            'country_code.max' => __('validation.max_country_code'),
            'country_code.exists' => __('validation.exists_country_code'),
            'currency_code.string' => __('validation.string_currency_code'),
            'currency_code.max' => __('validation.max_currency_code'),
            'currency_code.exists' => __('validation.exists_currency_code'),
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->errorResponse(
                __('validation.validation_failed'), 
                422,  
                $validator->errors()  
            )
        );
    }
}