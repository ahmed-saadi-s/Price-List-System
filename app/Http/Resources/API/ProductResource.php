<?php
namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Services\PriceService;

class ProductResource extends JsonResource
{
    protected $priceService;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->priceService = new PriceService();
    }

    public function toArray(Request $request): array
    {
        $date = $request->query('date', now()->toDateString());
        $countryCode = $request->query('country_code');
        $currencyCode = $request->query('currency_code');
        $applicablePriceData = $this->priceService->getApplicablePrice($this->resource, $date, $countryCode, $currencyCode);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at->toDateString(),
            'applicable_price' => $applicablePriceData['price'],
            'currency_code' => $applicablePriceData['currency_code'] ?? 'USD',
        ];
    }
}
