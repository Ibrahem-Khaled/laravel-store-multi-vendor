{{-- resources/views/admin/merchants/show.blade.php --}}
@extends('layouts.app')
@section('title', "تاجر: {$merchant->name}")

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header">دفتر القيود (Ledger)</div>
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>تاريخ</th>
                                <th>اتجاه</th>
                                <th>مبلغ</th>
                                <th>حالة</th>
                                <th>طلب/عنصر</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ledger as $e)
                                <tr>
                                    <td>{{ $e->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <span
                                            class="badge badge-{{ $e->direction === 'payable_to_merchant' ? 'success' : 'danger' }}">
                                            {{ $e->direction === 'payable_to_merchant' ? 'مستحق للتاجر' : 'مستحق علينا' }}
                                        </span>
                                    </td>
                                    <td>{{ number_format($e->amount, 2) }}</td>
                                    <td>
                                        @include('components.status-badge', ['status' => $e->status])
                                    </td>
                                    <td>
                                        <a href="{{ route('orders.show', $e->order) }}">#{{ $e->order_id }}</a>
                                        <div class="text-muted small">{{ $e->item?->product?->name }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">لا توجد قيود.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {!! $ledger->links('pagination::bootstrap-4') !!}
                </div>
            </div>

            <div class="card">
                <div class="card-header">مدفوعات/تحصيلات</div>
                <ul class="list-group list-group-flush">
                    @forelse($payments as $p)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small">
                                    {{ $p->type === 'payout_to_merchant' ? 'صرف' : 'تحصيل' }} — {{ $p->method ?? '—' }}
                                </div>
                                <div class="text-muted small">
                                    {{ $p->paid_at?->format('Y-m-d') }} — {{ $p->reference ?? '—' }}
                                </div>
                            </div>
                            <div class="font-weight-bold">{{ number_format($p->amount, 2) }}</div>
                        </li>
                    @empty
                        <li class="list-group-item text-center text-muted">لا توجد حركات.</li>
                    @endforelse
                </ul>
                <div class="card-footer">
                    {!! $payments->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">تسجيل تسوية</div>
                <div class="card-body">
                    <form method="post" action="{{ route('merchants.settle', $merchant) }}">
                        @csrf
                        <div class="form-group">
                            <label>الاتجاه</label>
                            <select name="direction" class="form-control">
                                <option value="payable_to_merchant">صرف للتاجر (منصّة → تاجر)</option>
                                <option value="receivable_from_merchant">تحصيل عمولة (تاجر → منصّة)</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>المبلغ</label>
                            <input type="number" name="amount" step="0.01" min="0.01" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <label>الطريقة</label>
                            <input type="text" name="method" class="form-control" placeholder="تحويل بنكي / نقدًا ...">
                        </div>
                        <div class="form-group">
                            <label>مرجع العملية</label>
                            <input type="text" name="reference" class="form-control" placeholder="مثال: TXN-12345">
                        </div>
                        <button class="btn btn-primary btn-block">حفظ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
