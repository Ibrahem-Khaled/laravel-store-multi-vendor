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

            // --- معلومات الطلب الأساسية ---
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('requested_role', ['admin', 'moderator', 'user', 'trader']);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('reason')->nullable(); // سبب عام للطلب

            // --- معلومات شخصية للتحقق (تُملأ إذا كان الدور المطلوب "تاجر") ---
            $table->string('full_name')->nullable(); // الاسم الكامل كما في الهوية
            $table->string('national_id_number')->nullable(); // رقم الهوية الوطنية أو الإقامة
            $table->string('national_id_image_path')->nullable(); // مسار صورة الهوية

            // --- معلومات النشاط التجاري (تُملأ إذا كان الدور المطلوب "تاجر") ---
            $table->string('store_name')->nullable(); // اسم المتجر المقترح
            $table->text('store_description')->nullable(); // نبذة عن المتجر
            $table->string('commercial_registration_number')->nullable(); // رقم السجل التجاري
            $table->string('commercial_registration_image_path')->nullable(); // مسار صورة السجل التجاري
            $table->string('bank_account_number')->nullable(); // رقم الحساب البنكي (IBAN) لاستقبال المدفوعات
            $table->string('bank_name')->nullable(); // اسم البنك

            // --- معلومات المراجعة بواسطة المدير ---
            $table->text('admin_notes')->nullable();
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
