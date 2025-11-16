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
        Schema::create('driver_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('drivers')->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained('driver_invoices')->onDelete('set null');
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            
            // معلومات الدفعة
            $table->string('payment_number')->unique()->comment('رقم الدفعة');
            $table->decimal('amount', 12, 2)->comment('مبلغ الدفعة');
            $table->enum('payment_method', ['cash', 'bank_transfer', 'wallet', 'other'])->default('bank_transfer');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed', 'cancelled'])->default('pending');
            
            // معلومات الدفع
            $table->string('reference_number')->nullable()->comment('رقم المرجع (رقم التحويل)');
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->date('payment_date')->comment('تاريخ الدفع');
            
            // ملاحظات
            $table->text('notes')->nullable();
            $table->json('payment_details')->nullable()->comment('تفاصيل إضافية');
            
            $table->timestamps();
            
            // Indexes
            $table->index('driver_id');
            $table->index('invoice_id');
            $table->index('payment_number');
            $table->index('status');
            $table->index('payment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_payments');
    }
};
