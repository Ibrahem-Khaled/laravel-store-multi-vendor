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
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade')->comment('رقم الطلب');
            $table->foreignId('order_item_id')->nullable()->constrained()->onDelete('cascade')->comment('عنصر الطلب المراد إرجاعه (null = كل الطلب)');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('العميل');
            $table->enum('type', ['return', 'refund', 'replacement'])->default('return')->comment('نوع الطلب: إرجاع، استرداد، استبدال');
            $table->enum('status', ['pending', 'approved', 'rejected', 'processing', 'completed', 'cancelled'])->default('pending')->comment('حالة الطلب');
            $table->text('reason')->comment('سبب الإرجاع');
            $table->text('customer_notes')->nullable()->comment('ملاحظات العميل');
            $table->text('admin_notes')->nullable()->comment('ملاحظات الإدارة');
            $table->decimal('refund_amount', 12, 2)->nullable()->comment('مبلغ الاسترداد');
            $table->enum('refund_method', ['original_payment', 'wallet', 'bank_transfer'])->nullable()->comment('طريقة الاسترداد');
            $table->foreignId('replacement_order_id')->nullable()->constrained('orders')->onDelete('set null')->comment('رقم طلب الاستبدال');
            $table->json('images')->nullable()->comment('صور المنتج المعيب');
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null')->comment('معالج الطلب');
            $table->timestamp('processed_at')->nullable()->comment('تاريخ المعالجة');
            $table->timestamp('approved_at')->nullable()->comment('تاريخ الموافقة');
            $table->timestamp('rejected_at')->nullable()->comment('تاريخ الرفض');
            $table->timestamp('completed_at')->nullable()->comment('تاريخ الإكمال');
            $table->timestamps();

            $table->index(['order_id', 'status']);
            $table->index(['user_id', 'status']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
