<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('loyalty_points_used')->default(0)->comment('النقاط المستخدمة في الطلب');
            $table->decimal('loyalty_discount', 10, 2)->default(0)->comment('الخصم من النقاط');
            $table->integer('loyalty_points_earned')->default(0)->comment('النقاط المكتسبة من الطلب');
            $table->decimal('platform_contribution', 10, 2)->default(0)->comment('مساهمة المنصة في النقاط');
            $table->decimal('customer_contribution', 10, 2)->default(0)->comment('مساهمة العميل في النقاط');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'loyalty_points_used',
                'loyalty_discount',
                'loyalty_points_earned',
                'platform_contribution',
                'customer_contribution'
            ]);
        });
    }
};
