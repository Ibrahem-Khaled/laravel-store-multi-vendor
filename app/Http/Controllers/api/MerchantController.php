<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MerchantOrderResource;
use App\Http\Resources\MerchantWithdrawalResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MerchantPayment;
use App\Models\MerchantProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MerchantController extends Controller
{
    /**
     * Get merchant dashboard statistics
     */
    public function dashboard()
    {
        $merchantId = Auth::guard('api')->id();

        // Get current month stats
        $currentMonth = Carbon::now()->startOfMonth();

        $stats = [
            // Orders Statistics
            'orders' => [
                'pending' => OrderItem::where('merchant_id', $merchantId)
                    ->whereHas('order', function($q) {
                        $q->whereIn('status', ['pending', 'processing', 'confirmed']);
                    })
                    ->count(),

                'completed' => OrderItem::where('merchant_id', $merchantId)
                    ->whereHas('order', function($q) {
                        $q->where('status', 'completed');
                    })
                    ->count(),

                'cancelled' => OrderItem::where('merchant_id', $merchantId)
                    ->whereHas('order', function($q) {
                        $q->where('status', 'cancelled');
                    })
                    ->count(),

                'total' => OrderItem::where('merchant_id', $merchantId)->count(),
            ],

            // Earnings Statistics
            'earnings' => [
                'total_earnings' => OrderItem::where('merchant_id', $merchantId)
                    ->whereHas('order', function($q) {
                        $q->where('status', 'completed');
                    })
                    ->sum('payout_amount'),

                'monthly_earnings' => OrderItem::where('merchant_id', $merchantId)
                    ->whereHas('order', function($q) use ($currentMonth) {
                        $q->where('status', 'completed')
                          ->where('created_at', '>=', $currentMonth);
                    })
                    ->sum('payout_amount'),

                'pending_earnings' => OrderItem::where('merchant_id', $merchantId)
                    ->whereHas('order', function($q) {
                        $q->whereIn('status', ['pending', 'processing', 'confirmed']);
                    })
                    ->sum('payout_amount'),

                'total_commission' => OrderItem::where('merchant_id', $merchantId)
                    ->whereHas('order', function($q) {
                        $q->where('status', 'completed');
                    })
                    ->sum('commission_amount'),
            ],

            // Withdrawal Statistics
            'withdrawals' => [
                'total_withdrawn' => MerchantPayment::where('merchant_id', $merchantId)
                    ->where('type', 'withdrawal')
                    ->where('paid_at', '!=', null)
                    ->sum('amount'),

                'pending_withdrawals' => MerchantPayment::where('merchant_id', $merchantId)
                    ->where('type', 'withdrawal')
                    ->where('paid_at', null)
                    ->sum('amount'),

                'available_balance' => $this->calculateAvailableBalance($merchantId),
            ],

            // Recent Activity
            'recent_orders' => OrderItem::where('merchant_id', $merchantId)
                ->with(['order.user', 'product'])
                ->latest()
                ->limit(5)
                ->get(),
        ];

        return response()->json([
            'status' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get pending orders for merchant
     */
    public function pendingOrders(Request $request)
    {
        $merchantId = Auth::guard('api')->id();

        $query = OrderItem::where('merchant_id', $merchantId)
            ->whereHas('order', function($q) {
                $q->whereIn('status', ['pending', 'processing', 'confirmed']);
            })
            ->with(['order.user', 'product', 'order.userAddress']);

        // Apply filters
        if ($request->has('status')) {
            $query->whereHas('order', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->has('date_from')) {
            $query->whereHas('order', function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            });
        }

        if ($request->has('date_to')) {
            $query->whereHas('order', function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            });
        }

        $orders = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => true,
            'data' => MerchantOrderResource::collection($orders)
        ]);
    }

    /**
     * Get order history for merchant
     */
    public function orderHistory(Request $request)
    {
        $merchantId = Auth::guard('api')->id();

        $query = OrderItem::where('merchant_id', $merchantId)
            ->with(['order.user', 'product', 'order.userAddress']);

        // Apply filters
        if ($request->has('status')) {
            $query->whereHas('order', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->has('date_from')) {
            $query->whereHas('order', function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            });
        }

        if ($request->has('date_to')) {
            $query->whereHas('order', function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            });
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => true,
            'data' => MerchantOrderResource::collection($orders)
        ]);
    }

    /**
     * Get earnings report for merchant
     */
    public function earnings(Request $request)
    {
        $merchantId = Auth::guard('api')->id();

        $query = OrderItem::where('merchant_id', $merchantId)
            ->whereHas('order', function($q) {
                $q->where('status', 'completed');
            })
            ->with(['order', 'product']);

        // Apply date filters
        if ($request->has('date_from')) {
            $query->whereHas('order', function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->date_from);
            });
        }

        if ($request->has('date_to')) {
            $query->whereHas('order', function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->date_to);
            });
        }

        $earnings = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        // Calculate summary
        $summary = [
            'total_earnings' => $query->sum('payout_amount'),
            'total_commission' => $query->sum('commission_amount'),
            'total_orders' => $query->count(),
            'average_order_value' => $query->avg('payout_amount'),
        ];

        return response()->json([
            'status' => true,
            'data' => [
                'summary' => $summary,
                'earnings' => $earnings
            ]
        ]);
    }

    /**
     * Get withdrawal history for merchant
     */
    public function withdrawals(Request $request)
    {
        $merchantId = Auth::guard('api')->id();

        $query = MerchantPayment::where('merchant_id', $merchantId)
            ->where('type', 'withdrawal');

        // Apply filters
        if ($request->has('status')) {
            if ($request->status === 'paid') {
                $query->where('paid_at', '!=', null);
            } elseif ($request->status === 'pending') {
                $query->where('paid_at', null);
            }
        }

        $withdrawals = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => true,
            'data' => MerchantWithdrawalResource::collection($withdrawals)
        ]);
    }

    /**
     * Request withdrawal
     */
    public function requestWithdrawal(Request $request)
    {
        $merchantId = Auth::guard('api')->id();

        $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string|in:bank_transfer,paypal',
            'reference' => 'nullable|string',
        ]);

        $availableBalance = $this->calculateAvailableBalance($merchantId);

        if ($request->amount > $availableBalance) {
            return response()->json([
                'status' => false,
                'message' => 'Insufficient balance. Available balance: ' . $availableBalance
            ], 400);
        }

        $withdrawal = MerchantPayment::create([
            'merchant_id' => $merchantId,
            'type' => 'withdrawal',
            'amount' => $request->amount,
            'method' => $request->method,
            'reference' => $request->reference,
            'meta' => [
                'requested_at' => now(),
                'status' => 'pending'
            ]
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Withdrawal request submitted successfully',
            'data' => $withdrawal
        ]);
    }

    /**
     * Get merchant profile
     */
    public function profile()
    {
        $merchantId = Auth::guard('api')->id();

        $profile = MerchantProfile::where('user_id', $merchantId)->first();

        if (!$profile) {
            return response()->json([
                'status' => false,
                'message' => 'Merchant profile not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $profile
        ]);
    }

    /**
     * Update merchant profile
     */
    public function updateProfile(Request $request)
    {
        $merchantId = Auth::guard('api')->id();

        $request->validate([
            'default_commission_rate' => 'nullable|numeric|min:0|max:100',
            'payout_bank_name' => 'nullable|string|max:255',
            'payout_account_name' => 'nullable|string|max:255',
            'payout_account_iban' => 'nullable|string|max:255',
        ]);

        $profile = MerchantProfile::where('user_id', $merchantId)->first();

        if (!$profile) {
            $profile = MerchantProfile::create([
                'user_id' => $merchantId,
                ...$request->only([
                    'default_commission_rate',
                    'payout_bank_name',
                    'payout_account_name',
                    'payout_account_iban'
                ])
            ]);
        } else {
            $profile->update($request->only([
                'default_commission_rate',
                'payout_bank_name',
                'payout_account_name',
                'payout_account_iban'
            ]));
        }

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => $profile
        ]);
    }

    /**
     * Get monthly statistics
     */
    public function monthlyStats(Request $request)
    {
        $merchantId = Auth::guard('api')->id();

        $months = $request->get('months', 12);
        $stats = [];

        for ($i = 0; $i < $months; $i++) {
            $month = Carbon::now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();

            $monthStats = OrderItem::where('merchant_id', $merchantId)
                ->whereHas('order', function($q) use ($startOfMonth, $endOfMonth) {
                    $q->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
                })
                ->selectRaw('
                    COUNT(*) as orders_count,
                    SUM(payout_amount) as total_earnings,
                    SUM(commission_amount) as total_commission,
                    AVG(payout_amount) as average_order_value
                ')
                ->first();

            $stats[] = [
                'month' => $month->format('Y-m'),
                'month_name' => $month->format('F Y'),
                'orders_count' => $monthStats->orders_count ?? 0,
                'total_earnings' => $monthStats->total_earnings ?? 0,
                'total_commission' => $monthStats->total_commission ?? 0,
                'average_order_value' => $monthStats->average_order_value ?? 0,
            ];
        }

        return response()->json([
            'status' => true,
            'data' => array_reverse($stats)
        ]);
    }

    /**
     * Calculate available balance for withdrawal
     */
    private function calculateAvailableBalance($merchantId)
    {
        $totalEarnings = OrderItem::where('merchant_id', $merchantId)
            ->whereHas('order', function($q) {
                $q->where('status', 'completed');
            })
            ->sum('payout_amount');

        $totalWithdrawn = MerchantPayment::where('merchant_id', $merchantId)
            ->where('type', 'withdrawal')
            ->where('paid_at', '!=', null)
            ->sum('amount');

        return $totalEarnings - $totalWithdrawn;
    }
}
