@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة العملات وأسعار الصرف</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">العملات</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات العملات --}}
        <div class="row mb-4">
            <x-stat-card title="إجمالي العملات" :count="$stats['total']" icon="coins" color="primary" class="mb-4" />
            <x-stat-card title="عملات نشطة" :count="$stats['active']" icon="check-circle" color="success" class="mb-4" />
            <x-stat-card title="عملات غير نشطة" :count="$stats['inactive']" icon="times-circle" color="warning" class="mb-4" />
            @if($stats['base_currency'])
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        العملة الأساسية
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $stats['base_currency']->code }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-star fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- بطاقة قائمة العملات --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">قائمة العملات</h6>
                <a href="{{ route('currencies.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> إضافة عملة
                </a>
            </div>
            <div class="card-body">
                {{-- نموذج البحث والتصفية --}}
                <form action="{{ route('currencies.index') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" name="search" class="form-control"
                                    placeholder="ابحث برمز العملة أو الاسم..." value="{{ $search }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" name="status">
                                    <option value="">كل الحالات</option>
                                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>نشطة فقط</option>
                                    <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>غير نشطة فقط</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" type="submit">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('currencies.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-redo"></i> إعادة تعيين
                            </a>
                        </div>
                    </div>
                </form>

                {{-- جدول العملات --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="50">#</th>
                                <th>رمز العملة</th>
                                <th>الاسم (عربي)</th>
                                <th>الاسم (إنجليزي)</th>
                                <th>الرمز</th>
                                <th>سعر الصرف</th>
                                <th width="100">الحالة</th>
                                <th width="100">أساسية</th>
                                <th width="200">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($currencies as $currency)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $currency->code }}</strong>
                                    </td>
                                    <td>{{ $currency->name_ar }}</td>
                                    <td>{{ $currency->name_en }}</td>
                                    <td>{{ $currency->symbol }} @if($currency->symbol_ar) ({{ $currency->symbol_ar }}) @endif</td>
                                    <td>
                                        <strong>{{ number_format($currency->exchange_rate, 4) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $currency->is_active ? 'success' : 'secondary' }}">
                                            {{ $currency->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($currency->is_base_currency)
                                            <span class="badge badge-warning">
                                                <i class="fas fa-star"></i> أساسية
                                            </span>
                                        @else
                                            <form action="{{ route('currencies.set-base', $currency->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning" 
                                                    onclick="return confirm('هل تريد تعيين هذه العملة كعملة أساسية؟')">
                                                    تعيين
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- زر عرض --}}
                                        <a href="{{ route('currencies.show', $currency->id) }}" 
                                            class="btn btn-sm btn-circle btn-info" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        {{-- زر تعديل --}}
                                        <a href="{{ route('currencies.edit', $currency->id) }}" 
                                            class="btn btn-sm btn-circle btn-primary" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        {{-- زر تفعيل/تعطيل --}}
                                        <form action="{{ route('currencies.toggle-status', $currency->id) }}" 
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-circle btn-{{ $currency->is_active ? 'warning' : 'success' }}" 
                                                title="{{ $currency->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                <i class="fas fa-{{ $currency->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                            </button>
                                        </form>

                                        {{-- زر حذف --}}
                                        @if(!$currency->is_base_currency)
                                            <form action="{{ route('currencies.destroy', $currency->id) }}" 
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('هل أنت متأكد من حذف هذه العملة؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-circle btn-danger" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">لا توجد عملات</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $currencies->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

