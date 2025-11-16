<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // تعديل enum status لإضافة 'pending'
        // في MySQL، يجب استخدام ALTER TABLE مباشرة لتعديل ENUM
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `status` ENUM('active', 'inactive', 'banned', 'pending') DEFAULT 'inactive'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // إرجاع enum status إلى القيم الأصلية (بدون pending)
        // تحويل أي pending موجودة إلى inactive أولاً
        DB::table('users')->where('status', 'pending')->update(['status' => 'inactive']);
        
        // إرجاع enum إلى القيم الأصلية
        DB::statement("ALTER TABLE `users` MODIFY COLUMN `status` ENUM('active', 'inactive', 'banned') DEFAULT 'inactive'");
    }
};
