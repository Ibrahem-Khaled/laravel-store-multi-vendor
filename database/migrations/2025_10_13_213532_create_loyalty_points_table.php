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
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('total_points')->default(0)->comment('إجمالي النقاط المتاحة');
            $table->integer('used_points')->default(0)->comment('النقاط المستخدمة');
            $table->integer('expired_points')->default(0)->comment('النقاط المنتهية الصلاحية');
            $table->decimal('platform_contribution', 10, 2)->default(0)->comment('مساهمة المنصة في النقاط');
            $table->decimal('customer_contribution', 10, 2)->default(0)->comment('مساهمة العميل في النقاط');
            $table->timestamp('last_earned_at')->nullable()->comment('آخر مرة تم كسب نقاط');
            $table->timestamp('last_used_at')->nullable()->comment('آخر مرة تم استخدام نقاط');
            $table->timestamps();

            // فهرسة لتحسين الأداء
            $table->index(['user_id', 'total_points']);
            $table->index('last_earned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_points');
    }
};
