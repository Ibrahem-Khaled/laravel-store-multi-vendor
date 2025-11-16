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
        Schema::create('currency_exchange_rate_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id')->constrained('currencies')->onDelete('cascade');
            $table->decimal('exchange_rate', 15, 4)->comment('سعر الصرف الجديد');
            $table->decimal('previous_rate', 15, 4)->nullable()->comment('سعر الصرف السابق');
            $table->decimal('change_percentage', 5, 2)->nullable()->comment('نسبة التغيير');
            $table->foreignId('updated_by')->constrained('users')->onDelete('restrict')->comment('المستخدم الذي قام بالتحديث');
            $table->text('notes')->nullable()->comment('ملاحظات');
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('currency_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_exchange_rate_history');
    }
};
