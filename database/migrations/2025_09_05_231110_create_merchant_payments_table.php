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
        Schema::create('merchant_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('merchant_id')->constrained('users')->onDelete('cascade');
            // type: payout_to_merchant (منصّة -> تاجر) | collection_from_merchant (تاجر -> منصّة)
            $table->enum('type', ['payout_to_merchant', 'collection_from_merchant']);
            $table->decimal('amount', 12, 2);
            $table->string('method')->nullable(); // bank_transfer, cash, gateway_settlement...
            $table->string('reference')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchant_payments');
    }
};
