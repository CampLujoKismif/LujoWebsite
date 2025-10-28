<?php

namespace Database\Seeders;

use App\Models\Camp;
use App\Models\CampInstance;
use Illuminate\Database\Seeder;

class CampInstanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $camps = Camp::all();
        $currentYear = now()->year;

        foreach ($camps as $camp) {
            // Create instances for current year and next year
            for ($year = $currentYear; $year <= $currentYear + 1; $year++) {
                CampInstance::firstOrCreate(
                    [
                        'camp_id' => $camp->id,
                        'year' => $year,
                    ],
                    [
                        'name' => $camp->display_name . ' ' . $year,
                        'description' => $camp->description . ' - ' . $year . ' Session',
                        'theme_description' => 'Theme for ' . $year . ' - ' . $camp->display_name,
                        'start_date' => $this->getStartDate($camp->name, $year),
                        'end_date' => $this->getEndDate($camp->name, $year),
                        'is_active' => true,
                        'max_capacity' => $this->getCapacity($camp->name),
                        'price' => $this->getPrice($camp->name),
                        'age_from' => $this->getAgeFrom($camp->name),
                        'age_to' => $this->getAgeTo($camp->name),
                        'grade_from' => $this->getGradeFrom($camp->name),
                        'grade_to' => $this->getGradeTo($camp->name),
                        'registration_open_date' => now()->subMonths(6),
                        'registration_close_date' => now()->addMonths(2),
                        'theme_photos' => [],
                        'schedule_data' => [],
                        'additional_info' => [],
                    ]
                );
            }
        }
    }

    private function getStartDate($campName, $year)
    {
        $dates = [
            'spark_week' => "{$year}-05-28",
            'jump_week' => "{$year}-06-01",
            'reunion_week' => "{$year}-06-08",
            'day_camp' => "{$year}-06-09",
            'super_week' => "{$year}-06-15",
            'strive_week' => "{$year}-06-22",
            'connect_week' => "{$year}-06-29",
            'elevate_week' => "{$year}-07-06",
            'fall_focus' => "{$year}-11-01",
        ];

        return $dates[$campName] ?? "{$year}-06-01";
    }

    private function getEndDate($campName, $year)
    {
        $dates = [
            'spark_week' => "{$year}-05-30",
            'jump_week' => "{$year}-06-07",
            'reunion_week' => "{$year}-06-14",
            'day_camp' => "{$year}-06-11",
            'super_week' => "{$year}-06-21",
            'strive_week' => "{$year}-06-28",
            'connect_week' => "{$year}-07-05",
            'elevate_week' => "{$year}-07-12",
            'fall_focus' => "{$year}-11-03",
        ];

        return $dates[$campName] ?? "{$year}-06-07";
    }

    private function getCapacity($campName)
    {
        $capacities = [
            'spark_week' => 60,
            'jump_week' => 150,
            'reunion_week' => 120,
            'day_camp' => 60,
            'super_week' => 100,
            'strive_week' => 150,
            'connect_week' => 120,
            'elevate_week' => 80,
            'fall_focus' => 100,
        ];

        return $capacities[$campName] ?? 100;
    }

    private function getPrice($campName)
    {
        $prices = [
            'spark_week' => 150.00,
            'jump_week' => 170.00,
            'reunion_week' => 170.00,
            'day_camp' => 100.00,
            'super_week' => 170.00,
            'strive_week' => 170.00,
            'connect_week' => 170.00,
            'elevate_week' => 170.00,
            'fall_focus' => 170.00,
        ];

        return $prices[$campName] ?? 170.00;
    }

    private function getAgeFrom($campName)
    {
        $ages = [
            'spark_week' => 6,
            'jump_week' => 14,
            'reunion_week' => 9,
            'day_camp' => 6,
            'super_week' => 9,
            'strive_week' => 10,
            'connect_week' => 11,
            'elevate_week' => 12,
            'fall_focus' => 10,
        ];

        return $ages[$campName] ?? 10;
    }

    private function getAgeTo($campName)
    {
        $ages = [
            'spark_week' => 10,
            'jump_week' => 19,
            'reunion_week' => 18,
            'day_camp' => 10,
            'super_week' => 12,
            'strive_week' => 19,
            'connect_week' => 19,
            'elevate_week' => 16,
            'fall_focus' => 18,
        ];

        return $ages[$campName] ?? 18;
    }

    private function getGradeFrom($campName)
    {
        $grades = [
            'spark_week' => 1,
            'jump_week' => 9,
            'reunion_week' => 4,
            'day_camp' => 1,
            'super_week' => 4,
            'strive_week' => 5,
            'connect_week' => 6,
            'elevate_week' => 7,
            'fall_focus' => 5,
        ];

        return $grades[$campName] ?? 5;
    }

    private function getGradeTo($campName)
    {
        $grades = [
            'spark_week' => 4,
            'jump_week' => 13,
            'reunion_week' => 12,
            'day_camp' => 4,
            'super_week' => 6,
            'strive_week' => 13,
            'connect_week' => 13,
            'elevate_week' => 10,
            'fall_focus' => 12,
        ];

        return $grades[$campName] ?? 12;
    }
} 