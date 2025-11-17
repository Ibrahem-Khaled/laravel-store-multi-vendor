<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // إضافة إعدادات رسوم التوصيل
        $deliveryFeeSettings = [
            // الرسوم الأساسية
            [
                'key' => 'delivery_base_fee',
                'value' => '10.00',
                'group' => 'delivery',
                'type' => 'number',
                'label' => 'الرسوم الأساسية للتوصيل',
                'description' => 'الرسوم الأساسية للتوصيل بالريال (قبل إضافة رسوم المسافة)',
                'order' => 1,
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // رسوم المسافة
            [
                'key' => 'delivery_distance_fee_per_km',
                'value' => '0.5',
                'group' => 'delivery',
                'type' => 'number',
                'label' => 'رسوم المسافة لكل كيلومتر',
                'description' => 'الرسوم الإضافية لكل كيلومتر (بالريال)',
                'order' => 2,
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // مضاعفات أنواع المركبات
            [
                'key' => 'delivery_car_multiplier',
                'value' => '1.0',
                'group' => 'delivery',
                'type' => 'number',
                'label' => 'مضاعف رسوم السيارة',
                'description' => 'مضاعف الرسوم للسيارة (1.0 = 100% من الرسوم)',
                'order' => 3,
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'delivery_motorcycle_multiplier',
                'value' => '0.8',
                'group' => 'delivery',
                'type' => 'number',
                'label' => 'مضاعف رسوم الدراجة النارية',
                'description' => 'مضاعف الرسوم للدراجة النارية (0.8 = 80% من الرسوم)',
                'order' => 4,
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'delivery_bicycle_multiplier',
                'value' => '0.6',
                'group' => 'delivery',
                'type' => 'number',
                'label' => 'مضاعف رسوم الدراجة الهوائية',
                'description' => 'مضاعف الرسوم للدراجة الهوائية (0.6 = 60% من الرسوم)',
                'order' => 5,
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // الحد الأدنى والأقصى
            [
                'key' => 'delivery_min_fee',
                'value' => '5.00',
                'group' => 'delivery',
                'type' => 'number',
                'label' => 'الحد الأدنى لرسوم التوصيل',
                'description' => 'الحد الأدنى لرسوم التوصيل بالريال (حتى لو كانت الحسابات أقل)',
                'order' => 6,
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'delivery_max_fee',
                'value' => '50.00',
                'group' => 'delivery',
                'type' => 'number',
                'label' => 'الحد الأقصى لرسوم التوصيل',
                'description' => 'الحد الأقصى لرسوم التوصيل بالريال (حتى لو كانت الحسابات أكثر)',
                'order' => 7,
                'is_public' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($deliveryFeeSettings as $setting) {
            // التحقق من عدم وجود الإعداد مسبقاً
            $exists = DB::table('settings')->where('key', $setting['key'])->exists();
            if (!$exists) {
                DB::table('settings')->insert($setting);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // حذف الإعدادات المضافة
        $keysToDelete = [
            'delivery_base_fee',
            'delivery_distance_fee_per_km',
            'delivery_car_multiplier',
            'delivery_motorcycle_multiplier',
            'delivery_bicycle_multiplier',
            'delivery_min_fee',
            'delivery_max_fee',
        ];

        DB::table('settings')->whereIn('key', $keysToDelete)->delete();
    }
};
