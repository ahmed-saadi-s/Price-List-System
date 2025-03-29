<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Country;
use App\Http\Requests\Countries\StoreCountryRequest;
use App\Http\Requests\Countries\UpdateCountryRequest;

class CountriesController
{

     // Display a listing of the Country.
    public function index()
    {
        $countries = Country::orderBy('id', 'desc')->get();
        return view('dashboard.countries', compact('countries'));
    }

   
     // Store a newly created Country.
     
    public function store(StoreCountryRequest $request)
    {
        $country = Country::create([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
        ]);
        return redirect()->route('dashboard.countries.index')->with('success', __('messages.country_added'));
    }

    
     // Update the specified Country.
     
    public function update(UpdateCountryRequest $request, Country $country)
    {
        $country->update([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
        ]);
    
        return redirect()->route('dashboard.countries.index')->with('success', __('messages.country_updated'));
    }

   
     // Remove the specified Country.
 
    public function destroy(Country $country)
{
    $country->delete();
    return redirect()->route('dashboard.countries.index')->with('success', __('messages.country_deleted'));
}
}
