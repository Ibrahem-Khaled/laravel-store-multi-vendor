<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * 
     * Run all seeders in the correct order:
     * 1. Roles and Permissions (must be first for user assignment)
     * 2. Users (require roles and permissions)
     * 3. Categories, Subcategories, and Brands
     * 4. Cities and Neighborhoods
     * 5. Merchants (require cities/neighborhoods)
     * 6. Products (require categories, brands, merchants)
     * 7. Notifications
     * 8. Orders (require products, merchants, users)
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting database seeding...');
        
        // Step 1: Seed Roles and Permissions (must be first)
        $this->command->info('📋 Seeding roles and permissions...');
        $this->call(RolePermissionSeeder::class);
        
        // Step 1.5: Seed Currencies (independent, can be seeded early)
        $this->command->info('💰 Seeding currencies...');
        $this->call(CurrencySeeder::class);
        
        // Step 2: Seed Users (require roles)
        $this->command->info('👥 Seeding users...');
        $this->call(UserSeeder::class);
        
        // Step 3: Seed Categories, Subcategories, and Brands
        $this->command->info('📦 Seeding categories, subcategories, and brands...');
        $this->call(CategorySubcategoryBrandSeeder::class);
        
        // Step 4: Seed Cities and Neighborhoods
        $this->command->info('📍 Seeding cities and neighborhoods...');
        $this->call(CityNeighborhoodSeeder::class);
        
        // Step 5: Seed Merchants (require cities/neighborhoods)
        $this->command->info('🏪 Seeding merchants...');
        $this->call(MerchantSeeder::class);
        
        // Step 6: Seed Products (require categories, brands, merchants)
        $this->command->info('🛍️ Seeding products...');
        $this->call(ProductSeeder::class);
        
        // Step 7: Seed Notifications
        $this->command->info('🔔 Seeding notifications...');
        $this->call(NotificationSeeder::class);
        
        // Step 8: Seed Orders (require products, merchants, users)
        $this->command->info('🛒 Seeding orders...');
        $this->call(OrderSeeder::class);
        
        $this->command->info('✅ Database seeding completed successfully!');
    }
}
