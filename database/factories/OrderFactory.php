<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'sent_by' => auth()->id() || 1,
            'last_name' => $this->faker->name(),
            'changes' => [
                "first_name" => $this->faker->name(),
                "last_name" => $this->faker->name(),
                'email' => $this->faker->unique()->safeEmail(),
            ],
            'status' => Order::PENDING_STATUS
        ];
    }
}
