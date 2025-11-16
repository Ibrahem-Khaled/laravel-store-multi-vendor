<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ar_SA');

        // إنشاء مستخدمين للسواقين إذا لم يكونوا موجودين
        $driverUsers = [];
        for ($i = 1; $i <= 20; $i++) {
            $user = User::firstOrCreate(
                ['email' => "driver{$i}@example.com"],
                [
                    'uuid' => Str::uuid(),
                    'username' => 'driver_' . $i,
                    'name' => $faker->name,
                    'phone' => '05' . rand(10000000, 99999999),
                    'password' => Hash::make('password123'),
                    'role' => 'user',
                    'status' => 'active',
                    'is_verified' => true,
                    'email_verified_at' => now(),
                ]
            );
            $driverUsers[] = $user;
        }

        // أنواع المركبات
        $vehicleTypes = ['car', 'motorcycle', 'bicycle'];
        $vehicleModels = [
            'car' => ['تويوتا كورولا', 'هوندا سيفيك', 'نيسان ألتيما', 'هيونداي إلنترا', 'فورد فوكس'],
            'motorcycle' => ['ياماها', 'هوندا', 'كاواساكي', 'سوزوكي'],
            'bicycle' => ['دراجة هوائية عادية', 'دراجة كهربائية', 'دراجة جبلية']
        ];

        // المدن والأحياء
        $cities = ['الرياض', 'جدة', 'الدمام', 'المدينة المنورة', 'مكة المكرمة'];
        $neighborhoods = [
            'الرياض' => ['العليا', 'الملز', 'النرجس', 'الورود', 'الزهراء'],
            'جدة' => ['الكورنيش', 'الروابي', 'الزهراء', 'الصفا', 'السلامة'],
            'الدمام' => ['الكورنيش', 'الفناتير', 'الفيصلية', 'الخليج', 'المنتزهات'],
            'المدينة المنورة' => ['قباء', 'العوالي', 'العزيزية', 'المناخة'],
            'مكة المكرمة' => ['العزيزية', 'الزاهر', 'الشبيكة', 'العتيبية']
        ];

        // ساعات العمل الافتراضية
        $defaultWorkingHours = [
            'monday' => ['start' => '08:00', 'end' => '18:00'],
            'tuesday' => ['start' => '08:00', 'end' => '18:00'],
            'wednesday' => ['start' => '08:00', 'end' => '18:00'],
            'thursday' => ['start' => '08:00', 'end' => '18:00'],
            'friday' => ['start' => '14:00', 'end' => '22:00'],
            'saturday' => ['start' => '08:00', 'end' => '18:00'],
            'sunday' => ['start' => '08:00', 'end' => '18:00'],
        ];

        // إنشاء السواقين
        foreach ($driverUsers as $index => $user) {
            $vehicleType = $faker->randomElement($vehicleTypes);
            $city = $faker->randomElement($cities);
            $neighborhood = $faker->randomElement($neighborhoods[$city] ?? ['عام']);

            // إنشاء مناطق خدمة عشوائية
            $serviceAreas = [];
            $numAreas = rand(2, 5);
            for ($j = 0; $j < $numAreas; $j++) {
                $serviceAreas[] = $faker->randomElement($neighborhoods[$city] ?? ['منطقة ' . ($j + 1)]);
            }
            $serviceAreas = array_unique($serviceAreas);

            Driver::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'license_number' => 'DL' . str_pad($index + 1, 8, '0', STR_PAD_LEFT),
                    'vehicle_type' => $vehicleType,
                    'vehicle_model' => $faker->randomElement($vehicleModels[$vehicleType]),
                    'vehicle_plate_number' => $faker->regexify('[A-Z]{3}[0-9]{4}'),
                    'phone_number' => $user->phone ?? '05' . rand(10000000, 99999999),
                    'city' => $city,
                    'neighborhood' => $neighborhood,
                    'latitude' => $faker->latitude(24.0, 25.0),
                    'longitude' => $faker->longitude(46.0, 47.0),
                    'is_available' => $faker->boolean(70), // 70% متاحين
                    'is_active' => true,
                    'is_supervisor' => $index < 3, // أول 3 سواقين مشرفين
                    'current_orders_count' => rand(0, 5),
                    'rating' => round($faker->randomFloat(2, 3.5, 5.0), 2),
                    'total_deliveries' => rand(0, 500),
                    'working_hours' => $defaultWorkingHours,
                    'service_areas' => $serviceAreas,
                ]
            );
        }

        $this->command->info('✅ تم إنشاء ' . count($driverUsers) . ' سواق بنجاح');
    }
}
