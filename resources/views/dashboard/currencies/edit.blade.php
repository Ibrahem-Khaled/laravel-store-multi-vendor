@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">تعديل العملة</h1>
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
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">معلومات العملة</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('currencies.update', $currency->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="code">رمز العملة</label>
                                <input type="text" class="form-control" 
                                    id="code" value="{{ $currency->code }}" disabled>
                                <small class="form-text text-muted">لا يمكن تغيير رمز العملة بعد الإنشاء</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name_ar">الاسم بالعربية <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name_ar') is-invalid @enderror" 
                                            id="name_ar" name="name_ar" value="{{ old('name_ar', $currency->name_ar) }}" 
                                            maxlength="100" required>
                                        @error('name_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name_en">الاسم بالإنجليزية <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name_en') is-invalid @enderror" 
                                            id="name_en" name="name_en" value="{{ old('name_en', $currency->name_en) }}" 
                                            maxlength="100" required>
                                        @error('name_en')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="symbol">الرمز <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('symbol') is-invalid @enderror" 
                                            id="symbol" name="symbol" value="{{ old('symbol', $currency->symbol) }}" 
                                            maxlength="10" required>
                                        @error('symbol')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="symbol_ar">الرمز بالعربية</label>
                                        <input type="text" class="form-control @error('symbol_ar') is-invalid @enderror" 
                                            id="symbol_ar" name="symbol_ar" value="{{ old('symbol_ar', $currency->symbol_ar) }}" 
                                            maxlength="20">
                                        @error('symbol_ar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="exchange_rate">سعر الصرف مقابل الدولار <span class="text-danger">*</span></label>
                                <input type="number" step="0.0001" min="0.0001" 
                                    class="form-control @error('exchange_rate') is-invalid @enderror" 
                                    id="exchange_rate" name="exchange_rate" 
                                    value="{{ old('exchange_rate', $currency->exchange_rate) }}" required>
                                <small class="form-text text-muted">
                                    ملاحظة: لتحديث سعر الصرف فقط مع حفظ السجل، استخدم زر "تحديث سعر الصرف" في صفحة التفاصيل
                                </small>
                                @error('exchange_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" 
                                        name="is_active" value="1" {{ old('is_active', $currency->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        تفعيل العملة
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> حفظ التغييرات
                                </button>
                                <a href="{{ route('currencies.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> إلغاء
                                </a>
                                <a href="{{ route('currencies.show', $currency->id) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i> عرض التفاصيل
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

