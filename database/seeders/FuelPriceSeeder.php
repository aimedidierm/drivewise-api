<?php

namespace Database\Seeders;

use App\Enums\FuelType;
use App\Models\FuelPrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FuelPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FuelPrice::create([
            'name' => 'Diesel Price',
            'type' => FuelType::DIESEL,
            'price' => 1200
        ]);

        FuelPrice::create([
            'name' => 'Gasoline Price',
            'type' => FuelType::GASOLINE,
            'price' => 1400
        ]);
    }
}
