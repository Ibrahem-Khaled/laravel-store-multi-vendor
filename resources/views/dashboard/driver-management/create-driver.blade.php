@extends('layouts.app')

@push('styles')
<style>
    .form-section {
        background: #f8f9fc;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-left: 4px solid #4e73df;
        transition: all 0.3s ease;
    }

    .form-section:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .section-header {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e3e6f0;
    }

    .section-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 1rem;
        font-size: 1.2rem;
    }

    .section-icon.primary { background: #4e73df; color: white; }
    .section-icon.success { background: #1cc88a; color: white; }
    .section-icon.info { background: #36b9cc; color: white; }
    .section-icon.warning { background: #f6c23e; color: white; }
    .section-icon.danger { background: #e74a3b; color: white; }

    .form-label {
        font-weight: 600;
        color: #5a5c69;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #d1d3e2;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }

    .working-hours-card {
        border: 1px solid #e3e6f0;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        background: white;
    }

    .working-hours-card:hover {
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }

    .service-area-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 0.5rem 1rem;
        background: #4e73df;
        color: white;
        border-radius: 20px;
        margin: 0.25rem;
        font-size: 0.9rem;
    }

    .service-area-badge .remove-btn {
        cursor: pointer;
        opacity: 0.8;
        transition: opacity 0.2s;
    }

    .service-area-badge .remove-btn:hover {
        opacity: 1;
    }

    .custom-switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .custom-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .slider {
        background-color: #4e73df;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }

    .required-field::after {
        content: " *";
        color: #e74a3b;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-user-plus mr-2 text-primary"></i>
                        إضافة سواق جديد
                    </h1>
                    <nav aria-label="breadcrumb" class="mt-2">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.driver.drivers') }}">السواقين</a>
                            </li>
                            <li class="breadcrumb-item active">إضافة جديد</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('admin.driver.drivers') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i> العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    @include('components.alerts')

    <form method="POST" action="{{ route('admin.driver.store') }}" id="driverForm">
        @csrf

        <div class="row">
            <div class="col-xl-10 col-lg-12 mx-auto">
                {{-- User Selection --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon primary">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 font-weight-bold text-gray-800">اختيار المستخدم</h5>
                            <small class="text-muted">اختر المستخدم الذي سيصبح سواق</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <label for="user_id" class="form-label required-field">المستخدم</label>
                            <select class="form-select @error('user_id') is-invalid @enderror" 
                                    id="user_id" 
                                    name="user_id" 
                                    required>
                                <option value="">-- اختر المستخدم --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} - {{ $user->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @if($users->isEmpty())
                                <div class="alert alert-warning mt-2">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    لا يوجد مستخدمون متاحون. يجب إنشاء مستخدم أولاً.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Driver Information --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon success">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 font-weight-bold text-gray-800">معلومات السواق</h5>
                            <small class="text-muted">البيانات الأساسية للسواق</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="license_number" class="form-label required-field">رقم الرخصة</label>
                                <input type="text" 
                                       class="form-control @error('license_number') is-invalid @enderror"
                                       id="license_number" 
                                       name="license_number" 
                                       value="{{ old('license_number') }}" 
                                       placeholder="أدخل رقم الرخصة"
                                       required>
                                @error('license_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone_number" class="form-label required-field">رقم الهاتف</label>
                                <input type="text" 
                                       class="form-control @error('phone_number') is-invalid @enderror"
                                       id="phone_number" 
                                       name="phone_number" 
                                       value="{{ old('phone_number') }}" 
                                       placeholder="أدخل رقم الهاتف"
                                       required>
                                @error('phone_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Vehicle Information --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon info">
                            <i class="fas fa-car"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 font-weight-bold text-gray-800">معلومات المركبة</h5>
                            <small class="text-muted">تفاصيل المركبة المستخدمة في التوصيل</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vehicle_type" class="form-label required-field">نوع المركبة</label>
                                <select class="form-select @error('vehicle_type') is-invalid @enderror" 
                                        id="vehicle_type" 
                                        name="vehicle_type" 
                                        required>
                                    <option value="">-- اختر نوع المركبة --</option>
                                    <option value="car" {{ old('vehicle_type') == 'car' ? 'selected' : '' }}>
                                        <i class="fas fa-car"></i> سيارة
                                    </option>
                                    <option value="motorcycle" {{ old('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>
                                        دراجة نارية
                                    </option>
                                    <option value="bicycle" {{ old('vehicle_type') == 'bicycle' ? 'selected' : '' }}>
                                        دراجة هوائية
                                    </option>
                                </select>
                                @error('vehicle_type')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vehicle_model" class="form-label required-field">موديل المركبة</label>
                                <input type="text" 
                                       class="form-control @error('vehicle_model') is-invalid @enderror"
                                       id="vehicle_model" 
                                       name="vehicle_model" 
                                       value="{{ old('vehicle_model') }}" 
                                       placeholder="مثال: تويوتا كورولا 2020"
                                       required>
                                @error('vehicle_model')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vehicle_plate_number" class="form-label required-field">رقم اللوحة</label>
                                <input type="text" 
                                       class="form-control @error('vehicle_plate_number') is-invalid @enderror"
                                       id="vehicle_plate_number" 
                                       name="vehicle_plate_number" 
                                       value="{{ old('vehicle_plate_number') }}" 
                                       placeholder="مثال: أ ب ج 123"
                                       required>
                                @error('vehicle_plate_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Location Information --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon warning">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 font-weight-bold text-gray-800">الموقع الجغرافي</h5>
                            <small class="text-muted">موقع السواق الأساسي</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city" class="form-label required-field">المدينة</label>
                                <input type="text" 
                                       class="form-control @error('city') is-invalid @enderror"
                                       id="city" 
                                       name="city" 
                                       value="{{ old('city') }}" 
                                       placeholder="أدخل اسم المدينة"
                                       required>
                                @error('city')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="neighborhood" class="form-label required-field">الحي</label>
                                <input type="text" 
                                       class="form-control @error('neighborhood') is-invalid @enderror"
                                       id="neighborhood" 
                                       name="neighborhood" 
                                       value="{{ old('neighborhood') }}" 
                                       placeholder="أدخل اسم الحي"
                                       required>
                                @error('neighborhood')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="latitude" class="form-label">خط العرض (Latitude)</label>
                                <input type="number" 
                                       step="any" 
                                       class="form-control @error('latitude') is-invalid @enderror"
                                       id="latitude" 
                                       name="latitude" 
                                       value="{{ old('latitude') }}"
                                       placeholder="مثال: 24.7136">
                                <small class="form-text text-muted">اختياري - للإحداثيات الدقيقة</small>
                                @error('latitude')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="longitude" class="form-label">خط الطول (Longitude)</label>
                                <input type="number" 
                                       step="any" 
                                       class="form-control @error('longitude') is-invalid @enderror"
                                       id="longitude" 
                                       name="longitude" 
                                       value="{{ old('longitude') }}"
                                       placeholder="مثال: 46.6753">
                                <small class="form-text text-muted">اختياري - للإحداثيات الدقيقة</small>
                                @error('longitude')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Working Hours --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon danger">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 font-weight-bold text-gray-800">ساعات العمل</h5>
                            <small class="text-muted">حدد أوقات العمل لكل يوم</small>
                        </div>
                    </div>
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
                            <div class="col-md-6 col-lg-4">
                                <div class="working-hours-card">
                                    <h6 class="font-weight-bold mb-3">
                                        <i class="fas fa-calendar-day text-primary"></i>
                                        {{ $dayNames[$day] }}
                                    </h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-label small">من</label>
                                            <input type="time" 
                                                   class="form-control form-control-sm"
                                                   name="working_hours[{{ $day }}][start]"
                                                   value="{{ old('working_hours.' . $day . '.start', '08:00') }}">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small">إلى</label>
                                            <input type="time" 
                                                   class="form-control form-control-sm"
                                                   name="working_hours[{{ $day }}][end]"
                                                   value="{{ old('working_hours.' . $day . '.end', '18:00') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Service Areas --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon primary">
                            <i class="fas fa-map"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 font-weight-bold text-gray-800">المناطق المخدومة</h5>
                            <small class="text-muted">حدد المناطق التي يخدمها السواق على الخريطة</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="service_areas_map" class="form-label">حدد المناطق على الخريطة</label>
                        <div id="service_areas_map" style="height: 400px; width: 100%; border-radius: 8px; border: 1px solid #d1d3e2;"></div>
                        <small class="form-text text-muted">انقر على الخريطة لإضافة منطقة جديدة أو استخدم مربع البحث</small>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   id="service_areas_search" 
                                   placeholder="ابحث عن منطقة أو انقر على الخريطة">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button" onclick="searchAndAddArea()">
                                    <i class="fas fa-search"></i> بحث وإضافة
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">المناطق المضافة:</label>
                        <div id="service_areas_list" class="mt-2">
                            <!-- Service areas will be added here dynamically -->
                        </div>
                        <input type="hidden" name="service_areas" id="service_areas_hidden">
                    </div>
                </div>

                {{-- Status Settings --}}
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon success">
                            <i class="fas fa-cog"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 font-weight-bold text-gray-800">إعدادات الحالة</h5>
                            <small class="text-muted">الصلاحيات والإعدادات الخاصة</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">مشرف على السواقين</label>
                                <div class="d-flex align-items-center">
                                    <label class="custom-switch">
                                        <input type="checkbox" 
                                               id="is_supervisor" 
                                               name="is_supervisor"
                                               value="1" 
                                               {{ old('is_supervisor') ? 'checked' : '' }}>
                                        <span class="slider"></span>
                                    </label>
                                    <span class="ml-3">تفعيل صلاحيات المشرف</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit Buttons --}}
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.driver.drivers') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> إلغاء
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save"></i> حفظ السواق
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAbQpG4lOycVS9bIMDtaKciz_mPlBJ33vw&libraries=places,drawing&language=ar"></script>
<script>
    // Service Areas Management with Google Maps
    let serviceAreas = [];
    let map;
    let markers = [];
    let autocomplete;
    let drawingManager;
    let polygons = [];

    // Load existing service areas if editing
    @if(old('service_areas'))
        try {
            const oldAreas = @json(old('service_areas'));
            if (Array.isArray(oldAreas)) {
                serviceAreas = oldAreas;
            } else if (typeof oldAreas === 'string') {
                serviceAreas = JSON.parse(oldAreas);
            }
        } catch(e) {
            console.error('Error parsing service areas:', e);
            serviceAreas = [];
        }
    @endif

    // Initialize Google Map
    function initMap() {
        // Default center (Riyadh, Saudi Arabia)
        const defaultCenter = { lat: 24.7136, lng: 46.6753 };
        
        map = new google.maps.Map(document.getElementById('service_areas_map'), {
            center: defaultCenter,
            zoom: 11,
            mapTypeControl: true,
            streetViewControl: true,
            fullscreenControl: true
        });

        // Initialize Places Autocomplete
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('service_areas_search'),
            {
                types: ['geocode', 'establishment'],
                componentRestrictions: { country: 'sa' }, // Saudi Arabia
                fields: ['geometry', 'name', 'formatted_address']
            }
        );

        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            if (place.geometry) {
                map.setCenter(place.geometry.location);
                map.setZoom(15);
                addAreaFromPlace(place);
            }
        });

        // Initialize Drawing Manager for polygons
        drawingManager = new google.maps.drawing.DrawingManager({
            drawingMode: null,
            drawingControl: true,
            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [
                    google.maps.drawing.OverlayType.POLYGON,
                    google.maps.drawing.OverlayType.CIRCLE
                ]
            },
            polygonOptions: {
                fillColor: '#4e73df',
                fillOpacity: 0.3,
                strokeWeight: 2,
                strokeColor: '#4e73df',
                clickable: false,
                editable: true,
                zIndex: 1
            },
            circleOptions: {
                fillColor: '#4e73df',
                fillOpacity: 0.3,
                strokeWeight: 2,
                strokeColor: '#4e73df',
                clickable: false,
                editable: true,
                zIndex: 1
            }
        });

        drawingManager.setMap(map);

        // Listen for polygon/circle completion
        google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
            const overlay = event.overlay;
            const type = event.type;
            
            if (type === google.maps.drawing.OverlayType.POLYGON) {
                const path = overlay.getPath();
                const bounds = new google.maps.LatLngBounds();
                path.forEach(function(latLng) {
                    bounds.extend(latLng);
                });
                const center = bounds.getCenter();
                addAreaFromCoordinates(center.lat(), center.lng(), overlay, 'polygon');
            } else if (type === google.maps.drawing.OverlayType.CIRCLE) {
                const center = overlay.getCenter();
                addAreaFromCoordinates(center.lat(), center.lng(), overlay, 'circle');
            }
            
            drawingManager.setDrawingMode(null);
        });

        // Click on map to add marker
        map.addListener('click', function(event) {
            addAreaFromCoordinates(event.latLng.lat(), event.latLng.lng(), null, 'point');
        });

        // Load existing areas on map
        loadExistingAreas();
    }

    function addAreaFromPlace(place) {
        const areaName = place.name || place.formatted_address;
        if (areaName && !serviceAreas.some(a => a.name === areaName)) {
            const area = {
                name: areaName,
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng(),
                type: 'point',
                address: place.formatted_address
            };
            serviceAreas.push(area);
            addMarker(area);
            updateServiceAreasList();
        }
    }

    function addAreaFromCoordinates(lat, lng, overlay, type) {
        // Use Geocoder to get address
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ location: { lat, lng } }, function(results, status) {
            if (status === 'OK' && results[0]) {
                const areaName = results[0].formatted_address || `منطقة ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                if (!serviceAreas.some(a => a.name === areaName)) {
                    const area = {
                        name: areaName,
                        lat: lat,
                        lng: lng,
                        type: type,
                        address: results[0].formatted_address
                    };
                    if (overlay) {
                        area.overlay = overlay;
                        polygons.push(overlay);
                    }
                    serviceAreas.push(area);
                    if (!overlay) {
                        addMarker(area);
                    }
                    updateServiceAreasList();
                }
            } else {
                // Fallback if geocoding fails
                const areaName = `منطقة ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                if (!serviceAreas.some(a => a.name === areaName)) {
                    const area = {
                        name: areaName,
                        lat: lat,
                        lng: lng,
                        type: type
                    };
                    if (overlay) {
                        area.overlay = overlay;
                        polygons.push(overlay);
                    }
                    serviceAreas.push(area);
                    if (!overlay) {
                        addMarker(area);
                    }
                    updateServiceAreasList();
                }
            }
        });
    }

    function addMarker(area) {
        const marker = new google.maps.Marker({
            position: { lat: area.lat, lng: area.lng },
            map: map,
            title: area.name,
            icon: {
                url: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
            }
        });
        
        const infoWindow = new google.maps.InfoWindow({
            content: `<div><strong>${area.name}</strong></div>`
        });
        
        marker.addListener('click', function() {
            infoWindow.open(map, marker);
        });
        
        markers.push(marker);
    }

    function loadExistingAreas() {
        serviceAreas.forEach(function(area) {
            if (area.type === 'point') {
                addMarker(area);
            }
        });
    }

    function searchAndAddArea() {
        const searchInput = document.getElementById('service_areas_search');
        if (searchInput.value.trim()) {
            // Trigger autocomplete selection
            const event = new Event('place_changed');
            autocomplete.set('query', searchInput.value);
        }
    }

    function updateServiceAreasList() {
        const list = document.getElementById('service_areas_list');
        const hidden = document.getElementById('service_areas_hidden');

        list.innerHTML = '';
        
        if (serviceAreas.length === 0) {
            list.innerHTML = '<p class="text-muted">لا توجد مناطق مضافة</p>';
        } else {
            serviceAreas.forEach((area, index) => {
                const badge = document.createElement('span');
                badge.className = 'service-area-badge';
                badge.innerHTML = `
                    ${area.name}
                    <i class="fas fa-times remove-btn" onclick="removeServiceArea(${index})"></i>
                `;
                list.appendChild(badge);
            });
        }

        // Store areas as JSON (without overlay references)
        const areasToStore = serviceAreas.map(area => ({
            name: area.name,
            lat: area.lat,
            lng: area.lng,
            type: area.type,
            address: area.address
        }));
        hidden.value = JSON.stringify(areasToStore);
    }

    function removeServiceArea(index) {
        const area = serviceAreas[index];
        
        // Remove marker if exists
        if (area.type === 'point') {
            const marker = markers.find(m => 
                m.getPosition().lat() === area.lat && 
                m.getPosition().lng() === area.lng
            );
            if (marker) {
                marker.setMap(null);
                markers = markers.filter(m => m !== marker);
            }
        }
        
        // Remove polygon/circle if exists
        if (area.overlay) {
            area.overlay.setMap(null);
            polygons = polygons.filter(p => p !== area.overlay);
        }
        
        serviceAreas.splice(index, 1);
        updateServiceAreasList();
    }

    // Initialize map when page loads
    window.addEventListener('load', function() {
        initMap();
        updateServiceAreasList();
    });

    // Allow Enter key in search
    document.getElementById('service_areas_search').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchAndAddArea();
        }
    });

    // Form validation
    document.getElementById('driverForm').addEventListener('submit', function(e) {
        const userId = document.getElementById('user_id').value;
        if (!userId) {
            e.preventDefault();
            alert('يجب اختيار مستخدم');
            return false;
        }
    });
</script>
@endpush
