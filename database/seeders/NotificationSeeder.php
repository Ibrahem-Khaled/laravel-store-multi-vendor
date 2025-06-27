<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;         // تأكد من استيراد نموذج المستخدمين الخاص بك
use Faker\Factory as Faker;


class NotificationSeeder extends Seeder
{

    public function run(): void
    {
        $faker = Faker::create('ar_SA'); // إنشاء مثيل Faker باللغة العربية (اختياري)
        $userIds = User::pluck('id')->toArray(); // جلب جميع معرفات المستخدمين الموجودة

        if (empty($userIds)) {
            $this->command->info('لا يوجد مستخدمون في قاعدة البيانات. يرجى تشغيل UserSeeder أولاً.');
            return;
        }

        // إنشاء 50 إشعارًا وهميًا
        for ($i = 0; $i < 50; $i++) {
            Notification::create([
                'user_id' => $faker->randomElement($userIds), // اختيار معرف مستخدم عشوائي
                'title' => $faker->sentence(mt_rand(3, 7)),   // عنوان إشعار عشوائي
                'body' => $faker->paragraph(mt_rand(2, 5)),    // نص إشعار عشوائي
                'is_read' => $faker->boolean(),               // قراءة الإشعار بشكل عشوائي (صحيح/خطأ)
                'related_id' => $faker->optional(0.7)->numberBetween(1, 100), // معرف متعلق (اختياري)
                'related_type' => $faker->optional(0.7)->randomElement(['Product', 'Reservation', null]), // نوع متعلق (اختياري)
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'), // تاريخ إنشاء عشوائي خلال العام الماضي
                'updated_at' => $faker->dateTimeBetween('-1 year', 'now'), // تاريخ تحديث عشوائي خلال العام الماضي
            ]);
        }
        $this->command->info('تم إنشاء 50 إشعارًا وهميًا بنجاح.');
    }
}
