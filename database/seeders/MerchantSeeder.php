<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, MerchantProfile, MerchantPayment};

class MerchantSeeder extends Seeder
{
    public function run(): void
    {
        // أدمن واحد
        User::factory()->admin()->create();

        // 20 تاجر + بروفايل
        $merchants = User::factory(20)->merchant()->create();
        foreach ($merchants as $m) {
            MerchantProfile::factory()->create([
                'user_id' => $m->id,
                'default_commission_rate' => fake()->randomElement([0.10, 0.12, 0.15]),
            ]);
        }

        // عينات مدفوعات (صرف/تحصيل) لتغذية التقارير
        MerchantPayment::factory(30)->create([
            // سيولّد merchant جديد افتراضياً؛ نربطه بواحد من الـ merchants الموجودين
        ])->each(function ($p) use ($merchants) {
            $p->update(['merchant_id' => $merchants->random()->id]);
        });
    }
}
