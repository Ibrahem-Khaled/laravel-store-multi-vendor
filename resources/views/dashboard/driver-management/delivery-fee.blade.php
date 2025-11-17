@extends('dashboard.driver-management.layout')

@section('title', 'تسعيرة التوصيل')
@section('page-title', 'إدارة تسعيرة التوصيل')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-dollar-sign me-2"></i>
                    إعدادات تسعيرة التوصيل
                </h6>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.delivery-fee.store') }}" method="POST">
                    @csrf

                    <!-- الرسوم الأساسية -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-left-primary h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">
                                        <i class="fas fa-coins me-2"></i>
                                        الرسوم الأساسية
                                    </h5>
                                    <div class="mb-3">
                                        <label for="base_fee" class="form-label">
                                            الرسوم الأساسية للتوصيل (ريال)
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" 
                                                   class="form-control @error('base_fee') is-invalid @enderror" 
                                                   id="base_fee" 
                                                   name="base_fee" 
                                                   value="{{ old('base_fee', $settings['base_fee']) }}" 
                                                   step="0.01" 
                                                   min="0" 
                                                   required>
                                            <span class="input-group-text">ريال</span>
                                        </div>
                                        <small class="form-text text-muted">
                                            الرسوم الأساسية التي تُضاف قبل حساب رسوم المسافة
                                        </small>
                                        @error('base_fee')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="distance_fee_per_km" class="form-label">
                                            رسوم المسافة لكل كيلومتر (ريال)
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" 
                                                   class="form-control @error('distance_fee_per_km') is-invalid @enderror" 
                                                   id="distance_fee_per_km" 
                                                   name="distance_fee_per_km" 
                                                   value="{{ old('distance_fee_per_km', $settings['distance_fee_per_km']) }}" 
                                                   step="0.01" 
                                                   min="0" 
                                                   required>
                                            <span class="input-group-text">ريال/كم</span>
                                        </div>
                                        <small class="form-text text-muted">
                                            الرسوم الإضافية التي تُضاف لكل كيلومتر من المسافة
                                        </small>
                                        @error('distance_fee_per_km')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-left-info h-100">
                                <div class="card-body">
                                    <h5 class="card-title text-info">
                                        <i class="fas fa-sliders-h me-2"></i>
                                        الحدود
                                    </h5>
                                    <div class="mb-3">
                                        <label for="min_fee" class="form-label">
                                            الحد الأدنى لرسوم التوصيل (ريال)
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" 
                                                   class="form-control @error('min_fee') is-invalid @enderror" 
                                                   id="min_fee" 
                                                   name="min_fee" 
                                                   value="{{ old('min_fee', $settings['min_fee']) }}" 
                                                   step="0.01" 
                                                   min="0" 
                                                   required>
                                            <span class="input-group-text">ريال</span>
                                        </div>
                                        <small class="form-text text-muted">
                                            الحد الأدنى لرسوم التوصيل (حتى لو كانت الحسابات أقل)
                                        </small>
                                        @error('min_fee')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="max_fee" class="form-label">
                                            الحد الأقصى لرسوم التوصيل (ريال)
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number" 
                                                   class="form-control @error('max_fee') is-invalid @enderror" 
                                                   id="max_fee" 
                                                   name="max_fee" 
                                                   value="{{ old('max_fee', $settings['max_fee']) }}" 
                                                   step="0.01" 
                                                   min="0" 
                                                   required>
                                            <span class="input-group-text">ريال</span>
                                        </div>
                                        <small class="form-text text-muted">
                                            الحد الأقصى لرسوم التوصيل (حتى لو كانت الحسابات أكثر)
                                        </small>
                                        @error('max_fee')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- مضاعفات أنواع المركبات -->
                    <div class="card border-left-success mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0 text-success">
                                <i class="fas fa-car me-2"></i>
                                مضاعفات أنواع المركبات
                            </h5>
                            <small class="text-muted">تحديد نسبة الرسوم لكل نوع مركبة (1.0 = 100% من الرسوم)</small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="car_multiplier" class="form-label">
                                            <i class="fas fa-car me-2"></i>
                                            مضاعف رسوم السيارة
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" 
                                               class="form-control @error('car_multiplier') is-invalid @enderror" 
                                               id="car_multiplier" 
                                               name="car_multiplier" 
                                               value="{{ old('car_multiplier', $settings['car_multiplier']) }}" 
                                               step="0.1" 
                                               min="0" 
                                               max="2" 
                                               required>
                                        <small class="form-text text-muted">
                                            مثال: 1.0 = 100% من الرسوم، 0.8 = 80% من الرسوم
                                        </small>
                                        @error('car_multiplier')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="motorcycle_multiplier" class="form-label">
                                            <i class="fas fa-motorcycle me-2"></i>
                                            مضاعف رسوم الدراجة النارية
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" 
                                               class="form-control @error('motorcycle_multiplier') is-invalid @enderror" 
                                               id="motorcycle_multiplier" 
                                               name="motorcycle_multiplier" 
                                               value="{{ old('motorcycle_multiplier', $settings['motorcycle_multiplier']) }}" 
                                               step="0.1" 
                                               min="0" 
                                               max="2" 
                                               required>
                                        <small class="form-text text-muted">
                                            مثال: 0.8 = 80% من الرسوم
                                        </small>
                                        @error('motorcycle_multiplier')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="bicycle_multiplier" class="form-label">
                                            <i class="fas fa-bicycle me-2"></i>
                                            مضاعف رسوم الدراجة الهوائية
                                            <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" 
                                               class="form-control @error('bicycle_multiplier') is-invalid @enderror" 
                                               id="bicycle_multiplier" 
                                               name="bicycle_multiplier" 
                                               value="{{ old('bicycle_multiplier', $settings['bicycle_multiplier']) }}" 
                                               step="0.1" 
                                               min="0" 
                                               max="2" 
                                               required>
                                        <small class="form-text text-muted">
                                            مثال: 0.6 = 60% من الرسوم
                                        </small>
                                        @error('bicycle_multiplier')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- مثال على الحساب -->
                    <div class="card border-left-warning mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0 text-warning">
                                <i class="fas fa-calculator me-2"></i>
                                مثال على الحساب
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info mb-0">
                                <strong>مثال:</strong> إذا كانت الرسوم الأساسية = <span id="example_base">10</span> ريال، 
                                والمسافة = 5 كيلومتر، ورسوم المسافة = <span id="example_distance">0.5</span> ريال/كم، 
                                ونوع المركبة = دراجة نارية (<span id="example_multiplier">0.8</span>)
                                <br>
                                <strong>الحساب:</strong> (10 + 5 × 0.5) × 0.8 = <span id="example_result">10</span> ريال
                            </div>
                        </div>
                    </div>

                    <!-- أزرار الحفظ -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.driver.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            إلغاء
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            حفظ الإعدادات
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // تحديث المثال عند تغيير القيم
    function updateExample() {
        const base = parseFloat(document.getElementById('base_fee').value) || 10;
        const distance = parseFloat(document.getElementById('distance_fee_per_km').value) || 0.5;
        const multiplier = parseFloat(document.getElementById('motorcycle_multiplier').value) || 0.8;
        const exampleDistance = 5; // مثال: 5 كيلومتر
        
        const result = (base + exampleDistance * distance) * multiplier;
        
        document.getElementById('example_base').textContent = base;
        document.getElementById('example_distance').textContent = distance;
        document.getElementById('example_multiplier').textContent = multiplier;
        document.getElementById('example_result').textContent = result.toFixed(2);
    }

    // إضافة مستمعات الأحداث
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = ['base_fee', 'distance_fee_per_km', 'motorcycle_multiplier'];
        inputs.forEach(id => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener('input', updateExample);
            }
        });
        updateExample();
    });
</script>
@endpush

