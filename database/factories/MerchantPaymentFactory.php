<?php

namespace Database\Factories;

use App\Models\MerchantPayment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MerchantPayment>
 */
class MerchantPaymentFactory extends Factory
{
    protected $model = MerchantPayment::class;

    public function definition(): array
    {
        return [
            'merchant_id' => User::factory()->merchant(),
            'type'        => $this->faker->randomElement(['payout_to_merchant', 'collection_from_merchant']),
            'amount'      => $this->faker->randomFloat(2, 50, 2000),
            'method'      => $this->faker->randomElement(['bank_transfer', 'cash']),
            'reference'   => 'TXN-' . $this->faker->numerify('########'),
            'paid_at'     => now()->subDays($this->faker->numberBetween(0, 15)),
        ];
    }
}
