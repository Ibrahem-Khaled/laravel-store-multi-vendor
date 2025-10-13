<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class FlexibleRegistrationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * اختبار التسجيل برقم هاتف فقط (بدون بريد إلكتروني)
     */
    public function test_register_with_phone_only()
    {
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => 'Test User',
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
                        'status'
                    ],
                    'token'
                ])
                ->assertJson([
                    'status' => 'success',
                    'message' => 'تم إنشاء الحساب بنجاح'
                ]);

        // التحقق من أن المستخدم تم إنشاؤه في قاعدة البيانات
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'phone' => '+1234567890',
            'email' => null,
            'status' => 'active'
        ]);
    }

    /**
     * اختبار التسجيل برقم هاتف وبريد إلكتروني
     */
    public function test_register_with_phone_and_email()
    {
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+1234567890',
            'password' => 'password123',
            'gender' => 'female'
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
                        'status'
                    ],
                    'token'
                ])
                ->assertJson([
                    'status' => 'success',
                    'message' => 'تم إنشاء الحساب بنجاح'
                ]);

        // التحقق من أن المستخدم تم إنشاؤه في قاعدة البيانات
        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+1234567890',
            'status' => 'active'
        ]);
    }

    /**
     * اختبار التسجيل بدون رقم هاتف (يجب أن يفشل)
     */
    public function test_register_without_phone_fails()
    {
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'gender' => 'male'
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'error',
                    'details'
                ])
                ->assertJson([
                    'error' => 'بيانات التسجيل غير صحيحة'
                ]);
    }

    /**
     * اختبار التسجيل برقم هاتف مكرر (يجب أن يفشل)
     */
    public function test_register_with_duplicate_phone_fails()
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
                    'error',
                    'details'
                ])
                ->assertJson([
                    'error' => 'بيانات التسجيل غير صحيحة'
                ]);
    }

    /**
     * اختبار التسجيل ببريد إلكتروني مكرر (يجب أن يفشل)
     */
    public function test_register_with_duplicate_email_fails()
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
                    'error',
                    'details'
                ])
                ->assertJson([
                    'error' => 'بيانات التسجيل غير صحيحة'
                ]);
    }

    /**
     * اختبار التسجيل بدون اسم (يجب أن يفشل)
     */
    public function test_register_without_name_fails()
    {
        $response = $this->postJson('/api/v2/auth/register', [
            'phone' => '+1234567890',
            'password' => 'password123',
            'gender' => 'male'
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'error',
                    'details'
                ])
                ->assertJson([
                    'error' => 'بيانات التسجيل غير صحيحة'
                ]);
    }

    /**
     * اختبار التسجيل بدون كلمة مرور (يجب أن يفشل)
     */
    public function test_register_without_password_fails()
    {
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => 'Test User',
            'phone' => '+1234567890',
            'gender' => 'male'
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'error',
                    'details'
                ])
                ->assertJson([
                    'error' => 'بيانات التسجيل غير صحيحة'
                ]);
    }

    /**
     * اختبار التسجيل بكلمة مرور قصيرة (يجب أن يفشل)
     */
    public function test_register_with_short_password_fails()
    {
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => 'Test User',
            'phone' => '+1234567890',
            'password' => '123',
            'gender' => 'male'
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'error',
                    'details'
                ])
                ->assertJson([
                    'error' => 'بيانات التسجيل غير صحيحة'
                ]);
    }

    /**
     * اختبار التسجيل ببريد إلكتروني غير صالح (يجب أن يفشل)
     */
    public function test_register_with_invalid_email_fails()
    {
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'phone' => '+1234567890',
            'password' => 'password123',
            'gender' => 'male'
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'error',
                    'details'
                ])
                ->assertJson([
                    'error' => 'بيانات التسجيل غير صحيحة'
                ]);
    }

    /**
     * اختبار التسجيل مع جنس غير صالح (يجب أن يفشل)
     */
    public function test_register_with_invalid_gender_fails()
    {
        $response = $this->postJson('/api/v2/auth/register', [
            'name' => 'Test User',
            'phone' => '+1234567890',
            'password' => 'password123',
            'gender' => 'invalid'
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'error',
                    'details'
                ])
                ->assertJson([
                    'error' => 'بيانات التسجيل غير صحيحة'
                ]);
    }

    /**
     * اختبار تسجيل الدخول بعد التسجيل برقم هاتف فقط
     */
    public function test_login_after_register_with_phone_only()
    {
        // تسجيل مستخدم جديد برقم هاتف فقط
        $registerResponse = $this->postJson('/api/v2/auth/register', [
            'name' => 'Test User',
            'phone' => '+1234567890',
            'password' => 'password123',
            'gender' => 'male'
        ]);

        $registerResponse->assertStatus(201);

        // تسجيل الدخول برقم الهاتف
        $loginResponse = $this->postJson('/api/v2/auth/login', [
            'login_field' => '+1234567890',
            'password' => 'password123'
        ]);

        $loginResponse->assertStatus(200)
                     ->assertJson([
                         'status' => 'success',
                         'message' => 'تم تسجيل الدخول بنجاح'
                     ]);
    }

    /**
     * اختبار تسجيل الدخول بعد التسجيل ببريد إلكتروني ورقم هاتف
     */
    public function test_login_after_register_with_email_and_phone()
    {
        // تسجيل مستخدم جديد ببريد إلكتروني ورقم هاتف
        $registerResponse = $this->postJson('/api/v2/auth/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '+1234567890',
            'password' => 'password123',
            'gender' => 'female'
        ]);

        $registerResponse->assertStatus(201);

        // تسجيل الدخول بالبريد الإلكتروني
        $loginResponse1 = $this->postJson('/api/v2/auth/login', [
            'login_field' => 'test@example.com',
            'password' => 'password123'
        ]);

        $loginResponse1->assertStatus(200)
                      ->assertJson([
                          'status' => 'success',
                          'message' => 'تم تسجيل الدخول بنجاح'
                      ]);

        // تسجيل الدخول برقم الهاتف
        $loginResponse2 = $this->postJson('/api/v2/auth/login', [
            'login_field' => '+1234567890',
            'password' => 'password123'
        ]);

        $loginResponse2->assertStatus(200)
                      ->assertJson([
                          'status' => 'success',
                          'message' => 'تم تسجيل الدخول بنجاح'
                      ]);
    }
}
