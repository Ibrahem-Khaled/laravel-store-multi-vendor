<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class FlexibleLoginTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // إنشاء مستخدم للاختبار
        $this->user = User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'username' => 'testuser',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+1234567890',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'role' => 'user'
        ]);
    }

    /**
     * اختبار تسجيل الدخول بالبريد الإلكتروني
     */
    public function test_login_with_email()
    {
        $response = $this->postJson('/api/v2/auth/login', [
            'login_field' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'token',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'phone'
                    ]
                ])
                ->assertJson([
                    'status' => 'success',
                    'message' => 'تم تسجيل الدخول بنجاح'
                ]);
    }

    /**
     * اختبار تسجيل الدخول برقم الهاتف
     */
    public function test_login_with_phone()
    {
        $response = $this->postJson('/api/v2/auth/login', [
            'login_field' => '+1234567890',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'token',
                    'user'
                ])
                ->assertJson([
                    'status' => 'success',
                    'message' => 'تم تسجيل الدخول بنجاح'
                ]);
    }

    /**
     * اختبار تسجيل الدخول بكلمة مرور خاطئة
     */
    public function test_login_with_wrong_password()
    {
        $response = $this->postJson('/api/v2/auth/login', [
            'login_field' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401)
                ->assertJson([
                    'error' => 'بيانات الاعتماد غير صحيحة'
                ]);
    }

    /**
     * اختبار تسجيل الدخول ببريد إلكتروني غير موجود
     */
    public function test_login_with_nonexistent_email()
    {
        $response = $this->postJson('/api/v2/auth/login', [
            'login_field' => 'nonexistent@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(401)
                ->assertJsonStructure([
                    'error',
                    'details'
                ]);
    }

    /**
     * اختبار تسجيل الدخول برقم هاتف غير موجود
     */
    public function test_login_with_nonexistent_phone()
    {
        $response = $this->postJson('/api/v2/auth/login', [
            'login_field' => '+9999999999',
            'password' => 'password123'
        ]);

        $response->assertStatus(401)
                ->assertJsonStructure([
                    'error',
                    'details'
                ]);
    }

    /**
     * اختبار تسجيل الدخول بمعرف غير صالح
     */
    public function test_login_with_invalid_field()
    {
        $response = $this->postJson('/api/v2/auth/login', [
            'login_field' => 'invalid_field',
            'password' => 'password123'
        ]);

        $response->assertStatus(400)
                ->assertJson([
                    'error' => 'يرجى إدخال بريد إلكتروني صالح أو رقم هاتف صالح'
                ]);
    }

    /**
     * اختبار تسجيل الدخول مع بيانات ناقصة
     */
    public function test_login_with_missing_data()
    {
        $response = $this->postJson('/api/v2/auth/login', [
            'login_field' => 'test@example.com'
            // كلمة المرور مفقودة
        ]);

        $response->assertStatus(400)
                ->assertJson([
                    'error' => 'يرجى إدخال معرف المستخدم وكلمة المرور'
                ]);
    }

    /**
     * اختبار تسجيل الدخول مع مستخدم غير نشط
     */
    public function test_login_with_inactive_user()
    {
        // إنشاء مستخدم غير نشط
        $inactiveUser = User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'username' => 'inactiveuser',
            'name' => 'Inactive User',
            'email' => 'inactive@example.com',
            'phone' => '+9876543210',
            'password' => Hash::make('password123'),
            'status' => 'inactive',
            'role' => 'user'
        ]);

        $response = $this->postJson('/api/v2/auth/login', [
            'login_field' => 'inactive@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(403)
                ->assertJson([
                    'error' => 'حسابك غير نشط. يرجى التواصل مع الإدارة'
                ]);
    }

    /**
     * اختبار تنسيقات مختلفة من أرقام الهواتف
     */
    public function test_phone_number_formats()
    {
        $phoneFormats = [
            '+1234567890',
            '1234567890',
            '(123) 456-7890',
            '123-456-7890',
            '123 456 7890'
        ];

        foreach ($phoneFormats as $phone) {
            // تحديث رقم هاتف المستخدم
            $this->user->update(['phone' => $phone]);

            $response = $this->postJson('/api/v2/auth/login', [
                'login_field' => $phone,
                'password' => 'password123'
            ]);

            $response->assertStatus(200)
                    ->assertJson([
                        'status' => 'success'
                    ]);
        }
    }
}
