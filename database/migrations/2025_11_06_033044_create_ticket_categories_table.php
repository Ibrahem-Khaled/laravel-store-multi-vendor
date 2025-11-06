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
        Schema::create('ticket_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // الاسم (مثل: تقني، مالي، عام)
            $table->string('name_en')->nullable(); // الاسم بالإنجليزية
            $table->string('icon')->nullable(); // أيقونة
            $table->text('description')->nullable(); // الوصف
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // إدخال الفئات الافتراضية
        $categories = [
            [
                'name' => 'دعم فني',
                'name_en' => 'Technical Support',
                'icon' => 'fas fa-laptop-code',
                'description' => 'المشاكل الفنية والتقنية',
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'استفسارات مالية',
                'name_en' => 'Financial Inquiries',
                'icon' => 'fas fa-money-bill-wave',
                'description' => 'الاستفسارات المتعلقة بالدفع والمالية',
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'استفسارات عامة',
                'name_en' => 'General Inquiries',
                'icon' => 'fas fa-question-circle',
                'description' => 'الاستفسارات العامة',
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'الشكاوى',
                'name_en' => 'Complaints',
                'icon' => 'fas fa-exclamation-triangle',
                'description' => 'تقديم الشكاوى',
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'اقتراحات',
                'name_en' => 'Suggestions',
                'icon' => 'fas fa-lightbulb',
                'description' => 'تقديم الاقتراحات',
                'order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('ticket_categories')->insert($categories);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_categories');
    }
};
