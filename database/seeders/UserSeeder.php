<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create('ar_SA'); // توليد بيانات باللغة العربية السعودية

        for ($i = 0; $i < 50; $i++) {
            User::firstOrCreate(
                ['email' => "user$i@example.com"],
                [
                    'uuid' => Str::uuid(),
                    'username' => 'user_' . $i,
                    'name' => $faker->name,
                    'phone' => '05' . rand(10000000, 99999999),
                    'avatar' => null,
                    'bio' => $faker->sentence,
                    'address' => $faker->address,
                    'country' => 'السعودية',
                    'is_verified' => $faker->boolean,
                    'status' => $faker->randomElement(['active', 'inactive', 'banned', 'pending']),
                    'gender' => $faker->randomElement(['male', 'female']),
                    'role' => $faker->randomElement(['user', 'trader', 'admin']),
                    'birth_date' => $faker->date('Y-m-d', '2005-01-01'),
                    'password' => Hash::make('password123'), // كلمة مرور افتراضية
                    'coins' => rand(0, 500),
                    'email_verified_at' => $faker->boolean ? now() : null,
                ]
            );
        }
    }
}
