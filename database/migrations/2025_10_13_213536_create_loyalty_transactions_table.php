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
        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('loyalty_points_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['earned', 'used', 'expired', 'refunded'])->comment('نوع المعاملة');
            $table->integer('points')->comment('عدد النقاط');
            $table->decimal('amount', 10, 2)->nullable()->comment('المبلغ المرتبط بالنقاط');
            $table->enum('source', ['order', 'manual', 'refund', 'expiry'])->comment('مصدر النقاط');
            $table->string('description')->comment('وصف المعاملة');
            $table->json('metadata')->nullable()->comment('بيانات إضافية');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null')->comment('رقم الطلب المرتبط');
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null')->comment('المعالج');
            $table->timestamp('expires_at')->nullable()->comment('تاريخ انتهاء الصلاحية');
            $table->timestamps();

            // فهرسة لتحسين الأداء
            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'created_at']);
            $table->index('order_id');
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_transactions');
    }
};
