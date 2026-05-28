<?php

namespace Database\Factories;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 year', 'now');
        $end = (clone $start)->modify('+1 month');
        return [
            'user_id' => User::factory(),
            'plan_id' => $this->faker->numberBetween(1, 3),
            'plan_name' => $this->faker->randomElement(['Basic', 'Pro', 'Premium']),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'started_at' => $start,
            'ends_at' => $end,
        ];
    }
}
