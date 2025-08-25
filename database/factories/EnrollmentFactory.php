<?php

namespace Database\Factories;

use App\Models\CampInstance;
use App\Models\Camper;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Enrollment>
 */
class EnrollmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'camp_instance_id' => CampInstance::factory(),
            'camper_id' => Camper::factory(),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'waitlisted', 'cancelled', 'registered_awaiting_payment', 'registered_fully_paid']),
            'balance_cents' => $this->faker->numberBetween(5000, 20000), // $50 to $200
            'amount_paid_cents' => 0,
            'forms_complete' => $this->faker->boolean(),
            'enrolled_at' => $this->faker->optional()->dateTime(),
            'notes' => $this->faker->optional()->text(),
        ];
    }

    /**
     * Indicate that the enrollment is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    /**
     * Indicate that the enrollment is confirmed.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
        ]);
    }

    /**
     * Indicate that the enrollment is awaiting payment.
     */
    public function awaitingPayment(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'registered_awaiting_payment',
            'forms_complete' => true,
        ]);
    }

    /**
     * Indicate that the enrollment is fully paid.
     */
    public function fullyPaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'registered_fully_paid',
            'forms_complete' => true,
            'amount_paid_cents' => $this->faker->numberBetween(5000, 20000),
        ]);
    }
}
