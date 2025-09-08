<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MerchantAdminController extends Controller
{
    public function index(Request $req)
    {
        // 1) التقاط فلاتر
        $search     = (string) $req->query('search', '');
        $year       = (int) ($req->query('year') ?: now()->year);
        $month      = (int) ($req->query('month') ?: now()->month);
        $statusKind = $req->query('balance', 'any'); // any | has_open | no_open | only_closed

        // 2) تحويل (شهر/سنة) إلى مدى زمني
        $from = Carbon::create($year, $month, 1)->startOfMonth();
        $to   = (clone $from)->endOfMonth();

        // 3) كويري التجّار مع تجميعات مقيّدة بالمدى الزمني
        $merchants = User::query()
            ->where('role', 'trader')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($qq) use ($search) {
                    $qq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                });
            })

            // payable_open: ما زال مستحق للتاجر (منصّة → تاجر)، pending وفي المدى الزمني
            ->withSum(['ledgerEntries as payable_open' => function ($q) use ($from, $to) {
                $q->where('direction', 'payable_to_merchant')
                    ->where('status', 'pending')
                    ->whereBetween('created_at', [$from, $to]);
            }], 'amount')

            // receivable_open: عمولة مستحقة على التاجر (تاجر → منصّة)، pending وفي المدى الزمني
            ->withSum(['ledgerEntries as receivable_open' => function ($q) use ($from, $to) {
                $q->where('direction', 'receivable_from_merchant')
                    ->where('status', 'pending')
                    ->whereBetween('created_at', [$from, $to]);
            }], 'amount')

            // payable_closed (اختياري): ما صُرف فعلاً للتاجر خلال المدى
            ->withSum(['ledgerEntries as payable_closed' => function ($q) use ($from, $to) {
                $q->where('direction', 'payable_to_merchant')
                    ->where('status', 'paid')
                    ->whereBetween('paid_at', [$from, $to]);
            }], 'amount')

            // receivable_closed (اختياري): ما تمّ تحصيله من التاجر خلال المدى
            ->withSum(['ledgerEntries as receivable_closed' => function ($q) use ($from, $to) {
                $q->where('direction', 'receivable_from_merchant')
                    ->where('status', 'paid')
                    ->whereBetween('paid_at', [$from, $to]);
            }], 'amount')

            // عدد المنتجات
            ->withCount(['products'])

            // فلاتر "له رصيد مفتوح / لا يوجد"
            ->when($statusKind === 'has_open', function ($q) {
                $q->havingRaw('(COALESCE(payable_open,0) + COALESCE(receivable_open,0)) > 0');
            })
            ->when($statusKind === 'no_open', function ($q) {
                $q->havingRaw('(COALESCE(payable_open,0) + COALESCE(receivable_open,0)) = 0');
            })
            ->when($statusKind === 'only_closed', function ($q) {
                // ليس لديه أي قيود pending، لكن ربما لديه قيود paid ضمن المدى
                $q->havingRaw('(COALESCE(payable_open,0) + COALESCE(receivable_open,0)) = 0')
                    ->havingRaw('(COALESCE(payable_closed,0) + COALESCE(receivable_closed,0)) > 0');
            })

            ->orderBy('id', 'desc')
            ->paginate(20)
            ->withQueryString();

        // 4) الملخص العام للفترة:
        // إجمالي أرباح التجار = مجموع payout_amount داخل order_items ضمن المدى (حسب created_at للطلب)
        $totalMerchantsEarnings = OrderItem::query()
            ->whereHas('order', fn($o) => $o->whereBetween('created_at', [$from, $to]))
            ->sum('payout_amount');

        // إجمالي عمولة المنصّة للفترة
        $totalPlatformCommission = OrderItem::query()
            ->whereHas('order', fn($o) => $o->whereBetween('created_at', [$from, $to]))
            ->sum('commission_amount');

        // إجمالي الأرصدة المفتوحة (payable و receivable) للفترة — من الـ ledger
        $openPayableSum = DB::table('merchant_ledger_entries')
            ->where('direction', 'payable_to_merchant')
            ->where('status', 'pending')
            ->whereBetween('created_at', [$from, $to])
            ->sum('amount');

        $openReceivableSum = DB::table('merchant_ledger_entries')
            ->where('direction', 'receivable_from_merchant')
            ->where('status', 'pending')
            ->whereBetween('created_at', [$from, $to])
            ->sum('amount');

        // نمرر كل شيء للواجهة
        return view('dashboard.merchants.index', [
            'merchants'               => $merchants,
            'search'                  => $search,
            'year'                    => $year,
            'month'                   => $month,
            'balance'                 => $statusKind,
            'from'                    => $from,
            'to'                      => $to,
            'totalMerchantsEarnings'  => $totalMerchantsEarnings,
            'totalPlatformCommission' => $totalPlatformCommission,
            'openPayableSum'          => $openPayableSum,
            'openReceivableSum'       => $openReceivableSum,
        ]);
    }

    public function show(User $merchant)
    {
        abort_unless($merchant->role === 'trader', 404);
        $merchant->loadCount(['products']);

        $ledger = $merchant->ledgerEntries()
            ->with(['order', 'item.product'])
            ->latest()->paginate(25);

        $payments = $merchant->merchantPayments()->latest()->paginate(10);

        return view('dashboard.merchants.show', compact('merchant', 'ledger', 'payments'));
    }

    public function settle(Request $req, User $merchant)
    {
        $data = $req->validate([
            'direction' => ['required', 'in:payable_to_merchant,receivable_from_merchant'],
            'amount'    => ['required', 'numeric', 'min:0.01'],
            'method'    => ['nullable', 'string', 'max:50'],
            'reference' => ['nullable', 'string', 'max:100'],
        ]);

        app(\App\Services\Accounting\MerchantSettlementService::class)
            ->settleForMerchant($merchant, $data['direction'], $data['amount'], [
                'method'    => $data['method'] ?? 'bank_transfer',
                'reference' => $data['reference'] ?? null,
            ]);

        return back()->with('success', 'تم تسجيل التسوية وإقفال القيود المناسبة.');
    }
}
