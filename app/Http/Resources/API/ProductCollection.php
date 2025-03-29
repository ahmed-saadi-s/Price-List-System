<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Services\PriceService;

class ProductCollection extends ResourceCollection
{
    protected $priceService;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->priceService = new PriceService();
    }

    public function toArray(Request $request): array
    {
        $products = $this->collection->map(function ($product) use ($request) {
            $date = now()->toDateString();
            $applicablePriceData = $this->priceService->getApplicablePrice($product, $date);

            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'created_at' => $product->created_at->toDateString(),
                'applicable_price' => $applicablePriceData['price'],
                'currency_code' => $applicablePriceData['currency_code'] ?? 'USD',
            ];
        });

        $order = $request->query('order');
        if ($order === 'lowest-to-highest') {
            $products = $products->sortBy('applicable_price');
        } elseif ($order === 'highest-to-lowest') {
            $products = $products->sortByDesc('applicable_price');
        }

        return $products->values()->toArray();
    }
}
