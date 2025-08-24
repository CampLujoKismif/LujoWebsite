<?php

namespace Database\Seeders;

use App\Models\ChurchCongregation;
use Illuminate\Database\Seeder;

class ChurchCongregationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $congregations = [
            [
                'name' => 'First Baptist Church',
                'address' => '123 Main Street',
                'city' => 'Springfield',
                'state' => 'IL',
                'zip_code' => '62701',
                'phone' => '(217) 555-0123',
                'website' => 'https://firstbaptistspringfield.org',
                'contact_person' => 'Pastor John Smith',
                'contact_email' => 'pastor@firstbaptistspringfield.org',
                'is_active' => true,
            ],
            [
                'name' => 'Grace Community Church',
                'address' => '456 Oak Avenue',
                'city' => 'Springfield',
                'state' => 'IL',
                'zip_code' => '62702',
                'phone' => '(217) 555-0456',
                'website' => 'https://gracecommunitychurch.org',
                'contact_person' => 'Pastor Sarah Johnson',
                'contact_email' => 'pastor@gracecommunitychurch.org',
                'is_active' => true,
            ],
            [
                'name' => 'St. Mary\'s Catholic Church',
                'address' => '789 Pine Street',
                'city' => 'Springfield',
                'state' => 'IL',
                'zip_code' => '62703',
                'phone' => '(217) 555-0789',
                'website' => 'https://stmarysspringfield.org',
                'contact_person' => 'Father Michael Brown',
                'contact_email' => 'father@stmarysspringfield.org',
                'is_active' => true,
            ],
            [
                'name' => 'Trinity Lutheran Church',
                'address' => '321 Elm Street',
                'city' => 'Springfield',
                'state' => 'IL',
                'zip_code' => '62704',
                'phone' => '(217) 555-0321',
                'website' => 'https://trinitylutheranspringfield.org',
                'contact_person' => 'Pastor David Wilson',
                'contact_email' => 'pastor@trinitylutheranspringfield.org',
                'is_active' => true,
            ],
            [
                'name' => 'New Life Church',
                'address' => '654 Maple Drive',
                'city' => 'Springfield',
                'state' => 'IL',
                'zip_code' => '62705',
                'phone' => '(217) 555-0654',
                'website' => 'https://newlifespringfield.org',
                'contact_person' => 'Pastor Lisa Davis',
                'contact_email' => 'pastor@newlifespringfield.org',
                'is_active' => true,
            ],
        ];

        foreach ($congregations as $congregation) {
            ChurchCongregation::create($congregation);
        }
    }
}
