<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\PriceList;
use App\Models\Country;
use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PriceListTest extends TestCase
{
    use RefreshDatabase;

    // Setup initial data before each test
    public function setUp(): void
    {
        parent::setUp();

        Country::create(['code' => 'US', 'name' => 'United States']);
        Country::create(['code' => 'CA', 'name' => 'Canada']);
        Currency::create(['code' => 'EUR', 'name' => 'Euro']);
        Currency::create(['code' => 'CAD', 'name' => 'Canadian Dollar']);
    }

    // Test fetching all products with their applicable prices
    public function test_it_returns_all_products_with_applicable_prices()
    {
        $product1 = Product::create(['name' => 'Test Product 1', 'base_price' => 100]);
        $product2 = Product::create(['name' => 'Test Product 2', 'base_price' => 150]);
        $product3 = Product::create(['name' => 'Test Product 3', 'base_price' => 200]);
        $product4 = Product::create(['name' => 'Test Product 4', 'base_price' => 250]);

        PriceList::create([
            'product_id' => $product2->id,
            'country_code' => 'US',
            'currency_code' => 'EUR',
            'price' => 120,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'priority' => 1,
        ]);

        PriceList::create([
            'product_id' => $product3->id,
            'country_code' => 'US',
            'currency_code' => 'EUR',
            'price' => 180,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'priority' => 2,
        ]);
        PriceList::create([
            'product_id' => $product3->id,
            'country_code' => 'US',
            'currency_code' => 'EUR',
            'price' => 170,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'priority' => 1,
        ]);

        PriceList::create([
            'product_id' => $product4->id,
            'country_code' => 'US',
            'currency_code' => 'EUR',
            'price' => 230,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'priority' => 3,
        ]);
        PriceList::create([
            'product_id' => $product4->id,
            'country_code' => 'US',
            'currency_code' => 'EUR',
            'price' => 220,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'priority' => 2,
        ]);
        PriceList::create([
            'product_id' => $product4->id,
            'country_code' => 'US',
            'currency_code' => 'EUR',
            'price' => 210,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'priority' => 1,
        ]);

        $response = $this->getJson('/api/products');
        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Test Product 1', 'applicable_price' => '100.00'])
            ->assertJsonFragment(['name' => 'Test Product 2', 'applicable_price' => '120.00'])
            ->assertJsonFragment(['name' => 'Test Product 3', 'applicable_price' => '170.00'])
            ->assertJsonFragment(['name' => 'Test Product 4', 'applicable_price' => '210.00']);
    }


    // Test fetching a product with specific country and currency
    public function test_it_returns_correct_price_for_specific_country_and_currency()
    {
        $product = Product::create(['name' => 'Test Product', 'base_price' => 100]);
        PriceList::create([
            'product_id' => $product->id,
            'country_code' => 'CA',
            'currency_code' => 'CAD',
            'price' => 120,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'priority' => 2,
        ]);
        PriceList::create([
            'product_id' => $product->id,
            'country_code' => 'CA',
            'currency_code' => 'EUR',
            'price' => 110,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'priority' => 1,
        ]);

        $response = $this->getJson("/api/products/{$product->id}?country_code=CA&currency_code=CAD&date=" . now()->toDateString());
        $response->assertStatus(200)
            ->assertJsonFragment(['applicable_price' => '120.00']);
    }

    // Test price list without country applies to all countries
    public function test_price_list_without_country_is_applicable_to_all_countries()
    {
        $product1 = Product::create(['name' => 'Product 1', 'base_price' => 100]);
        $product2 = Product::create(['name' => 'Product 2', 'base_price' => 200]);

        PriceList::create([
            'product_id' => $product1->id,
            'country_code' => null,
            'currency_code' => 'EUR',
            'price' => 90,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'priority' => 1,
        ]);

        PriceList::create([
            'product_id' => $product2->id,
            'country_code' => 'US',
            'currency_code' => 'EUR',
            'price' => 180,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'priority' => 1,
        ]);

        $response = $this->getJson('/api/products?country=IN');
        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Product 1')
            ->assertJsonPath('data.0.applicable_price', '90.00');

        $response = $this->getJson('/api/products?country=US');
        $response->assertStatus(200)
            ->assertJsonPath('data.1.name', 'Product 2')
            ->assertJsonPath('data.1.applicable_price', '180.00');

        $response = $this->getJson('/api/products');
        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Product 1')
            ->assertJsonPath('data.0.applicable_price', '90.00');
    }

    // Test price list without currency applies to all currencies
    public function test_price_list_without_currency_is_applicable_to_all_currencies()
    {
        $product1 = Product::create(['name' => 'Product 1', 'base_price' => 100]);
        $product2 = Product::create(['name' => 'Product 2', 'base_price' => 200]);

        PriceList::create([
            'product_id' => $product1->id,
            'country_code' => 'US',
            'currency_code' => null,
            'price' => 90,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'priority' => 1,
        ]);

        PriceList::create([
            'product_id' => $product2->id,
            'country_code' => 'US',
            'currency_code' => 'EUR',
            'price' => 180,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'priority' => 1,
        ]);

        $response = $this->getJson('/api/products?currency=EUR');
        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Product 1')
            ->assertJsonPath('data.0.applicable_price', '90.00');

        $response = $this->getJson('/api/products?currency=EUR');
        $response->assertStatus(200)
            ->assertJsonPath('data.1.name', 'Product 2')
            ->assertJsonPath('data.1.applicable_price', '180.00');

        $response = $this->getJson('/api/products');
        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Product 1')
            ->assertJsonPath('data.0.applicable_price', '90.00');
    }

    // Test handling products with no price list
    public function test_it_handles_missing_price_list()
    {
        $product = Product::create(['name' => 'Product without price list', 'base_price' => 100]);

        $response = $this->getJson('/api/products');
        $response->assertStatus(200)
            ->assertJsonMissing(['applicable_price']);
    }

    // Test selecting price with lower priority
    public function test_it_uses_lower_priority_when_multiple_price_lists_exist()
    {
        $product = Product::create(['name' => 'Test Product', 'base_price' => 100]);
        PriceList::create([
            'product_id' => $product->id,
            'price' => 150,
            'priority' => 2,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
        ]);

        PriceList::create([
            'product_id' => $product->id,
            'price' => 130,
            'priority' => 1,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
        ]);
        $response = $this->getJson("/api/products/{$product->id}");
        $response->assertStatus(200)
            ->assertJsonFragment(['applicable_price' => '130.00']);
    }
    // Test fallback to base price when no active price list matches
    public function test_it_falls_back_to_base_price_if_no_price_list_exists()
    {
        $product = Product::create(['name' => 'Test Product', 'base_price' => 200]);

        // Create an active price list for the product
        PriceList::create([
            'product_id' => $product->id,
            'country_code' => 'US',
            'currency_code' => 'EUR',
            'price' => 150,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'priority' => 1,
        ]);

        // Request with a country code don't match the active price list
        $response = $this->getJson("/api/products/{$product->id}?country_code=CA");
        $response->assertStatus(200)
            ->assertJsonFragment(['applicable_price' => '200.00']);
    }

    // Test sorting products by price (low to high and high to low)
    public function test_it_returns_products_sorted_by_price()
    {
        $product1 = Product::create(['name' => 'Cheap Product', 'base_price' => 50]);
        $product2 = Product::create(['name' => 'Expensive Product', 'base_price' => 300]);
        $product3 = Product::create(['name' => 'Mid-range Product', 'base_price' => 150]);

        PriceList::create([
            'product_id' => $product1->id,
            'country_code' => 'US',
            'currency_code' => 'EUR',
            'price' => 60,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'priority' => 1,
        ]);
        PriceList::create([
            'product_id' => $product2->id,
            'country_code' => 'US',
            'currency_code' => 'EUR',
            'price' => 280,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'priority' => 1,
        ]);
        PriceList::create([
            'product_id' => $product3->id,
            'country_code' => 'US',
            'currency_code' => 'EUR',
            'price' => 160,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(5)->toDateString(),
            'priority' => 1,
        ]);

        $response = $this->getJson('/api/products?order=lowest-to-highest');
        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Cheap Product')
            ->assertJsonPath('data.1.name', 'Mid-range Product')
            ->assertJsonPath('data.2.name', 'Expensive Product');

        $response = $this->getJson('/api/products?order=highest-to-lowest');
        $response->assertStatus(200)
            ->assertJsonPath('data.0.name', 'Expensive Product')
            ->assertJsonPath('data.1.name', 'Mid-range Product')
            ->assertJsonPath('data.2.name', 'Cheap Product');
    }
}