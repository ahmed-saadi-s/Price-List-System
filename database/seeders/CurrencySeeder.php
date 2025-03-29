<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            ['code' => 'SY', 'name' => 'Syrian Pound'],
            ['code' => 'AED', 'name' => 'UAE Dirham'],
            ['code' => 'KWD', 'name' => 'Kuwaiti Dinar'],
        ];

        foreach ($currencies as $currency) {
            Currency::updateOrCreate(
                ['code' => $currency['code']],
                ['name' => $currency['name']]
            );
        }
    }
}