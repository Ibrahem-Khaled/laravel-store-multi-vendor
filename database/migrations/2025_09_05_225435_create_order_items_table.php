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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');
            // تثبيت التاجر وقت البيع (denormalized)
            $table->foreignId('merchant_id')->constrained('users')->onDelete('restrict');

            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 12, 2); // سعر المنتج وقت البيع
            // نسبة العمولة وقت البيع (مثلاً 0.15 = 15%)
            $table->decimal('commission_rate', 5, 4)->default(0.15);
            // قيم مشتقة تُحسب عند الإنشاء
            $table->decimal('commission_amount', 12, 2)->default(0);
            $table->decimal('payout_amount', 12, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
