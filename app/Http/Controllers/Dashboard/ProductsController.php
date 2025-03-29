<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Product;
use App\Models\Country;
use App\Models\Currency;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;

class ProductsController
{
    // Display a listing of the Product.
    public function index()
    {
        $products = Product::with('priceLists')->orderBy('created_at', 'desc')->get();
        $countries = Country::get();
        $currencies = Currency::get();
        return view('dashboard.products', compact('products', 'countries', 'currencies'));
    }

    // Store a newly created Product.
    public function store(StoreProductRequest $request)
    {
        $product = Product::create([
            'name' => $request->input('name'),
            'base_price' => $request->input('base_price'),
            'description' => $request->input('description'),
        ]);

        if ($request->has('price_lists')) {
            foreach ($request->input('price_lists') as $priceListData) {
                if (!empty($priceListData['price'])) {
                    $product->priceLists()->create([
                        'country_code' => $priceListData['country_code'] ?? null,
                        'currency_code' => $priceListData['currency_code'] ?? null,
                        'price' => $priceListData['price'],
                        'start_date' => $priceListData['start_date'] ?? null,
                        'end_date' => $priceListData['end_date'] ?? null,
                        'priority' => $priceListData['priority'] ?? 1, // Default is 1 (Normal)
                    ]);
                }
            }
        }

        return redirect()->route('dashboard.products.index')->with('success', __('messages.product_added'));
    }

    // Update the specified Product.
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update([
            'name' => $request->input('name'),
            'base_price' => $request->input('base_price'),
            'description' => $request->input('description'),
        ]);

        // Always delete old price lists to ensure deleted entries from the modal are removed from the database
        $product->priceLists()->delete();

        if ($request->has('price_lists')) {
            foreach ($request->input('price_lists') as $priceListData) {
                if (!empty($priceListData['price'])) {
                    $product->priceLists()->create([
                        'country_code' => $priceListData['country_code'] ?? null,
                        'currency_code' => $priceListData['currency_code'] ?? null,
                        'price' => $priceListData['price'],
                        'start_date' => $priceListData['start_date'] ?? null,
                        'end_date' => $priceListData['end_date'] ?? null,
                        'priority' => $priceListData['priority'] ?? 1, // Default is 1 (Normal)
                    ]);
                }
            }
        }

        return redirect()->route('dashboard.products.index')->with('success', __('messages.product_updated'));
    }

    // Remove the specified Product.
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('dashboard.products.index')->with('success', __('messages.product_deleted'));
    }
}
