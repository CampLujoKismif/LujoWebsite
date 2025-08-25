<?php

namespace Database\Factories;

use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'enrollment_id' => Enrollment::factory(),
            'amount_cents' => $this->faker->numberBetween(1000, 10000), // $10 to $100
            'method' => $this->faker->randomElement(['cash', 'check', 'credit_card', 'debit_card', 'bank_transfer', 'online']),
            'reference' => $this->faker->optional()->bothify('REF-####-????'),
            'stripe_payment_intent_id' => $this->faker->optional()->regexify('pi_[a-zA-Z0-9]{24}'),
            'stripe_charge_id' => $this->faker->optional()->regexify('ch_[a-zA-Z0-9]{24}'),
            'status' => $this->faker->randomElement(['pending', 'processing', 'succeeded', 'failed', 'cancelled']),
            'stripe_metadata' => $this->faker->optional()->randomElement([['key1' => 'value1'], ['key2' => 'value2'], null]),
            'paid_at' => $this->faker->optional()->dateTime(),
            'processed_at' => $this->faker->optional()->dateTime(),
            'notes' => $this->faker->optional()->text(),
            'processed_by_user_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the payment is successful.
     */
    public function succeeded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'succeeded',
            'paid_at' => now(),
            'processed_at' => now(),
        ]);
    }

    /**
     * Indicate that the payment is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'paid_at' => null,
            'processed_at' => null,
        ]);
    }

    /**
     * Indicate that the payment failed.
     */
    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'failed',
            'paid_at' => null,
            'processed_at' => now(),
        ]);
    }

    /**
     * Indicate that the payment is a Stripe payment.
     */
    public function stripe(): static
    {
        return $this->state(fn (array $attributes) => [
            'method' => 'credit_card',
            'stripe_payment_intent_id' => 'pi_' . $this->faker->regexify('[a-zA-Z0-9]{24}'),
            'stripe_charge_id' => 'ch_' . $this->faker->regexify('[a-zA-Z0-9]{24}'),
        ]);
    }
}
