<?php

namespace Tests\Feature;

use App\Models\LoyaltyPoints;
use App\Models\LoyaltyTransaction;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoyaltySystemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // إنشاء مستخدم للاختبار
        $this->user = User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'username' => 'testuser' . uniqid(),
            'name' => 'Test User',
            'email' => 'test' . uniqid() . '@example.com',
            'phone' => '+1234567890',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'role' => 'user'
        ]);

        $this->token = JWTAuth::fromUser($this->user);
    }

    /** @test */
    public function user_can_get_loyalty_points()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->getJson('/api/v2/loyalty/points');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        'user_id',
                        'total_points',
                        'available_points',
                        'used_points',
                        'expired_points',
                        'platform_contribution',
                        'customer_contribution',
                        'total_contribution',
                        'last_earned_at',
                        'last_used_at'
                    ]
                ])
                ->assertJson([
                    'status' => 'success',
                    'data' => [
                        'user_id' => $this->user->id,
                        'total_points' => 0,
                        'available_points' => 0,
                        'used_points' => 0,
                        'expired_points' => 0,
                        'platform_contribution' => 0,
                        'customer_contribution' => 0,
                        'total_contribution' => 0
                    ]
                ]);
    }

    /** @test */
    public function user_can_get_loyalty_transactions()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->getJson('/api/v2/loyalty/transactions');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data',
                    'pagination' => [
                        'current_page',
                        'per_page',
                        'total',
                        'last_page'
                    ]
                ])
                ->assertJson([
                    'status' => 'success'
                ]);
    }

    /** @test */
    public function admin_can_add_loyalty_points()
    {
        // إنشاء مشرف
        $admin = User::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'username' => 'admin' . uniqid(),
            'name' => 'Admin User',
            'email' => 'admin' . uniqid() . '@example.com',
            'phone' => '+1234567891',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'role' => 'admin'
        ]);

        $adminToken = JWTAuth::fromUser($admin);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken,
            'Accept' => 'application/json'
        ])->postJson('/api/v2/loyalty/add', [
            'user_id' => $this->user->id,
            'points' => 100,
            'platform_contribution' => 70.00,
            'customer_contribution' => 30.00,
            'description' => 'نقاط مكافأة للعميل'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'user_id',
                        'user_name',
                        'points_added',
                        'platform_contribution',
                        'customer_contribution',
                        'total_points',
                        'available_points'
                    ]
                ])
                ->assertJson([
                    'status' => 'success',
                    'message' => 'تم إضافة النقاط بنجاح',
                    'data' => [
                        'user_id' => $this->user->id,
                        'points_added' => 100,
                        'platform_contribution' => 70.00,
                        'customer_contribution' => 30.00,
                        'total_points' => 100,
                        'available_points' => 100
                    ]
                ]);
    }

    /** @test */
    public function user_can_use_loyalty_points()
    {
        // إضافة نقاط للمستخدم أولاً
        $loyaltyPoints = LoyaltyPoints::create([
            'user_id' => $this->user->id,
            'total_points' => 100,
            'used_points' => 0,
            'expired_points' => 0,
            'platform_contribution' => 70.00,
            'customer_contribution' => 30.00,
        ]);

        // إنشاء عنوان للمستخدم
        $address = \App\Models\UserAddress::create([
            'user_id' => $this->user->id,
            'address_line_1' => '123 Test Street',
            'city' => 'Test City',
            'neighborhood' => 'Test Neighborhood',
            'address' => '123 Test Street, Test City',
        ]);

        // إنشاء طلب للاختبار
        $order = Order::create([
            'user_id' => $this->user->id,
            'order_number' => 'ORD-001',
            'grand_total' => 50.00,
            'status' => 'pending',
            'payment_method' => 'credit_card',
            'user_address_id' => $address->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/v2/loyalty/use', [
            'points' => 50,
            'order_id' => $order->id
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'points_used',
                        'points_value',
                        'remaining_points',
                        'order_total'
                    ]
                ])
                ->assertJson([
                    'status' => 'success',
                    'message' => 'تم استخدام النقاط بنجاح',
                    'data' => [
                        'points_used' => 50,
                        'points_value' => 0.50,
                        'remaining_points' => 50,
                        'order_total' => 49.50
                    ]
                ]);
    }

    /** @test */
    public function user_cannot_use_more_points_than_available()
    {
        // إضافة نقاط للمستخدم أولاً
        $loyaltyPoints = LoyaltyPoints::create([
            'user_id' => $this->user->id,
            'total_points' => 50,
            'used_points' => 0,
            'expired_points' => 0,
            'platform_contribution' => 35.00,
            'customer_contribution' => 15.00,
        ]);

        // إنشاء عنوان للمستخدم
        $address = \App\Models\UserAddress::create([
            'user_id' => $this->user->id,
            'address_line_1' => '123 Test Street',
            'city' => 'Test City',
            'neighborhood' => 'Test Neighborhood',
            'address' => '123 Test Street, Test City',
        ]);

        // إنشاء طلب للاختبار
        $order = Order::create([
            'user_id' => $this->user->id,
            'order_number' => 'ORD-002',
            'grand_total' => 100.00,
            'status' => 'pending',
            'payment_method' => 'credit_card',
            'user_address_id' => $address->id,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/v2/loyalty/use', [
            'points' => 100, // أكثر من المتاح
            'order_id' => $order->id
        ]);

        $response->assertStatus(400)
                ->assertJson([
                    'status' => 'error',
                    'message' => 'النقاط المتاحة غير كافية',
                    'available_points' => 50
                ]);
    }

    /** @test */
    public function loyalty_points_calculated_on_order_completion()
    {
        // إنشاء عنوان للمستخدم
        $address = \App\Models\UserAddress::create([
            'user_id' => $this->user->id,
            'address_line_1' => '123 Test Street',
            'city' => 'Test City',
            'neighborhood' => 'Test Neighborhood',
            'address' => '123 Test Street, Test City',
        ]);

        // إنشاء طلب مكتمل
        $order = Order::create([
            'user_id' => $this->user->id,
            'order_number' => 'ORD-003',
            'grand_total' => 100.00,
            'status' => 'completed',
            'payment_method' => 'credit_card',
            'user_address_id' => $address->id,
        ]);

        // حساب نقاط الولاء
        $result = \App\Http\Controllers\api\LoyaltyController::calculateLoyaltyPoints($order);

        $this->assertNotNull($result);
        $this->assertEquals(100, $result['points']);
        $this->assertEquals(70.00, $result['platform_contribution']);
        $this->assertEquals(30.00, $result['customer_contribution']);

        // التحقق من إنشاء سجل نقاط الولاء
        $loyaltyPoints = $this->user->loyaltyPoints;
        $this->assertNotNull($loyaltyPoints);
        $this->assertEquals(100, $loyaltyPoints->total_points);
        $this->assertEquals(70.00, $loyaltyPoints->platform_contribution);
        $this->assertEquals(30.00, $loyaltyPoints->customer_contribution);

        // التحقق من إنشاء معاملة الولاء
        $transaction = LoyaltyTransaction::where('user_id', $this->user->id)
            ->where('order_id', $order->id)
            ->first();

        $this->assertNotNull($transaction);
        $this->assertEquals('earned', $transaction->type);
        $this->assertEquals(100, $transaction->points);
        $this->assertEquals(100.00, $transaction->amount);
    }

    /** @test */
    public function non_admin_cannot_add_loyalty_points()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/v2/loyalty/add', [
            'user_id' => $this->user->id,
            'points' => 100,
            'platform_contribution' => 70.00,
            'customer_contribution' => 30.00,
            'description' => 'نقاط مكافأة للعميل'
        ]);

        $response->assertStatus(403)
                ->assertJson([
                    'status' => 'error',
                    'message' => 'ليس لديك صلاحية لإضافة نقاط الولاء'
                ]);
    }

    /** @test */
    public function loyalty_points_validation_works()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/v2/loyalty/use', [
            'points' => -10, // قيمة سالبة
            'order_id' => 999 // طلب غير موجود
        ]);

        $response->assertStatus(422)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'errors'
                ])
                ->assertJsonValidationErrors(['points', 'order_id']);
    }
}
