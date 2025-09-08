<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name'       => $this->faker->name(),
            'email'      => $this->faker->unique()->safeEmail(),
            'phone'      => $this->faker->unique()->phoneNumber(),
            'bio'        => $this->faker->sentence(),
            'country'    => 'EG',
            'status'     => 'active',
            'coins'      => $this->faker->numberBetween(0, 500),
            'password'   => 'password', // Laravel casts => hashed
            'role'       => 'customer',
            'username'   => Str::slug($this->faker->unique()->userName()),
        ];
    }

    public function admin(): self
    {
        return $this->state(fn() => ['role' => 'admin', 'email' => 'admin@example.com']);
    }

    public function merchant(): self
    {
        return $this->state(fn() => ['role' => 'trader']);
    }

    public function customer(): self
    {
        return $this->state(fn() => ['role' => 'user']);
    }
}
