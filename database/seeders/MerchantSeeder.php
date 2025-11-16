<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, MerchantProfile, MerchantPayment};

class MerchantSeeder extends Seeder
{
    public function run(): void
    {
        // أدمن واحد
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            User::factory()->admin()->make()->toArray()
        );

        // 20 تاجر + بروفايل
        $merchants = [];
        for ($i = 0; $i < 20; $i++) {
            $merchant = User::factory()->merchant()->create();
            $merchants[] = $merchant;
            
            MerchantProfile::firstOrCreate(
                ['user_id' => $merchant->id],
                MerchantProfile::factory()->make([
                    'user_id' => $merchant->id,
                    'default_commission_rate' => fake()->randomElement([0.10, 0.12, 0.15]),
                ])->toArray()
            );
        }

        // عينات مدفوعات (صرف/تحصيل) لتغذية التقارير
        if (count($merchants) > 0) {
            MerchantPayment::factory(30)->create([
                // سيولّد merchant جديد افتراضياً؛ نربطه بواحد من الـ merchants الموجودين
            ])->each(function ($p) use ($merchants) {
                $p->update(['merchant_id' => $merchants[array_rand($merchants)]->id]);
            });
        }
    }
}
