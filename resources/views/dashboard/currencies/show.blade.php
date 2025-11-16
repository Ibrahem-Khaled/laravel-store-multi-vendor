@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">تفاصيل العملة</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('currencies.index') }}">العملات</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $currency->code }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        <div class="row">
            {{-- معلومات العملة --}}
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">معلومات العملة</h6>
                        <a href="{{ route('currencies.edit', $currency->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">رمز العملة:</th>
                                <td><strong>{{ $currency->code }}</strong></td>
                            </tr>
                            <tr>
                                <th>الاسم بالعربية:</th>
                                <td>{{ $currency->name_ar }}</td>
                            </tr>
                            <tr>
                                <th>الاسم بالإنجليزية:</th>
                                <td>{{ $currency->name_en }}</td>
                            </tr>
                            <tr>
                                <th>الرمز:</th>
                                <td>{{ $currency->symbol }} @if($currency->symbol_ar) ({{ $currency->symbol_ar }}) @endif</td>
                            </tr>
                            <tr>
                                <th>سعر الصرف:</th>
                                <td>
                                    <strong class="text-primary">{{ number_format($currency->exchange_rate, 4) }}</strong>
                                </td>
                            </tr>
                            <tr>
                                <th>الحالة:</th>
                                <td>
                                    <span class="badge badge-{{ $currency->is_active ? 'success' : 'secondary' }}">
                                        {{ $currency->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>عملة أساسية:</th>
                                <td>
                                    @if($currency->is_base_currency)
                                        <span class="badge badge-warning">
                                            <i class="fas fa-star"></i> نعم
                                        </span>
                                    @else
                                        <span class="badge badge-secondary">لا</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>تاريخ الإنشاء:</th>
                                <td>{{ $currency->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>آخر تحديث:</th>
                                <td>{{ $currency->updated_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- تحديث سعر الصرف --}}
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">تحديث سعر الصرف</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('currencies.update-exchange-rate', $currency->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="exchange_rate">سعر الصرف الجديد:</label>
                                <input type="number" step="0.0001" min="0.0001" 
                                    class="form-control @error('exchange_rate') is-invalid @enderror" 
                                    id="exchange_rate" name="exchange_rate" 
                                    value="{{ old('exchange_rate', $currency->exchange_rate) }}" required>
                                @error('exchange_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="notes">ملاحظات (اختياري):</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                    id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> تحديث سعر الصرف
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- سجل تغييرات أسعار الصرف --}}
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">سجل تغييرات أسعار الصرف</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>التاريخ</th>
                                        <th>السعر</th>
                                        <th>السعر السابق</th>
                                        <th>التغيير %</th>
                                        <th>المحدث</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($history as $item)
                                        <tr>
                                            <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                            <td>
                                                <strong class="text-primary">{{ number_format($item->exchange_rate, 4) }}</strong>
                                            </td>
                                            <td>
                                                @if($item->previous_rate)
                                                    {{ number_format($item->previous_rate, 4) }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->change_percentage)
                                                    <span class="badge badge-{{ $item->change_percentage >= 0 ? 'success' : 'danger' }}">
                                                        {{ $item->change_percentage >= 0 ? '+' : '' }}{{ number_format($item->change_percentage, 2) }}%
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->updatedBy)
                                                    {{ $item->updatedBy->name }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if($item->notes)
                                            <tr>
                                                <td colspan="5" class="text-muted small">
                                                    <i class="fas fa-comment"></i> {{ $item->notes }}
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">لا يوجد سجل</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- الترقيم --}}
                        <div class="d-flex justify-content-center mt-3">
                            {{ $history->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

