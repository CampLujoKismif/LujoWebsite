<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Family>
 */
class FamilyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->lastName() . ' Family',
            'owner_user_id' => User::factory(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'zip_code' => $this->faker->postcode(),
            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_phone' => $this->faker->phoneNumber(),
            'emergency_contact_relationship' => $this->faker->randomElement(['parent', 'guardian', 'grandparent', 'sibling']),
        ];
    }

    /**
     * Indicate that the family has insurance.
     */
    public function withInsurance(): static
    {
        return $this->state(fn (array $attributes) => [
            'insurance_provider' => $this->faker->company(),
            'insurance_policy_number' => $this->faker->regexify('[A-Z0-9]{10}'),
        ]);
    }

    /**
     * Indicate that the family has a church congregation.
     */
    public function withChurch(): static
    {
        return $this->state(fn (array $attributes) => [
            'church_congregation_id' => 1, // You might want to create a ChurchCongregation factory
        ]);
    }
}
