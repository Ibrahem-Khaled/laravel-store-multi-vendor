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
        Schema::create('driver_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->onDelete('cascade');
            $table->foreignId('driver_order_id')->nullable()->constrained('driver_orders')->onDelete('set null');
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('set null');
            
            // المبالغ
            $table->decimal('delivery_fee', 10, 2)->default(0)->comment('رسوم التوصيل الكاملة');
            $table->decimal('driver_commission_percentage', 5, 2)->default(80.00)->comment('نسبة العمولة (مثلاً 80%)');
            $table->decimal('driver_earned_amount', 10, 2)->default(0)->comment('المبلغ المستحق للسواق');
            $table->decimal('platform_fee', 10, 2)->default(0)->comment('رسوم المنصة');
            
            // الحالة
            $table->enum('status', ['pending', 'processed', 'paid', 'cancelled'])->default('pending');
            $table->boolean('is_invoiced')->default(false)->comment('هل تم إضافتها للفاتورة');
            
            // التواريخ
            $table->date('earning_date')->comment('تاريخ الاستحقاق');
            $table->timestamp('processed_at')->nullable()->comment('تاريخ المعالجة');
            $table->timestamp('paid_at')->nullable()->comment('تاريخ الدفع');
            
            // ملاحظات
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('driver_id');
            $table->index('earning_date');
            $table->index('status');
            $table->index('is_invoiced');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_earnings');
    }
};
