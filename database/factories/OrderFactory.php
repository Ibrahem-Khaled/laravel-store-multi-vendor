<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $pm = $this->faker->randomElement(['cash_on_delivery', 'card']);
        return [
            'user_id'         => User::factory()->customer(),
            'user_address_id' => null,
            'status'          => $pm === 'card' ? 'paid' : 'pending',
            'payment_method'  => $pm,
            'subtotal'        => 0,
            'shipping_total'  => 0,
            'discount_total'  => 0,
            'grand_total'     => 0,
        ];
    }
}
