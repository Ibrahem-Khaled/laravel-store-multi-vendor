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
        Schema::create('driver_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null'); // من قام بالتخصيص
            $table->enum('status', ['assigned', 'accepted', 'picked_up', 'delivered', 'cancelled'])->default('assigned');
            $table->enum('assignment_type', ['auto', 'manual'])->default('auto'); // تلقائي أو يدوي
            $table->timestamp('assigned_at');
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->decimal('delivery_fee', 8, 2)->default(0.00);
            $table->json('delivery_notes')->nullable();
            $table->json('confirmation_data')->nullable(); // بيانات التأكيد
            $table->timestamps();

            // Indexes for better performance
            $table->index(['driver_id', 'status']);
            $table->index(['order_id', 'status']);
            $table->index('assigned_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_orders');
    }
};
