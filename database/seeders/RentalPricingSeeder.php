<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentalPricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\RentalPricing::firstOrCreate(
            ['description' => 'Standard rental pricing - $20 per person per day'],
            [
                'price_per_person_per_day' => 20.00,
                'deposit_amount' => null,
                'is_active' => true,
                'description' => 'Standard rental pricing - $20 per person per day',
            ]
        );
    }
}
