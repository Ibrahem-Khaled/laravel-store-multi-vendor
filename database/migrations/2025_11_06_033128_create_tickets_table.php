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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique(); // رقم التذكرة الفريد
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('ticket_categories')->onDelete('restrict');
            $table->string('subject'); // موضوع التذكرة
            $table->text('message'); // رسالة المستخدم
            $table->enum('status', ['pending', 'open', 'in_progress', 'resolved', 'closed'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->string('attachment')->nullable(); // ملف مرفق
            $table->text('response')->nullable(); // رد الدعم الفني
            $table->foreignId('responded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('responded_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->integer('rating')->nullable(); // تقييم من 1-5
            $table->text('feedback')->nullable(); // تعليق على الخدمة
            $table->timestamps();

            // فهارس للأداء
            $table->index('user_id');
            $table->index('status');
            $table->index('priority');
            $table->index('category_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
