<?php

namespace App\Services;

class PriceService
{
    public function getApplicablePrice($product, $date, $countryCode = null, $currencyCode = null)
    {
        $applicablePrice = $product->priceLists
            ->filter(function ($priceList) use ($date, $countryCode, $currencyCode) {
                // Check if start_date exists and ensure it is less than or equal to the current date
                $validStartDate = !is_null($priceList->start_date) ? $priceList->start_date <= $date : false;

                // Check if end_date exists and ensure it is greater than or equal to the current date
                $validEndDate = !is_null($priceList->end_date) ? $priceList->end_date >= $date : false;

                // The price should be applicable only if both start_date and end_date are valid
                $matchesDate = $validStartDate && $validEndDate;

                // If country is null, then it applies to all
                // If country is specified, search for a match in the price list
                // If price list has no country, it works for all countries
                $matchesCountry = is_null($countryCode) || $priceList->country_code === $countryCode || is_null($priceList->country_code);

                // If currency is null, then it applies to all
                // If currency is specified, search for a match in the price list
                // If price list has no currency, it works for all currencies
                $matchesCurrency = is_null($currencyCode) || $priceList->currency_code === $currencyCode || is_null($priceList->currency_code);

                return $matchesDate && $matchesCountry && $matchesCurrency;
            })
            ->sortBy('priority')
            ->first();

        return [
            'price' => $applicablePrice ? $applicablePrice->price : $product->base_price, // Use applicable price or fallback to base price
            'currency_code' => $applicablePrice ? $applicablePrice->currency_code : 'USD' // Default to 'USD' if no currency is found
        ];
    }
}
