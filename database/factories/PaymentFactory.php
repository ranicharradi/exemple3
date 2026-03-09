<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'course_id' => Course::factory(),
            'amount' => fake()->randomFloat(2, 29, 399),
            'reference' => 'CDX-'.Str::upper(Str::random(12)),
            'proof' => 'payment-proofs/'.Str::random(40).'.pdf',
            'status' => Payment::STATUS_PENDING,
            'reviewed_at' => null,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn () => [
            'status' => Payment::STATUS_APPROVED,
            'reviewed_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn () => [
            'status' => Payment::STATUS_REJECTED,
            'reviewed_at' => now(),
        ]);
    }
}
