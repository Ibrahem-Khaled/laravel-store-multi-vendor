<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(CategorySubcategoryBrandSeeder::class);
        $this->call(CityNeighborhoodSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(NotificationSeeder::class);
    }
}
