<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class ImprovedRegistrationErrorTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * اختبار رسائل الخطأ المحسنة للتسجيل بدون اسم
     */
    public function test_register_without_name_shows_clear_error()
    {
        $response = $this->postJson('/api/v2/auth/register', [
            'phone' => '+1234567890',
            'password' => 'password123',
            'gender' => 'male'
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'errors' => [
                        'name'
                    ],
                    'error_summary'
                ])
                ->assertJson([
                    'status' => 'error',
                    'message' => 'فشل في إنشاء الحساب'
                ])
                ->assertJsonFragment([
                    'name' => ['الاسم مطلوب']
                ]);
    }

    /**
     * اختبار رسائل الخطأ المحسنة للتسجيل بدون رقم هاتف
     */
    public function test_register_without_phone_shows_clear_error()
    {
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => 'أحمد محمد',
            'password' => 'password123',
            'gender' => 'male'
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'errors' => [
                        'phone'
                    ],
                    'error_summary'
                ])
                ->assertJson([
                    'status' => 'error',
                    'message' => 'فشل في إنشاء الحساب'
                ])
                ->assertJsonFragment([
                    'phone' => ['رقم الهاتف مطلوب']
                ]);
    }

    /**
     * اختبار رسائل الخطأ المحسنة للتسجيل بدون كلمة مرور
     */
    public function test_register_without_password_shows_clear_error()
    {
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => 'أحمد محمد',
            'phone' => '+1234567890',
            'gender' => 'male'
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'errors' => [
                        'password'
                    ],
                    'error_summary'
                ])
                ->assertJson([
                    'status' => 'error',
                    'message' => 'فشل في إنشاء الحساب'
                ])
                ->assertJsonFragment([
                    'password' => ['كلمة المرور مطلوبة']
                ]);
    }

    /**
     * اختبار رسائل الخطأ المحسنة لكلمة مرور قصيرة
     */
    public function test_register_with_short_password_shows_clear_error()
    {
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => 'أحمد محمد',
            'phone' => '+1234567890',
            'password' => '123',
            'gender' => 'male'
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'errors' => [
                        'password'
                    ],
                    'error_summary'
                ])
                ->assertJson([
                    'status' => 'error',
                    'message' => 'فشل في إنشاء الحساب'
                ])
                ->assertJsonFragment([
                    'password' => ['كلمة المرور يجب أن تكون 6 أحرف على الأقل']
                ]);
    }

    /**
     * اختبار رسائل الخطأ المحسنة لبريد إلكتروني غير صالح
     */
    public function test_register_with_invalid_email_shows_clear_error()
    {
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => 'أحمد محمد',
            'email' => 'invalid-email',
            'phone' => '+1234567890',
            'password' => 'password123',
            'gender' => 'male'
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'errors' => [
                        'email'
                    ],
                    'error_summary'
                ])
                ->assertJson([
                    'status' => 'error',
                    'message' => 'فشل في إنشاء الحساب'
                ])
                ->assertJsonFragment([
                    'email' => ['البريد الإلكتروني غير صالح']
                ]);
    }

    /**
     * اختبار رسائل الخطأ المحسنة لرقم هاتف مكرر
     */
    public function test_register_with_duplicate_phone_shows_clear_error()
    {
        // إنشاء مستخدم أول
        User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'username' => 'existinguser',
            'name' => 'Existing User',
            'phone' => '+1234567890',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'role' => 'user'
        ]);

        // محاولة إنشاء مستخدم بنفس رقم الهاتف
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => 'New User',
            'phone' => '+1234567890',
            'password' => 'password123',
            'gender' => 'male'
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'errors' => [
                        'phone'
                    ],
                    'error_summary'
                ])
                ->assertJson([
                    'status' => 'error',
                    'message' => 'فشل في إنشاء الحساب'
                ])
                ->assertJsonFragment([
                    'phone' => ['رقم الهاتف مستخدم بالفعل']
                ]);
    }

    /**
     * اختبار رسائل الخطأ المحسنة لبريد إلكتروني مكرر
     */
    public function test_register_with_duplicate_email_shows_clear_error()
    {
        // إنشاء مستخدم أول
        User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'username' => 'existinguser',
            'name' => 'Existing User',
            'email' => 'test@example.com',
            'phone' => '+1111111111',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'role' => 'user'
        ]);

        // محاولة إنشاء مستخدم بنفس البريد الإلكتروني
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => 'New User',
            'email' => 'test@example.com',
            'phone' => '+2222222222',
            'password' => 'password123',
            'gender' => 'male'
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'errors' => [
                        'email'
                    ],
                    'error_summary'
                ])
                ->assertJson([
                    'status' => 'error',
                    'message' => 'فشل في إنشاء الحساب'
                ])
                ->assertJsonFragment([
                    'email' => ['البريد الإلكتروني مستخدم بالفعل']
                ]);
    }

    /**
     * اختبار رسائل الخطأ المحسنة لجنس غير صالح
     */
    public function test_register_with_invalid_gender_shows_clear_error()
    {
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => 'أحمد محمد',
            'phone' => '+1234567890',
            'password' => 'password123',
            'gender' => 'invalid'
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'errors' => [
                        'gender'
                    ],
                    'error_summary'
                ])
                ->assertJson([
                    'status' => 'error',
                    'message' => 'فشل في إنشاء الحساب'
                ])
                ->assertJsonFragment([
                    'gender' => ['الجنس يجب أن يكون ذكر أو أنثى']
                ]);
    }

    /**
     * اختبار رسائل الخطأ المحسنة لدور غير صالح
     */
    public function test_register_with_invalid_role_shows_clear_error()
    {
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => 'أحمد محمد',
            'phone' => '+1234567890',
            'password' => 'password123',
            'gender' => 'male',
            'role' => 'invalid'
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'errors' => [
                        'role'
                    ],
                    'error_summary'
                ])
                ->assertJson([
                    'status' => 'error',
                    'message' => 'فشل في إنشاء الحساب'
                ])
                ->assertJsonFragment([
                    'role' => ['الدور يجب أن يكون مستخدم أو تاجر']
                ]);
    }

    /**
     * اختبار رسائل الخطأ المحسنة لأخطاء متعددة
     */
    public function test_register_with_multiple_errors_shows_clear_summary()
    {
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => '', // اسم فارغ
            'email' => 'invalid-email', // بريد غير صالح
            'phone' => '', // رقم هاتف فارغ
            'password' => '123', // كلمة مرور قصيرة
            'gender' => 'invalid' // جنس غير صالح
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'errors',
                    'error_summary'
                ])
                ->assertJson([
                    'status' => 'error',
                    'message' => 'فشل في إنشاء الحساب'
                ]);

        $responseData = $response->json();

        // التحقق من وجود ملخص الأخطاء
        $this->assertArrayHasKey('error_summary', $responseData);
        $this->assertIsArray($responseData['error_summary']);
        $this->assertNotEmpty($responseData['error_summary']);

        // التحقق من وجود أخطاء متعددة
        $this->assertArrayHasKey('errors', $responseData);
        $this->assertArrayHasKey('name', $responseData['errors']);
        $this->assertArrayHasKey('email', $responseData['errors']);
        $this->assertArrayHasKey('phone', $responseData['errors']);
        $this->assertArrayHasKey('password', $responseData['errors']);
        $this->assertArrayHasKey('gender', $responseData['errors']);
    }

    /**
     * اختبار التسجيل الناجح مع رسالة واضحة
     */
    public function test_successful_registration_shows_clear_success_message()
    {
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => 'أحمد محمد',
            'phone' => '+1234567890',
            'password' => 'password123',
            'gender' => 'male'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'gender',
                        'role',
                        'status',
                        'created_at'
                    ],
                    'token'
                ])
                ->assertJson([
                    'status' => 'success',
                    'message' => 'تم إنشاء الحساب بنجاح'
                ]);
    }

    /**
     * اختبار التسجيل الناجح مع بريد إلكتروني ورقم هاتف
     */
    public function test_successful_registration_with_email_and_phone()
    {
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => 'فاطمة أحمد',
            'email' => 'fatima@example.com',
            'phone' => '+9876543210',
            'password' => 'password123',
            'gender' => 'female',
            'role' => 'user'
        ]);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'gender',
                        'role',
                        'status',
                        'created_at'
                    ],
                    'token'
                ])
                ->assertJson([
                    'status' => 'success',
                    'message' => 'تم إنشاء الحساب بنجاح'
                ])
                ->assertJsonFragment([
                    'name' => 'فاطمة أحمد',
                    'email' => 'fatima@example.com',
                    'phone' => '+9876543210',
                    'gender' => 'female',
                    'role' => 'user',
                    'status' => 'active'
                ]);
    }
}
