<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\City;
use App\Models\Neighborhood;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $subCategories = SubCategory::pluck('id')->toArray();
        $brands = Brand::pluck('id')->toArray();
        $cities = City::all();

        for ($i = 1; $i <= 50; $i++) {
            $city = $cities->random();
            $neighborhood = $city->neighborhoods()->inRandomOrder()->first();

            Product::create([
                'sub_category_id' => Arr::random($subCategories),
                'brand_id' => Arr::random($brands),
                'city_id' => $city->id,
                'neighborhood_id' => $neighborhood->id,
                'name' => 'منتج رقم ' . $i,
                'description' => 'هذا وصف لمنتج رقم ' . $i . ' ويحتوي على تفاصيل وهمية.',
                'price' => rand(100, 1000),
                'discount_percent' => rand(0, 50),
                'video_url' => 'https://www.youtube.com/watch?v=' . Str::random(11),
                'latitude' => fake()->latitude(30.0, 33.0),
                'longitude' => fake()->longitude(10.0, 14.0),
            ]);
        }
    }
}
