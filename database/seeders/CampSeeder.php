<?php

namespace Database\Seeders;

use App\Models\Camp;
use Illuminate\Database\Seeder;

class CampSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $camps = [
            [
                'name' => 'spark_week',
                'display_name' => 'Spark Week',
                'description' => 'Early summer camp for 1st-4th grade students',
                'is_active' => true,
                'price' => 150.00,
            ],
            [
                'name' => 'jump_week',
                'display_name' => 'Jump Week',
                'description' => 'Summer camp for 9th grade through graduated seniors',
                'is_active' => true,
                'price' => 170.00,
            ],
            [
                'name' => 'reunion_week',
                'display_name' => 'Reunion Week',
                'description' => 'Summer camp for 4th-12th grade students',
                'is_active' => true,
                'price' => 170.00,
            ],
            [
                'name' => 'day_camp',
                'display_name' => 'Day Camp',
                'description' => 'Day camp for 1st-4th grade students',
                'is_active' => true,
                'price' => 100.00,
            ],
            [
                'name' => 'super_week',
                'display_name' => 'Super Week',
                'description' => 'Summer camp for 4th-6th grade students',
                'is_active' => true,
                'price' => 170.00,
            ],
            [
                'name' => 'strive_week',
                'display_name' => 'Strive Week',
                'description' => 'Summer camp for 5th grade through graduated seniors',
                'is_active' => true,
                'price' => 170.00,
            ],
            [
                'name' => 'connect_week',
                'display_name' => 'Connect Week',
                'description' => 'Summer camp for 6th grade through graduated seniors',
                'is_active' => true,
                'price' => 170.00,
            ],
            [
                'name' => 'elevate_week',
                'display_name' => 'Elevate Week',
                'description' => 'Summer camp for 7th-10th grade girls',
                'is_active' => true,
                'price' => 170.00,
            ],
            [
                'name' => 'fall_focus',
                'display_name' => 'Fall Focus',
                'description' => 'Fall retreat for 5th-12th grade students',
                'is_active' => true,
                'price' => 170.00,
            ],
        ];

        foreach ($camps as $campData) {
            Camp::create($campData);
        }
    }
} 