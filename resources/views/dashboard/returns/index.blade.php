@extends('layouts.app')

@section('title', 'إدارة المرتجعات')
@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">إدارة المرتجعات والاستبدال</h1>
    </div>

    @include('components.alerts')

    <!-- إحصائيات -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">قيد المراجعة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">موافق عليها</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['approved'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">قيد المعالجة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['processing'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cog fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">مكتملة</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الفلاتر -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">فلترة المرتجعات</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('returns.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>البحث</label>
                            <input type="text" name="search" class="form-control" 
                                   value="{{ request('search') }}" 
                                   placeholder="رقم الطلب / اسم العميل">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>الحالة</label>
                            <select name="status" class="form-control">
                                <option value="">كل الحالات</option>
                                <option value="pending" @selected(request('status') == 'pending')>قيد المراجعة</option>
                                <option value="approved" @selected(request('status') == 'approved')>موافق عليها</option>
                                <option value="rejected" @selected(request('status') == 'rejected')>مرفوضة</option>
                                <option value="processing" @selected(request('status') == 'processing')>قيد المعالجة</option>
                                <option value="completed" @selected(request('status') == 'completed')>مكتملة</option>
                                <option value="cancelled" @selected(request('status') == 'cancelled')>ملغاة</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>النوع</label>
                            <select name="type" class="form-control">
                                <option value="">كل الأنواع</option>
                                <option value="return" @selected(request('type') == 'return')>إرجاع</option>
                                <option value="refund" @selected(request('type') == 'refund')>استرداد</option>
                                <option value="replacement" @selected(request('type') == 'replacement')>استبدال</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>من تاريخ</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>إلى تاريخ</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول المرتجعات -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">قائمة المرتجعات</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>رقم الطلب</th>
                            <th>العميل</th>
                            <th>النوع</th>
                            <th>الحالة</th>
                            <th>السبب</th>
                            <th>مبلغ الاسترداد</th>
                            <th>تاريخ الطلب</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($returns as $return)
                            <tr>
                                <td>{{ $return->id }}</td>
                                <td>
                                    <a href="{{ route('orders.show', $return->order_id) }}" class="text-primary">
                                        #{{ $return->order_id }}
                                    </a>
                                </td>
                                <td>
                                    <div>{{ $return->user->name }}</div>
                                    <small class="text-muted">{{ $return->user->email }}</small>
                                </td>
                                <td>
                                    @php
                                        $typeLabels = [
                                            'return' => 'إرجاع',
                                            'refund' => 'استرداد',
                                            'replacement' => 'استبدال'
                                        ];
                                    @endphp
                                    <span class="badge badge-info">{{ $typeLabels[$return->type] ?? $return->type }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'approved' => 'info',
                                            'rejected' => 'danger',
                                            'processing' => 'primary',
                                            'completed' => 'success',
                                            'cancelled' => 'secondary'
                                        ];
                                    @endphp
                                    <span class="badge badge-{{ $statusColors[$return->status] ?? 'secondary' }}">
                                        @if($return->status == 'pending') قيد المراجعة
                                        @elseif($return->status == 'approved') موافق عليها
                                        @elseif($return->status == 'rejected') مرفوضة
                                        @elseif($return->status == 'processing') قيد المعالجة
                                        @elseif($return->status == 'completed') مكتملة
                                        @elseif($return->status == 'cancelled') ملغاة
                                        @else {{ $return->status }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <small>{{ Str::limit($return->reason, 50) }}</small>
                                </td>
                                <td>
                                    @if($return->refund_amount)
                                        <strong>{{ number_format($return->refund_amount, 2) }} ريال</strong>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $return->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('returns.show', $return->id) }}" 
                                       class="btn btn-sm btn-info" title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">لا توجد مرتجعات</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $returns->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

