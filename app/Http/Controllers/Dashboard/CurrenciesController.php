<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Currency;
use App\Http\Requests\Currencies\StoreCurrencyRequest;
use App\Http\Requests\Currencies\UpdateCurrencyRequest;

class CurrenciesController
{

    // Display a listing of the Currency.
    public function index()
    {
        $currencies = Currency::orderBy('id', 'desc')->get();
        return view('dashboard.currencies', compact('currencies'));
    }

    // Store a newly created Currency.
    public function store(StoreCurrencyRequest $request)
    {
        $currency = Currency::create([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
        ]);
        return redirect()->route('dashboard.currencies.index')->with('success', __('messages.currency_added'));
    }

    // Update the specified Currency.
    public function update(UpdateCurrencyRequest $request, Currency $currency)
    {
        $currency->update([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
        ]);

        return redirect()->route('dashboard.currencies.index')->with('success', __('messages.currency_updated'));
    }

    // Remove the specified Currency.
    public function destroy(Currency $currency)
    {
        $currency->delete();
        return redirect()->route('dashboard.currencies.index')->with('success', __('messages.currency_deleted'));
    }
}
