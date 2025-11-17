@extends('dashboard.driver-management.layout')

@section('title', 'تعديل السواق')
@section('page-title', 'تعديل السواق: ' . $driver->user->name)

@section('page-actions')
    <a href="{{ route('admin.driver.details', $driver->id) }}" class="btn btn-info">
        <i class="fas fa-eye me-2"></i>
        عرض التفاصيل
    </a>
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
                    <i class="fas fa-edit me-2"></i>
                    تعديل بيانات السواق
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.driver.update', $driver->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- User Information (Read Only) -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="fas fa-user me-2"></i>
                                معلومات المستخدم
                            </h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الاسم</label>
                            <input type="text" class="form-control" value="{{ $driver->user->name }}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="text" class="form-control" value="{{ $driver->user->email }}" readonly>
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
                                   id="license_number" name="license_number" value="{{ old('license_number', $driver->license_number) }}" required>
                            @error('license_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                   id="phone_number" name="phone_number" value="{{ old('phone_number', $driver->phone_number) }}" required>
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
                                <option value="car" {{ old('vehicle_type', $driver->vehicle_type) == 'car' ? 'selected' : '' }}>سيارة</option>
                                <option value="motorcycle" {{ old('vehicle_type', $driver->vehicle_type) == 'motorcycle' ? 'selected' : '' }}>دراجة نارية</option>
                                <option value="bicycle" {{ old('vehicle_type', $driver->vehicle_type) == 'bicycle' ? 'selected' : '' }}>دراجة هوائية</option>
                            </select>
                            @error('vehicle_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="vehicle_model" class="form-label">موديل المركبة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('vehicle_model') is-invalid @enderror"
                                   id="vehicle_model" name="vehicle_model" value="{{ old('vehicle_model', $driver->vehicle_model) }}" required>
                            @error('vehicle_model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="vehicle_plate_number" class="form-label">رقم اللوحة <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('vehicle_plate_number') is-invalid @enderror"
                                   id="vehicle_plate_number" name="vehicle_plate_number" value="{{ old('vehicle_plate_number', $driver->vehicle_plate_number) }}" required>
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
                                   id="city" name="city" value="{{ old('city', $driver->city) }}" required>
                            @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="neighborhood" class="form-label">الحي <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('neighborhood') is-invalid @enderror"
                                   id="neighborhood" name="neighborhood" value="{{ old('neighborhood', $driver->neighborhood) }}" required>
                            @error('neighborhood')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">خط العرض</label>
                            <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror"
                                   id="latitude" name="latitude" value="{{ old('latitude', $driver->latitude) }}">
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">خط الطول</label>
                            <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror"
                                   id="longitude" name="longitude" value="{{ old('longitude', $driver->longitude) }}">
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
                                    $workingHours = $driver->working_hours ?? [];
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
                                                           value="{{ old('working_hours.' . $day . '.start', $workingHours[$day]['start'] ?? '08:00') }}">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label small">إلى</label>
                                                    <input type="time" class="form-control form-control-sm"
                                                           name="working_hours[{{ $day }}][end]"
                                                           value="{{ old('working_hours.' . $day . '.end', $workingHours[$day]['end'] ?? '18:00') }}">
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
                        <div class="col-12 mb-3">
                            <label for="service_areas_map" class="form-label">حدد المناطق على الخريطة</label>
                            <div id="service_areas_map" style="height: 400px; width: 100%; border-radius: 8px; border: 1px solid #d1d3e2;"></div>
                            <small class="form-text text-muted">انقر على الخريطة لإضافة منطقة جديدة أو استخدم مربع البحث</small>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="input-group">
                                <input type="text" class="form-control" id="service_areas_search" placeholder="ابحث عن منطقة أو انقر على الخريطة">
                                <button class="btn btn-primary" type="button" onclick="searchAndAddArea()">
                                    <i class="fas fa-search"></i> بحث وإضافة
                                </button>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">المناطق المضافة:</label>
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
                        <div class="col-md-3 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                       value="1" {{ old('is_active', $driver->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    نشط
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_available" name="is_available"
                                       value="1" {{ old('is_available', $driver->is_available) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_available">
                                    متاح للعمل
                                </label>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_supervisor" name="is_supervisor"
                                       value="1" {{ old('is_supervisor', $driver->is_supervisor) ? 'checked' : '' }}>
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
                                <a href="{{ route('admin.driver.details', $driver->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>
                                    إلغاء
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    حفظ التغييرات
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

@push('styles')
<style>
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
</style>
@endpush

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAbQpG4lOycVS9bIMDtaKciz_mPlBJ33vw&libraries=places,drawing&language=ar&callback=initGoogleMaps"></script>
<script>
    // Service Areas Management with Google Maps
    let serviceAreas = [];
    let map;
    let markers = [];
    let autocomplete;
    let drawingManager;
    let polygons = [];

    // Load existing service areas
    @if($driver->service_areas)
        try {
            serviceAreas = @json($driver->service_areas ?? []);
        } catch(e) {
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
            drawingMode: google.maps.drawing.OverlayType.POLYGON,
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
            autocomplete.set('query', searchInput.value);
            // The place_changed event will handle adding the area
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

    // Initialize Google Maps callback
    function initGoogleMaps() {
        if (document.getElementById('service_areas_map')) {
            initMap();
            updateServiceAreasList();
        }
    }

    // Initialize map when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof google !== 'undefined' && google.maps) {
                initGoogleMaps();
            }
        });
    } else {
        if (typeof google !== 'undefined' && google.maps) {
            initGoogleMaps();
        }
    }

    // Allow Enter key in search
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('service_areas_search');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchAndAddArea();
                }
            });
        }
    });
</script>
@endpush
