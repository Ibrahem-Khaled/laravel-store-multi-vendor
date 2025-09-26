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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('license_number')->unique();
            $table->string('vehicle_type'); // car, motorcycle, bicycle
            $table->string('vehicle_model');
            $table->string('vehicle_plate_number');
            $table->string('phone_number');
            $table->string('city');
            $table->string('neighborhood');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_supervisor')->default(false); // مشرف على السواقين
            $table->integer('current_orders_count')->default(0);
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('total_deliveries')->default(0);
            $table->json('working_hours')->nullable(); // {"monday": {"start": "08:00", "end": "18:00"}, ...}
            $table->json('service_areas')->nullable(); // المناطق التي يخدمها
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
