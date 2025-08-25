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
      Schema::create('role_change_requests', function (Blueprint $table) {
            $table->id();

            // ربط الطلب بالمستخدم الذي قدمه
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // الدور المطلوب (يجب أن تكون القيم متطابقة مع الموجودة في جدول users)
            $table->enum('requested_role', ['admin', 'moderator', 'user', 'trader']);

            // حالة الطلب
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            // سبب الطلب (لماذا يريد المستخدم هذا الدور)
            $table->text('reason')->nullable();

            // ملاحظات من المدير (سبب الرفض أو الموافقة)
            $table->text('admin_notes')->nullable();

            // ربط الطلب بالمدير الذي قام بمراجعته (اختياري لكن مفيد)
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_change_requests');
    }
};
