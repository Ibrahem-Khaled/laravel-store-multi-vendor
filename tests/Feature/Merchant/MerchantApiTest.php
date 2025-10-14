<?php

namespace Tests\Feature\Merchant;

use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\MerchantPayment;
use App\Models\MerchantProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MerchantApiTest extends TestCase
{
    use RefreshDatabase;

    protected $merchant;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create merchant user
        $this->merchant = User::factory()->create([
            'status' => 'active',
        ]);

        // Create merchant profile
        MerchantProfile::factory()->create([
            'user_id' => $this->merchant->id,
        ]);

        // Create token
        $this->token = $this->merchant->createToken('test-token')->plainTextToken;
    }

    /**
     * Test merchant dashboard
     */
    public function test_merchant_dashboard()
    {
        // Create test orders
        $this->createTestOrders();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v2/merchant/dashboard');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        'orders' => [
                            'pending',
                            'completed',
                            'cancelled',
                            'total'
                        ],
                        'earnings' => [
                            'total_earnings',
                            'monthly_earnings',
                            'pending_earnings',
                            'total_commission'
                        ],
                        'withdrawals' => [
                            'total_withdrawn',
                            'pending_withdrawals',
                            'available_balance'
                        ],
                        'recent_orders'
                    ]
                ]);
    }

    /**
     * Test pending orders
     */
    public function test_pending_orders()
    {
        $this->createTestOrders();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v2/merchant/orders/pending');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'order_id',
                                'product',
                                'customer',
                                'delivery_address',
                                'quantity',
                                'unit_price',
                                'total_price',
                                'commission_rate',
                                'commission_amount',
                                'payout_amount',
                                'order_status',
                                'payment_method',
                                'order_date'
                            ]
                        ]
                    ]
                ]);
    }

    /**
     * Test order history
     */
    public function test_order_history()
    {
        $this->createTestOrders();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v2/merchant/orders/history');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'order_id',
                                'product',
                                'customer',
                                'quantity',
                                'unit_price',
                                'payout_amount'
                            ]
                        ]
                    ]
                ]);
    }

    /**
     * Test earnings report
     */
    public function test_earnings()
    {
        $this->createTestOrders();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v2/merchant/earnings');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        'summary' => [
                            'total_earnings',
                            'total_commission',
                            'total_orders',
                            'average_order_value'
                        ],
                        'earnings' => [
                            'data' => [
                                '*' => [
                                    'id',
                                    'order_id',
                                    'product',
                                    'quantity',
                                    'unit_price',
                                    'commission_amount',
                                    'payout_amount'
                                ]
                            ]
                        ]
                    ]
                ]);
    }

    /**
     * Test withdrawals
     */
    public function test_withdrawals()
    {
        // Create test withdrawals
        MerchantPayment::factory()->create([
            'merchant_id' => $this->merchant->id,
            'type' => 'withdrawal',
            'amount' => 1000.00,
            'method' => 'bank_transfer',
            'paid_at' => now(),
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v2/merchant/withdrawals');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        'data' => [
                            '*' => [
                                'id',
                                'amount',
                                'method',
                                'reference',
                                'status',
                                'requested_at',
                                'paid_at'
                            ]
                        ]
                    ]
                ]);
    }

    /**
     * Test withdrawal request
     */
    public function test_withdrawal_request()
    {
        // Create completed orders to have balance
        $this->createTestOrders();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json',
        ])->postJson('/api/v2/merchant/withdrawals/request', [
            'amount' => 500.00,
            'method' => 'bank_transfer',
            'reference' => 'REF123456'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'id',
                        'amount',
                        'method',
                        'reference',
                        'status',
                        'requested_at'
                    ]
                ]);
    }

    /**
     * Test withdrawal request with insufficient balance
     */
    public function test_withdrawal_request_insufficient_balance()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json',
        ])->postJson('/api/v2/merchant/withdrawals/request', [
            'amount' => 10000.00,
            'method' => 'bank_transfer',
            'reference' => 'REF123456'
        ]);

        $response->assertStatus(400)
                ->assertJson([
                    'status' => false,
                    'message' => 'Insufficient balance. Available balance: 0'
                ]);
    }

    /**
     * Test merchant profile
     */
    public function test_merchant_profile()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v2/merchant/profile');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        'id',
                        'user_id',
                        'default_commission_rate',
                        'payout_bank_name',
                        'payout_account_name',
                        'payout_account_iban'
                    ]
                ]);
    }

    /**
     * Test update merchant profile
     */
    public function test_update_merchant_profile()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json',
        ])->putJson('/api/v2/merchant/profile', [
            'default_commission_rate' => 12.00,
            'payout_bank_name' => 'New Bank',
            'payout_account_name' => 'New Account',
            'payout_account_iban' => 'NEWIBAN123456789'
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'message',
                    'data' => [
                        'id',
                        'user_id',
                        'default_commission_rate',
                        'payout_bank_name',
                        'payout_account_name',
                        'payout_account_iban'
                    ]
                ]);
    }

    /**
     * Test monthly statistics
     */
    public function test_monthly_stats()
    {
        $this->createTestOrders();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/v2/merchant/monthly-stats?months=6');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'status',
                    'data' => [
                        '*' => [
                            'month',
                            'month_name',
                            'orders_count',
                            'total_earnings',
                            'total_commission',
                            'average_order_value'
                        ]
                    ]
                ]);
    }

    /**
     * Test unauthorized access
     */
    public function test_unauthorized_access()
    {
        $response = $this->getJson('/api/v2/merchant/dashboard');

        $response->assertStatus(401)
                ->assertJson([
                    'status' => false,
                    'code' => 'UNAUTHENTICATED'
                ]);
    }

    /**
     * Test inactive merchant access
     */
    public function test_inactive_merchant_access()
    {
        $inactiveMerchant = User::factory()->create([
            'status' => 'inactive',
        ]);

        $token = $inactiveMerchant->createToken('test-token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v2/merchant/dashboard');

        $response->assertStatus(403)
                ->assertJson([
                    'status' => false,
                    'code' => 'ACCOUNT_DEACTIVATED'
                ]);
    }

    /**
     * Create test orders for testing
     */
    private function createTestOrders()
    {
        $customer = User::factory()->create();
        $product = Product::factory()->create([
            'merchant_id' => $this->merchant->id,
        ]);

        // Create pending order
        $pendingOrder = Order::factory()->create([
            'user_id' => $customer->id,
            'status' => 'pending',
        ]);

        OrderItem::factory()->create([
            'order_id' => $pendingOrder->id,
            'product_id' => $product->id,
            'merchant_id' => $this->merchant->id,
            'quantity' => 2,
            'unit_price' => 100.00,
            'commission_rate' => 10.00,
            'commission_amount' => 20.00,
            'payout_amount' => 180.00,
        ]);

        // Create completed order
        $completedOrder = Order::factory()->create([
            'user_id' => $customer->id,
            'status' => 'completed',
        ]);

        OrderItem::factory()->create([
            'order_id' => $completedOrder->id,
            'product_id' => $product->id,
            'merchant_id' => $this->merchant->id,
            'quantity' => 1,
            'unit_price' => 200.00,
            'commission_rate' => 10.00,
            'commission_amount' => 20.00,
            'payout_amount' => 180.00,
        ]);
    }
}
