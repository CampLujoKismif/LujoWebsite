<?php

namespace Database\Factories;

use App\Models\Camp;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CampInstance>
 */
class CampInstanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('+1 month', '+6 months');
        $endDate = clone $startDate;
        $endDate->modify('+1 week');

        return [
            'camp_id' => Camp::factory(),
            'name' => $this->faker->words(2, true),
            'year' => $startDate->format('Y'),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_active' => true,
            'registration_open_date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'registration_close_date' => $this->faker->dateTimeBetween($startDate, $endDate),
            'max_capacity' => $this->faker->numberBetween(20, 100),
            'price' => $this->faker->numberBetween(200, 500),
            'description' => $this->faker->paragraph(),
        ];
    }

    /**
     * Indicate that the camp instance is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the camp instance is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
