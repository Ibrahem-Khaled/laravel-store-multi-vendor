@extends('dashboard.driver-management.layout')

@section('title', 'إضافة سواق جديد')
@section('page-title', 'إضافة سواق جديد')

@section('page-actions')
    <a href="{{ route('admin.driver.drivers') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-right me-2"></i>
        العودة للقائمة
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-xl-8 col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-plus me-2"></i>
                    بيانات السواق الجديد
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.driver.store') }}">
                    @csrf

                    <!-- User Selection -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-user me-2"></i>
                                اختيار المستخدم
                            </h6>
                        </div>
                        <div class="col-md-6">
                            <label for="user_id" class="form-label">المستخدم <span class="text-danger">*</span></label>
                            <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                <option value="">اختر المستخدم</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <!-- Driver Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-id-card me-2"></i>
                                معلومات السواق
                            </h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="license_number" class="form-label">رقم الرخصة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('license_number') is-invalid @enderror"
                                   id="license_number" name="license_number" value="{{ old('license_number') }}" required>
                            @error('license_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                   id="phone_number" name="phone_number" value="{{ old('phone_number') }}" required>
                            @error('phone_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Vehicle Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-car me-2"></i>
                                معلومات المركبة
                            </h6>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="vehicle_type" class="form-label">نوع المركبة <span class="text-danger">*</span></label>
                            <select class="form-select @error('vehicle_type') is-invalid @enderror" id="vehicle_type" name="vehicle_type" required>
                                <option value="">اختر نوع المركبة</option>
                                <option value="car" {{ old('vehicle_type') == 'car' ? 'selected' : '' }}>سيارة</option>
                                <option value="motorcycle" {{ old('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>دراجة نارية</option>
                                <option value="bicycle" {{ old('vehicle_type') == 'bicycle' ? 'selected' : '' }}>دراجة هوائية</option>
                            </select>
                            @error('vehicle_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="vehicle_model" class="form-label">موديل المركبة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('vehicle_model') is-invalid @enderror"
                                   id="vehicle_model" name="vehicle_model" value="{{ old('vehicle_model') }}" required>
                            @error('vehicle_model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="vehicle_plate_number" class="form-label">رقم اللوحة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('vehicle_plate_number') is-invalid @enderror"
                                   id="vehicle_plate_number" name="vehicle_plate_number" value="{{ old('vehicle_plate_number') }}" required>
                            @error('vehicle_plate_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                الموقع الجغرافي
                            </h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">المدينة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('city') is-invalid @enderror"
                                   id="city" name="city" value="{{ old('city') }}" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="neighborhood" class="form-label">الحي <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('neighborhood') is-invalid @enderror"
                                   id="neighborhood" name="neighborhood" value="{{ old('neighborhood') }}" required>
                            @error('neighborhood')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">خط العرض</label>
                            <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror"
                                   id="latitude" name="latitude" value="{{ old('latitude') }}">
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">خط الطول</label>
                            <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror"
                                   id="longitude" name="longitude" value="{{ old('longitude') }}">
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Working Hours -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-clock me-2"></i>
                                ساعات العمل
                            </h6>
                        </div>
                        <div class="col-12">
                            <div class="row">
                                @php
                                    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                    $dayNames = [
                                        'monday' => 'الاثنين',
                                        'tuesday' => 'الثلاثاء',
                                        'wednesday' => 'الأربعاء',
                                        'thursday' => 'الخميس',
                                        'friday' => 'الجمعة',
                                        'saturday' => 'السبت',
                                        'sunday' => 'الأحد'
                                    ];
                                @endphp
                                @foreach($days as $day)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card">
                                        <div class="card-body p-3">
                                            <h6 class="card-title">{{ $dayNames[$day] }}</h6>
                                            <div class="row">
                                                <div class="col-6">
                                                    <label class="form-label small">من</label>
                                                    <input type="time" class="form-control form-control-sm"
                                                           name="working_hours[{{ $day }}][start]"
                                                           value="{{ old('working_hours.' . $day . '.start', '08:00') }}">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label small">إلى</label>
                                                    <input type="time" class="form-control form-control-sm"
                                                           name="working_hours[{{ $day }}][end]"
                                                           value="{{ old('working_hours.' . $day . '.end', '18:00') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Service Areas -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-map me-2"></i>
                                المناطق المخدومة
                            </h6>
                        </div>
                        <div class="col-12">
                            <label for="service_areas" class="form-label">أضف المناطق التي يخدمها السواق (اضغط Enter بعد كل منطقة)</label>
                            <input type="text" class="form-control" id="service_areas_input" placeholder="اكتب اسم المنطقة واضغط Enter">
                            <div id="service_areas_list" class="mt-2">
                                <!-- Service areas will be added here dynamically -->
                            </div>
                            <input type="hidden" name="service_areas" id="service_areas_hidden">
                        </div>
                    </div>

                    <!-- Status Settings -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-cog me-2"></i>
                                إعدادات الحالة
                            </h6>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_supervisor" name="is_supervisor"
                                       value="1" {{ old('is_supervisor') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_supervisor">
                                    مشرف على السواقين
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.driver.drivers') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>
                                    إلغاء
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    حفظ السواق
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Service Areas Management
    let serviceAreas = [];

    document.getElementById('service_areas_input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const area = this.value.trim();
            if (area && !serviceAreas.includes(area)) {
                serviceAreas.push(area);
                updateServiceAreasList();
                this.value = '';
            }
        }
    });

    function updateServiceAreasList() {
        const list = document.getElementById('service_areas_list');
        const hidden = document.getElementById('service_areas_hidden');

        list.innerHTML = '';
        serviceAreas.forEach((area, index) => {
            const badge = document.createElement('span');
            badge.className = 'badge bg-primary me-2 mb-2';
            badge.innerHTML = `${area} <i class="fas fa-times ms-1" onclick="removeServiceArea(${index})"></i>`;
            list.appendChild(badge);
        });

        hidden.value = JSON.stringify(serviceAreas);
    }

    function removeServiceArea(index) {
        serviceAreas.splice(index, 1);
        updateServiceAreasList();
    }

    // Load existing service areas if editing
    @if(old('service_areas'))
        try {
            serviceAreas = JSON.parse('{{ old("service_areas") }}');
            updateServiceAreasList();
        } catch(e) {
            console.log('Error parsing service areas');
        }
    @endif
</script>
@endsection
