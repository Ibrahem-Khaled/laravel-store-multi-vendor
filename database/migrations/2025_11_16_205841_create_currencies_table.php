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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique()->comment('رمز العملة (USD, YER_NEW, etc.)');
            $table->string('name_ar', 100)->comment('اسم العملة بالعربية');
            $table->string('name_en', 100)->comment('اسم العملة بالإنجليزية');
            $table->string('symbol', 10)->comment('رمز العملة ($, ر.ي, etc.)');
            $table->string('symbol_ar', 20)->nullable()->comment('رمز العملة بالعربية');
            $table->decimal('exchange_rate', 15, 4)->default(1.0)->comment('سعر الصرف مقابل الدولار');
            $table->boolean('is_active')->default(true)->comment('هل العملة مفعلة');
            $table->boolean('is_base_currency')->default(false)->comment('هل هي العملة الأساسية');
            $table->timestamps();
            
            $table->index('code');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
