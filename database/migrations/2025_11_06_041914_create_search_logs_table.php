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
        Schema::create('search_logs', function (Blueprint $table) {
            $table->id();
            $table->string('query'); // نص البحث
            $table->string('type')->default('product'); // نوع البحث: product, merchant, all
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('ip_address', 45)->nullable();
            $table->integer('results_count')->default(0); // عدد النتائج
            $table->timestamps();

            // فهارس للأداء
            $table->index('query');
            $table->index('type');
            $table->index('created_at');
        });

        // جدول لتتبع المنتجات الأكثر بحثاً
        Schema::create('popular_searches', function (Blueprint $table) {
            $table->id();
            $table->string('query'); // نص البحث
            $table->string('type')->default('product'); // نوع البحث
            $table->integer('search_count')->default(1); // عدد مرات البحث
            $table->integer('results_count')->default(0); // متوسط عدد النتائج
            $table->timestamp('last_searched_at')->nullable(); // آخر مرة تم البحث عنها
            $table->timestamps();

            // فهرس فريد لمنع التكرار
            $table->unique(['query', 'type']);
            $table->index('search_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('popular_searches');
        Schema::dropIfExists('search_logs');
    }
};
