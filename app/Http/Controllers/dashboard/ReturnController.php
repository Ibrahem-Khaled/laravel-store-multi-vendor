<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\OrderReturn;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReturnController extends Controller
{
    /**
     * عرض قائمة المرتجعات
     */
    public function index(Request $request)
    {
        $query = OrderReturn::with(['order.user', 'orderItem.product', 'user', 'processedBy']);

        // الفلترة
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', $search)
                  ->orWhereHas('order', function($oq) use ($search) {
                      $oq->where('id', $search);
                  })
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('email', 'like', "%$search%");
                  });
            });
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $returns = $query->latest()->paginate(20);

        // إحصائيات
        $stats = [
            'pending' => OrderReturn::where('status', 'pending')->count(),
            'approved' => OrderReturn::where('status', 'approved')->count(),
            'processing' => OrderReturn::where('status', 'processing')->count(),
            'completed' => OrderReturn::where('status', 'completed')->count(),
            'rejected' => OrderReturn::where('status', 'rejected')->count(),
            'total' => OrderReturn::count(),
        ];

        return view('dashboard.returns.index', compact('returns', 'stats'));
    }

    /**
     * عرض تفاصيل المرتجع
     */
    public function show($id)
    {
        $return = OrderReturn::with(['order.user', 'order.items.product', 'orderItem.product', 'user', 'processedBy', 'replacementOrder'])
            ->findOrFail($id);

        return view('dashboard.returns.show', compact('return'));
    }

    /**
     * الموافقة على المرتجع
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
            'refund_amount' => 'nullable|numeric|min:0',
            'refund_method' => 'nullable|in:original_payment,wallet,bank_transfer',
        ]);

        $return = OrderReturn::findOrFail($id);

        if ($return->status !== 'pending') {
            return back()->withErrors(['error' => 'لا يمكن الموافقة على مرتجع غير معلق']);
        }

        DB::transaction(function() use ($return, $request) {
            $return->approve(auth()->id(), $request->admin_notes);

            // حساب مبلغ الاسترداد إذا لم يكن محدد
            if (!$request->refund_amount && $return->type !== 'replacement') {
                if ($return->order_item_id) {
                    $item = $return->orderItem;
                    $refundAmount = $item->quantity * $item->unit_price;
                } else {
                    $refundAmount = $return->order->grand_total;
                }
                $return->update([
                    'refund_amount' => $refundAmount,
                    'refund_method' => $request->refund_method ?? 'original_payment',
                ]);
            } else {
                $return->update([
                    'refund_amount' => $request->refund_amount,
                    'refund_method' => $request->refund_method ?? 'original_payment',
                ]);
            }
        });

        return redirect()->route('returns.show', $id)
            ->with('success', 'تم الموافقة على المرتجع بنجاح');
    }

    /**
     * رفض المرتجع
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $return = OrderReturn::findOrFail($id);

        if ($return->status !== 'pending') {
            return back()->withErrors(['error' => 'لا يمكن رفض مرتجع غير معلق']);
        }

        $return->reject(auth()->id(), $request->admin_notes);

        return redirect()->route('returns.show', $id)
            ->with('success', 'تم رفض المرتجع');
    }

    /**
     * وضع المرتجع قيد المعالجة
     */
    public function process(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $return = OrderReturn::findOrFail($id);

        if (!in_array($return->status, ['approved', 'pending'])) {
            return back()->withErrors(['error' => 'لا يمكن معالجة هذا المرتجع']);
        }

        $return->markAsProcessing(auth()->id(), $request->admin_notes);

        return redirect()->route('returns.show', $id)
            ->with('success', 'تم وضع المرتجع قيد المعالجة');
    }

    /**
     * إكمال المرتجع
     */
    public function complete(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $return = OrderReturn::findOrFail($id);

        if (!in_array($return->status, ['approved', 'processing'])) {
            return back()->withErrors(['error' => 'لا يمكن إكمال هذا المرتجع']);
        }

        DB::transaction(function() use ($return, $request) {
            $return->complete(auth()->id(), $request->admin_notes);

            // هنا يمكن إضافة منطق الاسترداد المالي أو تحديث المخزون
            // TODO: تنفيذ الاسترداد المالي
            // TODO: تحديث المخزون
        });

        return redirect()->route('returns.show', $id)
            ->with('success', 'تم إكمال المرتجع بنجاح');
    }

    /**
     * إلغاء المرتجع
     */
    public function cancel($id)
    {
        $return = OrderReturn::findOrFail($id);

        if (!in_array($return->status, ['pending', 'approved'])) {
            return back()->withErrors(['error' => 'لا يمكن إلغاء هذا المرتجع']);
        }

        $return->cancel();

        return redirect()->route('returns.show', $id)
            ->with('success', 'تم إلغاء المرتجع');
    }
}
