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
                'start_date' => '2024-05-28',
                'end_date' => '2024-05-30',
                'is_active' => true,
                'max_capacity' => 60,
                'price' => 150.00,
                'grade_from' => 1,
                'grade_to' => 4,
            ],
            [
                'name' => 'jump_week',
                'display_name' => 'Jump Week',
                'description' => 'Summer camp for 9th grade through graduated seniors',
                'start_date' => '2024-06-01',
                'end_date' => '2024-06-07',
                'is_active' => true,
                'max_capacity' => 150,
                'price' => 170.00,
                'grade_from' => 9,
                'grade_to' => 13,
            ],
            [
                'name' => 'reunion_week',
                'display_name' => 'Reunion Week',
                'description' => 'Summer camp for 4th-12th grade students',
                'start_date' => '2024-06-08',
                'end_date' => '2024-06-14',
                'is_active' => true,
                'max_capacity' => 120,
                'price' => 170.00,
                'grade_from' => 4,
                'grade_to' => 13,
            ],
            [
                'name' => 'day_camp',
                'display_name' => 'Day Camp',
                'description' => 'Day camp for 1st-4th grade students',
                'start_date' => '2024-06-09',
                'end_date' => '2024-06-11',
                'is_active' => true,
                'max_capacity' => 60,
                'price' => 100.00,
                'grade_from' => 1,
                'grade_to' => 4,
            ],
            [
                'name' => 'super_week',
                'display_name' => 'Super Week',
                'description' => 'Summer camp for 4th-6th grade students',
                'start_date' => '2024-06-15',
                'end_date' => '2024-06-21',
                'is_active' => true,
                'max_capacity' => 100,
                'price' => 170.00,
                'grade_from' => 4,
                'grade_to' => 6,
            ],
            [
                'name' => 'strive_week',
                'display_name' => 'Strive Week',
                'description' => 'Summer camp for 5th grade through graduated seniors',
                'start_date' => '2024-06-22',
                'end_date' => '2024-06-28',
                'is_active' => true,
                'max_capacity' => 150,
                'price' => 170.00,
                'grade_from' => 5,
                'grade_to' => 13,
            ],
            [
                'name' => 'connect_week',
                'display_name' => 'Connect Week',
                'description' => 'Summer camp for 6th grade through graduated seniors',
                'start_date' => '2024-06-29',
                'end_date' => '2024-07-05',
                'is_active' => true,
                'max_capacity' => 120,
                'price' => 170.00,
                'grade_from' => 6,
                'grade_to' => 13,
            ],
            [
                'name' => 'elevate_week',
                'display_name' => 'Elevate Week',
                'description' => 'Summer camp for 7th-10th grade girls',
                'start_date' => '2024-07-06',
                'end_date' => '2024-07-12',
                'is_active' => true,
                'max_capacity' => 80,
                'price' => 170.00,
                'grade_from' => 7,
                'grade_to' => 10,
            ],
            [
                'name' => 'fall_focus',
                'display_name' => 'Fall Focus',
                'description' => 'Fall retreat for 5th-12th grade students',
                'start_date' => '2024-11-01',
                'end_date' => '2024-11-03',
                'is_active' => true,
                'max_capacity' => 100,
                'price' => 170.00,
                'grade_from' => 5,
                'grade_to' => 13,
            ],
        ];

        foreach ($camps as $campData) {
            Camp::create($campData);
        }
    }
} 