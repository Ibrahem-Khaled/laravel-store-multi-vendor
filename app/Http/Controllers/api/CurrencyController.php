<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\CurrencyExchangeRateHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    /**
     * جلب جميع العملات (للمديرين)
     * GET /v2/admin/currencies
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user || $user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية للوصول إلى هذا المورد',
                    'errors' => [
                        'permission' => ['يجب أن تكون مدير للوصول إلى هذه الصفحة']
                    ]
                ], 403);
            }

            $perPage = $request->input('per_page', 50);
            $currencies = Currency::orderBy('is_base_currency', 'desc')
                ->orderBy('code')
                ->paginate($perPage);

            $currenciesData = $currencies->map(function ($currency) {
                return [
                    'id' => $currency->id,
                    'code' => $currency->code,
                    'name_ar' => $currency->name_ar,
                    'name_en' => $currency->name_en,
                    'symbol' => $currency->symbol,
                    'symbol_ar' => $currency->symbol_ar,
                    'is_active' => $currency->is_active,
                    'is_base_currency' => $currency->is_base_currency,
                    'exchange_rate' => (float) $currency->exchange_rate,
                    'created_at' => $currency->created_at->format('Y-m-d\TH:i:s\Z'),
                    'updated_at' => $currency->updated_at->format('Y-m-d\TH:i:s\Z'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'تم جلب العملات بنجاح',
                'data' => [
                    'currencies' => $currenciesData,
                    'pagination' => [
                        'total' => $currencies->total(),
                        'per_page' => $currencies->perPage(),
                        'current_page' => $currencies->currentPage(),
                        'last_page' => $currencies->lastPage(),
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب العملات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب عملة محددة
     * GET /v2/admin/currencies/{id}
     */
    public function show($id)
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user || $user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية للوصول إلى هذا المورد',
                    'errors' => [
                        'permission' => ['يجب أن تكون مدير للوصول إلى هذه الصفحة']
                    ]
                ], 403);
            }

            $currency = Currency::with(['exchangeRateHistory.updatedBy:id,name'])
                ->find($id);

            if (!$currency) {
                return response()->json([
                    'success' => false,
                    'message' => 'العملة غير موجودة',
                    'errors' => [
                        'currency' => ['لا توجد عملة بهذا المعرف']
                    ]
                ], 404);
            }

            $history = $currency->exchangeRateHistory()
                ->with('updatedBy:id,name')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($history) {
                    return [
                        'id' => $history->id,
                        'exchange_rate' => (float) $history->exchange_rate,
                        'updated_by' => $history->updatedBy ? [
                            'id' => $history->updatedBy->id,
                            'name' => $history->updatedBy->name,
                        ] : null,
                        'updated_at' => $history->created_at->format('Y-m-d\TH:i:s\Z'),
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'تم جلب العملة بنجاح',
                'data' => [
                    'currency' => [
                        'id' => $currency->id,
                        'code' => $currency->code,
                        'name_ar' => $currency->name_ar,
                        'name_en' => $currency->name_en,
                        'symbol' => $currency->symbol,
                        'symbol_ar' => $currency->symbol_ar,
                        'is_active' => $currency->is_active,
                        'is_base_currency' => $currency->is_base_currency,
                        'exchange_rate' => (float) $currency->exchange_rate,
                        'created_at' => $currency->created_at->format('Y-m-d\TH:i:s\Z'),
                        'updated_at' => $currency->updated_at->format('Y-m-d\TH:i:s\Z'),
                        'history' => $history,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب العملة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إضافة عملة جديدة
     * POST /v2/admin/currencies
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user || $user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية للوصول إلى هذا المورد',
                    'errors' => [
                        'permission' => ['يجب أن تكون مدير للوصول إلى هذه الصفحة']
                    ]
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'code' => 'required|string|max:10|unique:currencies,code|regex:/^[A-Z_]+$/',
                'name_ar' => 'required|string|max:100',
                'name_en' => 'required|string|max:100',
                'symbol' => 'required|string|max:10',
                'symbol_ar' => 'nullable|string|max:20',
                'exchange_rate' => 'required|numeric|min:0.0001',
                'is_active' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'خطأ في التحقق من البيانات',
                    'errors' => $validator->errors()
                ], 422);
            }

            $currency = Currency::create([
                'code' => strtoupper($request->code),
                'name_ar' => $request->name_ar,
                'name_en' => $request->name_en,
                'symbol' => $request->symbol,
                'symbol_ar' => $request->symbol_ar,
                'exchange_rate' => $request->exchange_rate,
                'is_active' => $request->input('is_active', true),
                'is_base_currency' => false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة العملة بنجاح',
                'data' => [
                    'currency' => [
                        'id' => $currency->id,
                        'code' => $currency->code,
                        'name_ar' => $currency->name_ar,
                        'name_en' => $currency->name_en,
                        'symbol' => $currency->symbol,
                        'symbol_ar' => $currency->symbol_ar,
                        'is_active' => $currency->is_active,
                        'is_base_currency' => $currency->is_base_currency,
                        'exchange_rate' => (float) $currency->exchange_rate,
                        'created_at' => $currency->created_at->format('Y-m-d\TH:i:s\Z'),
                        'updated_at' => $currency->updated_at->format('Y-m-d\TH:i:s\Z'),
                    ]
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إضافة العملة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث عملة موجودة
     * PUT /v2/admin/currencies/{id}
     */
    public function update(Request $request, $id)
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user || $user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية للوصول إلى هذا المورد',
                    'errors' => [
                        'permission' => ['يجب أن تكون مدير للوصول إلى هذه الصفحة']
                    ]
                ], 403);
            }

            $currency = Currency::find($id);

            if (!$currency) {
                return response()->json([
                    'success' => false,
                    'message' => 'العملة غير موجودة',
                    'errors' => [
                        'currency' => ['لا توجد عملة بهذا المعرف']
                    ]
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name_ar' => 'sometimes|required|string|max:100',
                'name_en' => 'sometimes|required|string|max:100',
                'symbol' => 'sometimes|required|string|max:10',
                'symbol_ar' => 'nullable|string|max:20',
                'exchange_rate' => 'sometimes|required|numeric|min:0.0001',
                'is_active' => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'خطأ في التحقق من البيانات',
                    'errors' => $validator->errors()
                ], 422);
            }

            $currency->update($request->only([
                'name_ar',
                'name_en',
                'symbol',
                'symbol_ar',
                'exchange_rate',
                'is_active',
            ]));

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث العملة بنجاح',
                'data' => [
                    'currency' => [
                        'id' => $currency->id,
                        'code' => $currency->code,
                        'name_ar' => $currency->name_ar,
                        'name_en' => $currency->name_en,
                        'symbol' => $currency->symbol,
                        'symbol_ar' => $currency->symbol_ar,
                        'is_active' => $currency->is_active,
                        'is_base_currency' => $currency->is_base_currency,
                        'exchange_rate' => (float) $currency->exchange_rate,
                        'created_at' => $currency->created_at->format('Y-m-d\TH:i:s\Z'),
                        'updated_at' => $currency->updated_at->format('Y-m-d\TH:i:s\Z'),
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث العملة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث سعر الصرف فقط
     * PATCH /v2/admin/currencies/{id}/exchange-rate
     */
    public function updateExchangeRate(Request $request, $id)
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user || $user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية للوصول إلى هذا المورد',
                    'errors' => [
                        'permission' => ['يجب أن تكون مدير للوصول إلى هذه الصفحة']
                    ]
                ], 403);
            }

            $currency = Currency::find($id);

            if (!$currency) {
                return response()->json([
                    'success' => false,
                    'message' => 'العملة غير موجودة',
                    'errors' => [
                        'currency' => ['لا توجد عملة بهذا المعرف']
                    ]
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'exchange_rate' => 'required|numeric|min:0.0001',
                'notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'خطأ في التحقق من البيانات',
                    'errors' => $validator->errors()
                ], 422);
            }

            $previousRate = $currency->exchange_rate;
            $newRate = $request->exchange_rate;
            $changePercentage = $previousRate > 0 
                ? (($newRate - $previousRate) / $previousRate) * 100 
                : 0;

            DB::beginTransaction();

            $currency->update([
                'exchange_rate' => $newRate
            ]);

            $history = CurrencyExchangeRateHistory::create([
                'currency_id' => $currency->id,
                'exchange_rate' => $newRate,
                'previous_rate' => $previousRate,
                'change_percentage' => round($changePercentage, 2),
                'updated_by' => $user->id,
                'notes' => $request->notes,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث سعر الصرف بنجاح',
                'data' => [
                    'currency' => [
                        'id' => $currency->id,
                        'code' => $currency->code,
                        'name_ar' => $currency->name_ar,
                        'exchange_rate' => (float) $currency->exchange_rate,
                        'updated_at' => $currency->updated_at->format('Y-m-d\TH:i:s\Z'),
                    ],
                    'history' => [
                        'id' => $history->id,
                        'exchange_rate' => (float) $history->exchange_rate,
                        'previous_rate' => (float) $history->previous_rate,
                        'change_percentage' => (float) $history->change_percentage,
                        'updated_by' => [
                            'id' => $user->id,
                            'name' => $user->name,
                        ],
                        'notes' => $history->notes,
                        'updated_at' => $history->created_at->format('Y-m-d\TH:i:s\Z'),
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث سعر الصرف',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف عملة
     * DELETE /v2/admin/currencies/{id}
     */
    public function destroy($id)
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user || $user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية للوصول إلى هذا المورد',
                    'errors' => [
                        'permission' => ['يجب أن تكون مدير للوصول إلى هذه الصفحة']
                    ]
                ], 403);
            }

            $currency = Currency::find($id);

            if (!$currency) {
                return response()->json([
                    'success' => false,
                    'message' => 'العملة غير موجودة',
                    'errors' => [
                        'currency' => ['لا توجد عملة بهذا المعرف']
                    ]
                ], 404);
            }

            if ($currency->is_base_currency) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف هذه العملة',
                    'errors' => [
                        'currency' => ['لا يمكن حذف العملة الأساسية']
                    ]
                ], 400);
            }

            $currency->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف العملة بنجاح'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف العملة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تفعيل/تعطيل عملة
     * PATCH /v2/admin/currencies/{id}/toggle-status
     */
    public function toggleStatus($id)
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user || $user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية للوصول إلى هذا المورد',
                    'errors' => [
                        'permission' => ['يجب أن تكون مدير للوصول إلى هذه الصفحة']
                    ]
                ], 403);
            }

            $currency = Currency::find($id);

            if (!$currency) {
                return response()->json([
                    'success' => false,
                    'message' => 'العملة غير موجودة',
                    'errors' => [
                        'currency' => ['لا توجد عملة بهذا المعرف']
                    ]
                ], 404);
            }

            $currency->update([
                'is_active' => !$currency->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة العملة بنجاح',
                'data' => [
                    'currency' => [
                        'id' => $currency->id,
                        'code' => $currency->code,
                        'is_active' => $currency->is_active,
                        'updated_at' => $currency->updated_at->format('Y-m-d\TH:i:s\Z'),
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث حالة العملة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب سجل تغييرات أسعار الصرف
     * GET /v2/admin/currencies/{id}/exchange-rate-history
     */
    public function exchangeRateHistory(Request $request, $id)
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user || $user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية للوصول إلى هذا المورد',
                    'errors' => [
                        'permission' => ['يجب أن تكون مدير للوصول إلى هذه الصفحة']
                    ]
                ], 403);
            }

            $currency = Currency::find($id);

            if (!$currency) {
                return response()->json([
                    'success' => false,
                    'message' => 'العملة غير موجودة',
                    'errors' => [
                        'currency' => ['لا توجد عملة بهذا المعرف']
                    ]
                ], 404);
            }

            $perPage = $request->input('per_page', 20);
            $page = $request->input('page', 1);

            $history = CurrencyExchangeRateHistory::where('currency_id', $id)
                ->with('updatedBy:id,name,email')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            $historyData = $history->map(function ($item) {
                return [
                    'id' => $item->id,
                    'currency_id' => $item->currency_id,
                    'exchange_rate' => (float) $item->exchange_rate,
                    'previous_rate' => $item->previous_rate ? (float) $item->previous_rate : null,
                    'change_percentage' => $item->change_percentage ? (float) $item->change_percentage : null,
                    'updated_by' => $item->updatedBy ? [
                        'id' => $item->updatedBy->id,
                        'name' => $item->updatedBy->name,
                        'email' => $item->updatedBy->email,
                    ] : null,
                    'notes' => $item->notes,
                    'created_at' => $item->created_at->format('Y-m-d\TH:i:s\Z'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'تم جلب السجل بنجاح',
                'data' => [
                    'history' => $historyData,
                    'pagination' => [
                        'total' => $history->total(),
                        'per_page' => $history->perPage(),
                        'current_page' => $history->currentPage(),
                        'last_page' => $history->lastPage(),
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب السجل',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب جميع العملات النشطة (للمستخدمين العاديين)
     * GET /v2/currencies
     */
    public function getCurrencies(Request $request)
    {
        try {
            $currencies = Currency::active()
                ->orderBy('is_base_currency', 'desc')
                ->orderBy('code')
                ->get();

            $currenciesData = $currencies->map(function ($currency) {
                return [
                    'id' => $currency->id,
                    'code' => $currency->code,
                    'name_ar' => $currency->name_ar,
                    'name_en' => $currency->name_en,
                    'symbol' => $currency->symbol,
                    'symbol_ar' => $currency->symbol_ar,
                    'exchange_rate' => (float) $currency->exchange_rate,
                    'is_base_currency' => $currency->is_base_currency,
                ];
            });

            $baseCurrency = $currencies->where('is_base_currency', true)->first();

            return response()->json([
                'success' => true,
                'message' => 'تم جلب العملات بنجاح',
                'data' => [
                    'base_currency' => $baseCurrency ? [
                        'code' => $baseCurrency->code,
                        'name_ar' => $baseCurrency->name_ar,
                        'name_en' => $baseCurrency->name_en,
                        'symbol' => $baseCurrency->symbol,
                    ] : null,
                    'currencies' => $currenciesData,
                    'total' => $currencies->count(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب العملات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب عملة محددة (للمستخدمين العاديين)
     * GET /v2/currencies/{code}
     */
    public function getCurrencyByCode($code)
    {
        try {
            $currency = Currency::active()
                ->where('code', strtoupper($code))
                ->first();

            if (!$currency) {
                return response()->json([
                    'success' => false,
                    'message' => 'العملة غير موجودة أو غير نشطة',
                    'errors' => [
                        'currency' => ['لا توجد عملة بهذا الرمز']
                    ]
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'تم جلب العملة بنجاح',
                'data' => [
                    'currency' => [
                        'id' => $currency->id,
                        'code' => $currency->code,
                        'name_ar' => $currency->name_ar,
                        'name_en' => $currency->name_en,
                        'symbol' => $currency->symbol,
                        'symbol_ar' => $currency->symbol_ar,
                        'exchange_rate' => (float) $currency->exchange_rate,
                        'is_base_currency' => $currency->is_base_currency,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب العملة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحويل العملات
     * POST /v2/currencies/convert
     */
    public function convertCurrency(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric|min:0.01',
                'from' => 'required|string|exists:currencies,code',
                'to' => 'required|string|exists:currencies,code',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'خطأ في التحقق من البيانات',
                    'errors' => $validator->errors()
                ], 422);
            }

            $fromCurrency = Currency::active()->where('code', strtoupper($request->from))->first();
            $toCurrency = Currency::active()->where('code', strtoupper($request->to))->first();

            if (!$fromCurrency || !$toCurrency) {
                return response()->json([
                    'success' => false,
                    'message' => 'إحدى العملات غير موجودة أو غير نشطة',
                ], 404);
            }

            $amount = (float) $request->amount;
            
            // التحويل: من عملة إلى عملة أخرى
            // أولاً نحول من العملة المصدر إلى العملة الأساسية (USD)
            $amountInBase = $amount / $fromCurrency->exchange_rate;
            
            // ثم نحول من العملة الأساسية إلى العملة الهدف
            $convertedAmount = $amountInBase * $toCurrency->exchange_rate;

            return response()->json([
                'success' => true,
                'message' => 'تم تحويل العملة بنجاح',
                'data' => [
                    'from' => [
                        'code' => $fromCurrency->code,
                        'name_ar' => $fromCurrency->name_ar,
                        'name_en' => $fromCurrency->name_en,
                        'symbol' => $fromCurrency->symbol,
                        'amount' => $amount,
                        'exchange_rate' => (float) $fromCurrency->exchange_rate,
                    ],
                    'to' => [
                        'code' => $toCurrency->code,
                        'name_ar' => $toCurrency->name_ar,
                        'name_en' => $toCurrency->name_en,
                        'symbol' => $toCurrency->symbol,
                        'amount' => round($convertedAmount, 4),
                        'exchange_rate' => (float) $toCurrency->exchange_rate,
                    ],
                    'conversion_rate' => round($toCurrency->exchange_rate / $fromCurrency->exchange_rate, 6),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحويل العملة',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب أسعار الصرف الحالية (للمستخدمين العاديين)
     * GET /v2/settings/exchange-rates
     */
    public function getExchangeRates()
    {
        try {
            $currencies = Currency::active()
                ->orderBy('is_base_currency', 'desc')
                ->orderBy('code')
                ->get();

            $baseCurrency = $currencies->where('is_base_currency', true)->first();
            $rates = [];

            foreach ($currencies as $currency) {
                $rates[$currency->code] = (float) $currency->exchange_rate;
            }

            $lastUpdated = Currency::max('updated_at');

            return response()->json([
                'success' => true,
                'message' => 'تم جلب أسعار الصرف بنجاح',
                'data' => [
                    'base_currency' => $baseCurrency ? $baseCurrency->code : 'USD',
                    'rates' => $rates,
                    'last_updated' => $lastUpdated ? $lastUpdated->format('Y-m-d\TH:i:s\Z') : null,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب أسعار الصرف',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث متعدد لأسعار الصرف
     * POST /v2/admin/currencies/bulk-update-rates
     */
    public function bulkUpdateRates(Request $request)
    {
        try {
            $user = auth()->guard('api')->user();

            if (!$user || $user->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'ليس لديك صلاحية للوصول إلى هذا المورد',
                    'errors' => [
                        'permission' => ['يجب أن تكون مدير للوصول إلى هذه الصفحة']
                    ]
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'rates' => 'required|array|min:1',
                'rates.*.currency_id' => 'required|exists:currencies,id',
                'rates.*.exchange_rate' => 'required|numeric|min:0.0001',
                'rates.*.notes' => 'nullable|string|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'خطأ في التحقق من البيانات',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $updated = [];
            foreach ($request->rates as $rateData) {
                $currency = Currency::find($rateData['currency_id']);
                if (!$currency) continue;

                $previousRate = $currency->exchange_rate;
                $newRate = $rateData['exchange_rate'];
                $changePercentage = $previousRate > 0 
                    ? (($newRate - $previousRate) / $previousRate) * 100 
                    : 0;

                $currency->update([
                    'exchange_rate' => $newRate
                ]);

                CurrencyExchangeRateHistory::create([
                    'currency_id' => $currency->id,
                    'exchange_rate' => $newRate,
                    'previous_rate' => $previousRate,
                    'change_percentage' => round($changePercentage, 2),
                    'updated_by' => $user->id,
                    'notes' => $rateData['notes'] ?? null,
                ]);

                $updated[] = [
                    'id' => $currency->id,
                    'code' => $currency->code,
                    'exchange_rate' => (float) $currency->exchange_rate,
                ];
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث أسعار الصرف بنجاح',
                'data' => [
                    'updated' => count($updated),
                    'currencies' => $updated,
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث أسعار الصرف',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
