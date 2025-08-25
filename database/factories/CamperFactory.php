<?php

namespace Database\Factories;

use App\Models\Family;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Camper>
 */
class CamperFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'family_id' => Family::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'date_of_birth' => $this->faker->dateTimeBetween('-18 years', '-5 years'),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'allergies' => $this->faker->optional()->text(),
            'medical_conditions' => $this->faker->optional()->text(),
            'medications' => $this->faker->optional()->text(),
            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_phone' => $this->faker->phoneNumber(),
            'emergency_contact_relationship' => $this->faker->randomElement(['parent', 'guardian', 'grandparent', 'sibling']),
            'notes' => $this->faker->optional()->text(),
        ];
    }

    /**
     * Indicate that the camper is male.
     */
    public function male(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender' => 'male',
        ]);
    }

    /**
     * Indicate that the camper is female.
     */
    public function female(): static
    {
        return $this->state(fn (array $attributes) => [
            'gender' => 'female',
        ]);
    }

    /**
     * Indicate that the camper has allergies.
     */
    public function withAllergies(): static
    {
        return $this->state(fn (array $attributes) => [
            'allergies' => $this->faker->sentence(),
        ]);
    }

    /**
     * Indicate that the camper has medical conditions.
     */
    public function withMedicalConditions(): static
    {
        return $this->state(fn (array $attributes) => [
            'medical_conditions' => $this->faker->sentence(),
        ]);
    }
}
