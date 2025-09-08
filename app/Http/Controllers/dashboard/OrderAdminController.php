<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\dashboard\OrderStatusRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderAdminController extends Controller
{
    public function index(Request $req)
    {
        $filters = $req->only(['status', 'method', 'from', 'to', 'search', 'sort', 'dir']);
        $sort = in_array($filters['sort'] ?? '', ['id', 'grand_total', 'created_at']) ? $filters['sort'] : 'created_at';
        $dir  = in_array($filters['dir'] ?? '', ['asc', 'desc']) ? $filters['dir'] : 'desc';

        $orders = Order::query()
            ->with(['user'])
            ->withAdminSummaries()
            ->filter($filters)
            ->orderBy($sort, $dir)
            ->paginate(20)
            ->withQueryString();

        return view('dashboard.orders.index', compact('orders', 'filters', 'sort', 'dir'));
    }

    public function show(Order $order)
    {
        $order->load([
            'user',
            'items.merchant',
            'items.product.brand',
        ]);

        return view('dashboard.orders.show', compact('order'));
    }

    public function updateStatus(OrderStatusRequest $request, Order $order)
    {
        $old = $order->status;
        $order->update(['status' => $request->validated()['status']]);

        // حدث إشعار/لوج تدقيق (Queueable Notification/Event)
        // event(new \App\Events\Admin\OrderStatusChanged($order, $old, $order->status));

        return back()->with('success', 'تم تحديث حالة الطلب.');
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'ids'    => ['required', 'array'],
            'ids.*'  => ['integer', 'exists:orders,id'],
            'action' => ['required', 'in:mark_paid,mark_shipped,mark_cancelled'],
        ]);

        DB::transaction(function () use ($validated) {
            $orders = Order::whereIn('id', $validated['ids'])->lockForUpdate()->get();

            foreach ($orders as $order) {
                $old = $order->status;
                $new = match ($validated['action']) {
                    'mark_paid'      => 'paid',
                    'mark_shipped'   => 'shipped',
                    'mark_cancelled' => 'cancelled',
                };
                $order->update(['status' => $new]);
                // event(new \App\Events\Admin\OrderStatusChanged($order, $old, $new));
            }
        });

        return back()->with('success', 'تم تنفيذ العملية الجماعية.');
    }
}
