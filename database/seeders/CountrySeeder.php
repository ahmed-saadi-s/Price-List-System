<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['code' => 'SY', 'name' => 'Syria'],
            ['code' => 'AE', 'name' => 'United Arab Emirates'],
            ['code' => 'KW', 'name' => 'Kuwait'],
        ];

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['code' => $country['code']],
                ['name' => $country['name']]
            );
        }
    }
}