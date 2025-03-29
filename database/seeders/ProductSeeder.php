<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\PriceList;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $product1 = Product::updateOrCreate(
            ['name' => 'Basic Phone'],
            [
                'base_price' => 100,
                'description' => 'A simple and affordable mobile phone.',
            ]

        );

        $product2 = Product::updateOrCreate(
            ['name' => 'Smart TV'],
            [
                'base_price' => 500,
                'description' => 'A high-definition smart television with multiple features.', // وصف بسيط
            ]
        );
        PriceList::updateOrCreate(
            [
                'product_id' => $product2->id,
                'country_code' => null,
                'currency_code' => 'SY',
                'start_date' => now()->subDays(5)->toDateString(),
                'end_date' => now()->addDays(5)->toDateString(),
            ],
            [
                'price' => 450,
                'priority' => 1,
            ]
        );

        $product3 = Product::updateOrCreate(
            ['name' => 'Laptop'],
            ['base_price' => 1000]
        );
        PriceList::updateOrCreate(
            [
                'product_id' => $product3->id,
                'country_code' => 'AE',
                'currency_code' => 'AED',
                'start_date' => now()->subDays(5)->toDateString(),
                'end_date' => now()->addDays(5)->toDateString(),
            ],
            [
                'price' => 1200,
                'priority' => 2,
            ]
        );
        PriceList::updateOrCreate(
            [
                'product_id' => $product3->id,
                'country_code' => 'AE',
                'currency_code' => 'AED',
                'start_date' => now()->subDays(5)->toDateString(),
                'end_date' => now()->addDays(5)->toDateString(),
            ],
            [
                'price' => 1150,
                'priority' => 1,
            ]
        );

        $product4 = Product::updateOrCreate(
            ['name' => 'Headphones'],
            ['base_price' => 50]
        );
        PriceList::updateOrCreate(
            [
                'product_id' => $product4->id,
                'country_code' => null,
                'currency_code' => 'SY',
                'start_date' => now()->subDays(5)->toDateString(),
                'end_date' => now()->addDays(5)->toDateString(),
            ],
            [
                'price' => 45,
                'priority' => 3,
            ]
        );
        PriceList::updateOrCreate(
            [
                'product_id' => $product4->id,
                'country_code' => 'AE',
                'currency_code' => 'AED',
                'start_date' => now()->subDays(5)->toDateString(),
                'end_date' => now()->addDays(5)->toDateString(),
            ],
            [
                'price' => 60,
                'priority' => 2,
            ]
        );
        PriceList::updateOrCreate(
            [
                'product_id' => $product4->id,
                'country_code' => 'KW',
                'currency_code' => 'KWD',
                'start_date' => now()->subDays(5)->toDateString(),
                'end_date' => now()->addDays(5)->toDateString(),
            ],
            [
                'price' => 3500,
                'priority' => 1,
            ]
        );

        $product5 = Product::updateOrCreate(
            ['name' => 'Smart Watch'],
            ['base_price' => 200]
        );
        PriceList::updateOrCreate(
            [
                'product_id' => $product5->id,
                'country_code' => null,
                'currency_code' => null,
                'start_date' => now()->subDays(5)->toDateString(),
                'end_date' => now()->addDays(5)->toDateString(),
            ],
            [
                'price' => 180,
                'priority' => 1,
            ]
        );
        PriceList::updateOrCreate(
            [
                'product_id' => $product5->id,
                'country_code' => null,
                'currency_code' => 'SY',
                'start_date' => now()->subDays(5)->toDateString(),
                'end_date' => now()->addDays(5)->toDateString(),
            ],
            [
                'price' => 190,
                'priority' => 2,
            ]
        );
    }
}
