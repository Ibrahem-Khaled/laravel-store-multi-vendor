<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class CategorySubcategoryBrandSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('ar_SA');

        // إنشاء مستخدم تاجر لاستخدامه في العلامات التجارية
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'trader@example.com'],
            [
                'uuid' => Str::uuid(),
                'username' => 'trader_user',
                'name' => 'تاجر تجريبي',
                'password' => bcrypt('password123'),
                'role' => 'trader',
                'email_verified_at' => now(),
            ]
        );

        // إنشاء 5 تصنيفات رئيسية
        for ($i = 1; $i <= 5; $i++) {
            $category = Category::create([
                'name' => 'تصنيف ' . $i,
                'description' => $faker->sentence,
                'image' => 'category_' . $i . '.jpg',
            ]);

            // لكل تصنيف 3 فئات فرعية
            for ($j = 1; $j <= 3; $j++) {
                SubCategory::create([
                    'category_id' => $category->id,
                    'name' => "فئة فرعية $j - $category->name",
                    'description' => $faker->sentence,
                    'image' => 'subcategory_' . $j . '.jpg',
                ]);
            }
        }

        // إنشاء 10 علامات تجارية
        for ($k = 1; $k <= 10; $k++) {
            Brand::create([
                'user_id' => $user->id,
                'name' => 'علامة تجارية ' . $k,
                'description' => $faker->sentence,
                'image' => 'brand_' . $k . '.jpg',
                'link' => 'https://example.com/brand' . $k,
                'order' => $k,
                'latitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'is_active' => true,
            ]);
        }
    }
}
