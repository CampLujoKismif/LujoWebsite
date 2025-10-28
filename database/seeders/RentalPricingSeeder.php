<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RentalPricing;

class RentalPricingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default pricing if none exists
        if (!RentalPricing::current()) {
            RentalPricing::create([
                'price_per_person_per_day' => 25.00,
                'deposit_amount' => 100.00,
                'description' => 'Default rental pricing',
                'is_active' => true,
            ]);
        }
    }
}