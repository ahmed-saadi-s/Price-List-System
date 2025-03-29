<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Traits\ApiResponse;
use App\Http\Resources\API\ProductCollection;
use App\Http\Resources\API\ProductResource;
use App\Http\Requests\API\ShowProductRequest;
use Illuminate\Http\Request;

class ProductController
{
    use ApiResponse;


    // List all products with their applicable price.

    public function index(Request $request)
    {
        $products = Product::with('priceLists')->orderBy('created_at', 'desc')->get();
        if ($products->isEmpty()) {
            // no products were found
            return $this->successResponse(null, __('messages.no_products_found'));
        }
        return $this->successResponse(new ProductCollection($products), __('messages.products_retrieved_successfully'));
    }
    // Show a single product by its ID with applicable price details.

    public function show(ShowProductRequest $request, $id)
    {
        $product = Product::with('priceLists')->find($id);

        if (!$product) {
            // the product was not found
            return $this->errorResponse(__('messages.product_not_found'), 404);
        }
        return $this->successResponse(new ProductResource($product), __('messages.product_retrieved_successfully'));
    }
}
