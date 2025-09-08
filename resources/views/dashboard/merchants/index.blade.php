{{-- resources/views/dashboard/merchants/index.blade.php --}}
@extends('layouts.app')
@section('title', 'التجّار')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h1 class="h3 mb-0">التجّار</h1>
    </div>

    {{-- الملخص العام للفترة --}}
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card border-left-success h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">إجمالي أرباح التجّار (صافي)</div>
                    <div class="h5 mb-0 font-weight-bold">{{ number_format($totalMerchantsEarnings, 2) }}</div>
                    <small class="text-muted">{{ $from->format('Y-m-d') }} → {{ $to->format('Y-m-d') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-left-primary h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">إجمالي عمولة المنصّة</div>
                    <div class="h5 mb-0 font-weight-bold">{{ number_format($totalPlatformCommission, 2) }}</div>
                    <small class="text-muted">{{ $from->format('Y-m-d') }} → {{ $to->format('Y-m-d') }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-left-info h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">مستحقات للتجّار (مفتوحة)</div>
                    <div class="h5 mb-0 font-weight-bold text-info">{{ number_format($openPayableSum, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-left-danger h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">مستحقات علينا من التجّار (مفتوحة)
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-danger">{{ number_format($openReceivableSum, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- فلاتر --}}
    <div class="card mb-3">
        <div class="card-body">
            <form class="form-row" method="get">
                <div class="form-group col-md-3 mb-2">
                    <input name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="بحث بالاسم/الإيميل/المعرّف">
                </div>
                <div class="form-group col-md-2 mb-2">
                    <select name="year" class="form-control">
                        @for ($y = now()->year; $y >= now()->year - 4; $y--)
                            <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group col-md-2 mb-2">
                    <select name="month" class="form-control">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" @selected($m == $month)>{{ $m }}</option>
                        @endfor
                    </select>
                </div>
                <div class="form-group col-md-3 mb-2">
                    <select name="balance" class="form-control">
                        <option value="any" @selected(request('balance', 'any') === 'any')>كل التجّار</option>
                        <option value="has_open" @selected(request('balance') === 'has_open')>له رصيد مفتوح</option>
                        <option value="no_open" @selected(request('balance') === 'no_open')>بدون رصيد مفتوح</option>
                        <option value="only_closed" @selected(request('balance') === 'only_closed')>عمليات مقفولة فقط</option>
                    </select>
                </div>
                <div class="form-group col-md-2 mb-2">
                    <button class="btn btn-primary btn-block">تصفية</button>
                </div>
            </form>
        </div>
    </div>

    {{-- جدول التجّار --}}
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>المنتجات</th>
                        <th>مستحقات له (مفتوحة)</th>
                        <th>مستحقات علينا (مفتوحة)</th>
                        <th>مستحقات له (مقفولة)</th>
                        <th>مستحقات علينا (مقفولة)</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($merchants as $m)
                        <tr>
                            <td>{{ $m->id }}</td>
                            <td>
                                <div>{{ $m->name }}</div>
                                <small class="text-muted">{{ $m->email }}</small>
                            </td>
                            <td>{{ $m->products_count }}</td>
                            <td class="text-success">{{ number_format($m->payable_open ?? 0, 2) }}</td>
                            <td class="text-danger">{{ number_format($m->receivable_open ?? 0, 2) }}</td>
                            <td class="text-muted">{{ number_format($m->payable_closed ?? 0, 2) }}</td>
                            <td class="text-muted">{{ number_format($m->receivable_closed ?? 0, 2) }}</td>
                            <td>
                                <a href="{{ route('merchants.show', $m) }}" class="btn btn-sm btn-link">تفاصيل</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">لا توجد نتائج.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {!! $merchants->links('pagination::bootstrap-4') !!}
        </div>
    </div>
@endsection
