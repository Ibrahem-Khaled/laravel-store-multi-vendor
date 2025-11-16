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
        Schema::create('driver_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            
            // معلومات الفاتورة
            $table->string('invoice_number')->unique()->comment('رقم الفاتورة');
            $table->enum('invoice_type', ['weekly', 'monthly', 'custom'])->default('monthly');
            $table->date('period_start')->comment('بداية الفترة');
            $table->date('period_end')->comment('نهاية الفترة');
            
            // المبالغ
            $table->decimal('total_earnings', 12, 2)->default(0)->comment('إجمالي المستحقات');
            $table->decimal('total_paid', 12, 2)->default(0)->comment('إجمالي المدفوع');
            $table->decimal('total_deductions', 12, 2)->default(0)->comment('إجمالي الخصومات');
            $table->decimal('net_amount', 12, 2)->default(0)->comment('المبلغ الصافي المستحق');
            
            // الحالة
            $table->enum('status', ['draft', 'sent', 'paid', 'cancelled'])->default('draft');
            
            // التواريخ
            $table->date('invoice_date')->comment('تاريخ الفاتورة');
            $table->date('due_date')->nullable()->comment('تاريخ الاستحقاق');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            
            // ملاحظات
            $table->text('notes')->nullable();
            $table->json('earnings_summary')->nullable()->comment('ملخص المستحقات');
            
            $table->timestamps();
            
            // Indexes
            $table->index('driver_id');
            $table->index('invoice_number');
            $table->index('status');
            $table->index('period_start');
            $table->index('period_end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_invoices');
    }
};
