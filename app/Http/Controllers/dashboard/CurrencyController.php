<?php

namespace App\Http\Controllers\dashboard;

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
     * عرض قائمة العملات
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status'); // active, inactive, all

        $query = Currency::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%$search%")
                    ->orWhere('name_ar', 'like', "%$search%")
                    ->orWhere('name_en', 'like', "%$search%");
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $currencies = $query->orderBy('is_base_currency', 'desc')
            ->orderBy('code')
            ->paginate(20);

        // إحصائيات
        $stats = [
            'total' => Currency::count(),
            'active' => Currency::where('is_active', true)->count(),
            'inactive' => Currency::where('is_active', false)->count(),
            'base_currency' => Currency::where('is_base_currency', true)->first(),
        ];

        return view('dashboard.currencies.index', compact('currencies', 'stats', 'search', 'status'));
    }

    /**
     * عرض تفاصيل عملة محددة
     */
    public function show($id)
    {
        $currency = Currency::with(['exchangeRateHistory.updatedBy:id,name'])
            ->findOrFail($id);

        $history = $currency->exchangeRateHistory()
            ->with('updatedBy:id,name')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('dashboard.currencies.show', compact('currency', 'history'));
    }

    /**
     * عرض نموذج إضافة عملة جديدة
     */
    public function create()
    {
        return view('dashboard.currencies.create');
    }

    /**
     * حفظ عملة جديدة
     */
    public function store(Request $request)
    {
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
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Currency::create([
            'code' => strtoupper($request->code),
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'symbol' => $request->symbol,
            'symbol_ar' => $request->symbol_ar,
            'exchange_rate' => $request->exchange_rate,
            'is_active' => $request->input('is_active', true),
            'is_base_currency' => false,
        ]);

        return redirect()->route('currencies.index')
            ->with('success', 'تم إضافة العملة بنجاح');
    }

    /**
     * عرض نموذج تعديل عملة
     */
    public function edit($id)
    {
        $currency = Currency::findOrFail($id);
        return view('dashboard.currencies.edit', compact('currency'));
    }

    /**
     * تحديث عملة موجودة
     */
    public function update(Request $request, $id)
    {
        $currency = Currency::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name_ar' => 'sometimes|required|string|max:100',
            'name_en' => 'sometimes|required|string|max:100',
            'symbol' => 'sometimes|required|string|max:10',
            'symbol_ar' => 'nullable|string|max:20',
            'exchange_rate' => 'sometimes|required|numeric|min:0.0001',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $currency->update($request->only([
            'name_ar',
            'name_en',
            'symbol',
            'symbol_ar',
            'exchange_rate',
            'is_active',
        ]));

        return redirect()->route('currencies.index')
            ->with('success', 'تم تحديث العملة بنجاح');
    }

    /**
     * حذف عملة
     */
    public function destroy($id)
    {
        $currency = Currency::findOrFail($id);

        if ($currency->is_base_currency) {
            return redirect()->route('currencies.index')
                ->with('error', 'لا يمكن حذف العملة الأساسية');
        }

        $currency->delete();

        return redirect()->route('currencies.index')
            ->with('success', 'تم حذف العملة بنجاح');
    }

    /**
     * تحديث سعر الصرف فقط
     */
    public function updateExchangeRate(Request $request, $id)
    {
        $currency = Currency::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'exchange_rate' => 'required|numeric|min:0.0001',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $previousRate = $currency->exchange_rate;
        $newRate = $request->exchange_rate;
        $changePercentage = $previousRate > 0 
            ? (($newRate - $previousRate) / $previousRate) * 100 
            : 0;

        DB::beginTransaction();

        try {
            $currency->update([
                'exchange_rate' => $newRate
            ]);

            CurrencyExchangeRateHistory::create([
                'currency_id' => $currency->id,
                'exchange_rate' => $newRate,
                'previous_rate' => $previousRate,
                'change_percentage' => round($changePercentage, 2),
                'updated_by' => Auth::id(),
                'notes' => $request->notes,
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'تم تحديث سعر الصرف بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث سعر الصرف: ' . $e->getMessage());
        }
    }

    /**
     * تفعيل/تعطيل عملة
     */
    public function toggleStatus($id)
    {
        $currency = Currency::findOrFail($id);

        $currency->update([
            'is_active' => !$currency->is_active
        ]);

        return redirect()->back()
            ->with('success', 'تم تحديث حالة العملة بنجاح');
    }

    /**
     * تعيين عملة كعملة أساسية
     */
    public function setBaseCurrency($id)
    {
        DB::beginTransaction();

        try {
            // إلغاء العملة الأساسية الحالية
            Currency::where('is_base_currency', true)
                ->update(['is_base_currency' => false]);

            // تعيين العملة الجديدة كأساسية
            $currency = Currency::findOrFail($id);
            $currency->update([
                'is_base_currency' => true,
                'exchange_rate' => 1.0
            ]);

            DB::commit();

            return redirect()->back()
                ->with('success', 'تم تعيين العملة كعملة أساسية بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تعيين العملة الأساسية: ' . $e->getMessage());
        }
    }
}
