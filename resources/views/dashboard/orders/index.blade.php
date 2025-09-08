{{-- resources/views/admin/orders/index.blade.php --}}
@extends('layouts.app') {{-- غيّر حسب Layout عندك --}}
@section('title', 'إدارة الطلبات')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h1 class="h3 mb-0">الطلبات</h1>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form class="form-row" method="get">
            <div class="form-group col-md-3 mb-2">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="بحث بالرقم/الاسم/الإيميل">
            </div>
            <div class="form-group col-md-2 mb-2">
                <select name="status" class="form-control">
                    <option value="">كل الحالات</option>
                    @foreach (['pending', 'paid', 'shipped', 'completed', 'cancelled'] as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ $s }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-2 mb-2">
                <select name="method" class="form-control">
                    <option value="">كل طرق الدفع</option>
                    <option value="cash_on_delivery" @selected(request('method') === 'cash_on_delivery')>COD</option>
                    <option value="card" @selected(request('method') === 'card')>بطاقة</option>
                </select>
            </div>
            <div class="form-group col-md-2 mb-2">
                <input type="date" name="from" value="{{ request('from') }}" class="form-control">
            </div>
            <div class="form-group col-md-2 mb-2">
                <input type="date" name="to" value="{{ request('to') }}" class="form-control">
            </div>
            <div class="form-group col-md-1 mb-2">
                <button class="btn btn-primary btn-block">تصفية</button>
            </div>
        </form>
    </div>
</div>

<form method="post" action="{{ route('orders.bulk') }}" class="card">
    @csrf
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <button name="action" value="mark_paid" class="btn btn-outline-primary btn-sm">تعيين مدفوعة</button>
            <button name="action" value="mark_shipped" class="btn btn-outline-info btn-sm">تعيين مُرسلة</button>
            <button name="action" value="mark_cancelled" class="btn btn-outline-danger btn-sm">إلغاء</button>
        </div>
        <div>
            @php
                $sort = request('sort', 'created_at');
                $dir = request('dir', 'desc') === 'asc' ? 'asc' : 'desc';
                $flip = $dir === 'asc' ? 'desc' : 'asc';
            @endphp
            <a class="btn btn-link btn-sm"
                href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'dir' => $flip]) }}">
                ترتيب بالتاريخ {{ $dir === 'asc' ? '↑' : '↓' }}
            </a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th><input type="checkbox"
                            onclick="document.querySelectorAll('.chk').forEach(c=>c.checked=this.checked)"></th>
                    <th>#</th>
                    <th>المشتري</th>
                    <th>الحالة</th>
                    <th>الدفع</th>
                    <th>العناصر</th>
                    <th>العمولة</th>
                    <th>صافي التجّار</th>
                    <th>الإجمالي</th>
                    <th>تاريخ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                    <tr>
                        <td><input class="chk" type="checkbox" name="ids[]" value="{{ $o->id }}"></td>
                        <td>{{ $o->id }}</td>
                        <td>
                            <div>{{ $o->user?->name ?? '—' }}</div>
                            <small class="text-muted">{{ $o->user?->email }}</small>
                        </td>
                        <td>
                            @include('components.status-badge', ['status' => $o->status])
                        </td>
                        <td><span class="badge badge-secondary">{{ $o->payment_method }}</span></td>
                        <td>{{ $o->items_count }}</td>
                        <td>{{ number_format($o->commission_sum ?? 0, 2) }}</td>
                        <td>{{ number_format($o->payout_sum ?? 0, 2) }}</td>
                        <td class="font-weight-bold">{{ number_format($o->grand_total, 2) }}</td>
                        <td>{{ $o->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('orders.show', $o) }}" class="btn btn-sm btn-link">تفاصيل</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted">لا توجد نتائج.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        {{-- Laravel >=9: --}}
        {!! $orders->links('pagination::bootstrap-4') !!}
    </div>
</form>

@endsection
