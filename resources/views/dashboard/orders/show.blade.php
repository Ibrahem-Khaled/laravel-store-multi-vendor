{{-- resources/views/admin/orders/show.blade.php --}}
@extends('layouts.app')
@section('title', "طلب #{$order->id}")


@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">تفاصيل العناصر</div>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>المنتج</th>
                                <th>التاجر</th>
                                <th>سعر</th>
                                <th>كمية</th>
                                <th>عمولة</th>
                                <th>صافي للتاجر</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $it)
                                <tr>
                                    <td>{{ $it->product->name }}</td>
                                    <td>
                                        <a href="{{ route('merchants.show', $it->merchant) }}">
                                            {{ $it->merchant->name }}
                                        </a>
                                    </td>
                                    <td>{{ number_format($it->unit_price, 2) }}</td>
                                    <td>{{ $it->quantity }}</td>
                                    <td>{{ number_format($it->commission_amount, 2) }}</td>
                                    <td>{{ number_format($it->payout_amount, 2) }}</td>
                                    <td>{{ number_format($it->unit_price * $it->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">ملخّص الطلب</div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>الحالة:</span>
                        @include('components.status-badge', ['status' => $order->status])
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>طريقة الدفع:</span> <span class="badge badge-secondary">{{ $order->payment_method }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-1">
                        <span>المجموع الفرعي:</span> <strong>{{ number_format($order->subtotal, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>الشحن:</span> <strong>{{ number_format($order->shipping_total, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span>الخصم:</span> <strong>{{ number_format($order->discount_total, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>الإجمالي:</span> <strong
                            class="text-primary">{{ number_format($order->grand_total, 2) }}</strong>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">تحديث الحالة</div>
                <div class="card-body">
                    <form method="post" action="{{ route('orders.updateStatus', $order) }}">
                        @csrf @method('patch')
                        <div class="form-group">
                            <label>الحالة</label>
                            <select name="status" class="form-control" required>
                                @foreach (['pending', 'paid', 'shipped', 'completed', 'cancelled'] as $s)
                                    <option value="{{ $s }}" @selected($order->status === $s)>{{ $s }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>السبب (اختياري)</label>
                            <textarea name="reason" rows="2" class="form-control"></textarea>
                        </div>
                        <button class="btn btn-primary btn-block">حفظ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
