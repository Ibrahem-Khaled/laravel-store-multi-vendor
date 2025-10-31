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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action'); // created, updated, deleted, etc.
            $table->string('auditable_type'); // Model class name
            $table->unsignedBigInteger('auditable_id'); // Model ID
            $table->json('old_values')->nullable(); // القيم القديمة قبل التعديل
            $table->json('new_values')->nullable(); // القيم الجديدة بعد التعديل
            $table->json('changed_fields')->nullable(); // الحقول التي تم تغييرها
            $table->longText('description')->nullable(); // وصف العملية
            $table->string('ip_address', 45)->nullable(); // عنوان IP
            $table->text('user_agent')->nullable(); // معلومات المتصفح
            $table->string('url')->nullable(); // رابط الطلب
            $table->string('method')->nullable(); // HTTP Method
            $table->timestamps();

            // فهارس للبحث السريع
            $table->index(['user_id', 'created_at']);
            $table->index(['auditable_type', 'auditable_id']);
            $table->index(['action', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};

