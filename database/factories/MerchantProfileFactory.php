<?php

namespace Database\Factories;

use App\Models\MerchantProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MerchantProfile>
 */
class MerchantProfileFactory extends Factory
{
    protected $model = MerchantProfile::class;

    public function definition(): array
    {
        return [
            'user_id'                 => User::factory()->merchant(),
            'default_commission_rate' => $this->faker->randomElement([0.10, 0.12, 0.15]),
            'payout_bank_name'        => 'National Bank',
            'payout_account_name'     => $this->faker->name(),
            'payout_account_iban'     => 'EG' . $this->faker->bankAccountNumber(),
        ];
    }
}
