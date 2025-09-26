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
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->string('city')->nullable()->after('address_line_1');
            $table->string('neighborhood')->nullable()->after('city');
            $table->string('address')->nullable()->after('neighborhood'); // Full address text
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            $table->dropColumn(['city', 'neighborhood', 'address']);
        });
    }
};
