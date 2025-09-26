@extends('dashboard.driver-management.layout')

@section('page-title', 'قائمة السواقين')

@section('page-actions')
    <a href="{{ route('admin.driver.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> إضافة سواق جديد
    </a>
@endsection

@section('content')
<!-- Filters -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-filter me-2"></i>
            فلترة السواقين
        </h6>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.driver.drivers') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">البحث</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="اسم، بريد، رخصة، لوحة">
                </div>
                <div class="col-md-2">
                    <label for="city" class="form-label">المدينة</label>
                    <select class="form-select" id="city" name="city">
                        <option value="">جميع المدن</option>
                        @foreach($cities as $city)
                            <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="neighborhood" class="form-label">الحي</label>
                    <select class="form-select" id="neighborhood" name="neighborhood">
                        <option value="">جميع الأحياء</option>
                        @foreach($neighborhoods as $neighborhood)
                            <option value="{{ $neighborhood }}" {{ request('neighborhood') == $neighborhood ? 'selected' : '' }}>
                                {{ $neighborhood }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="vehicle_type" class="form-label">نوع المركبة</label>
                    <select class="form-select" id="vehicle_type" name="vehicle_type">
                        <option value="">جميع الأنواع</option>
                        @foreach($vehicleTypes as $type)
                            <option value="{{ $type }}" {{ request('vehicle_type') == $type ? 'selected' : '' }}>
                                {{ __('driver.vehicle_type.' . $type) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="is_active" class="form-label">الحالة</label>
                    <select class="form-select" id="is_active" name="is_active">
                        <option value="">جميع الحالات</option>
                        <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Drivers Table -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-users me-2"></i>
            السواقين ({{ $drivers->total() }})
        </h6>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="exportDrivers()">
                <i class="fas fa-download me-1"></i>
                تصدير
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>السواق</th>
                        <th>المركبة</th>
                        <th>الموقع</th>
                        <th>الحالة</th>
                        <th>الإحصائيات</th>
                        <th>التقييم</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($drivers as $driver)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px;">
                                        {{ substr($driver->user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="fw-bold">{{ $driver->user->name }}</div>
                                    <small class="text-muted">
                                        {{ $driver->user->email }}<br>
                                        {{ $driver->phone_number }}
                                    </small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div class="fw-bold">{{ __('driver.vehicle_type.' . $driver->vehicle_type) }}</div>
                                <small class="text-muted">
                                    {{ $driver->vehicle_model }}<br>
                                    {{ $driver->vehicle_plate_number }}
                                </small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div class="fw-bold">{{ $driver->city }}</div>
                                <small class="text-muted">{{ $driver->neighborhood }}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                @php
                                    $statusClass = 'status-offline';
                                    $statusText = 'غير متاح';

                                    if (!$driver->is_active) {
                                        $statusClass = 'status-inactive';
                                        $statusText = 'غير نشط';
                                    } elseif ($driver->is_available) {
                                        $statusClass = 'status-available';
                                        $statusText = 'متاح';
                                    } elseif ($driver->current_orders_count > 0) {
                                        $statusClass = 'status-busy';
                                        $statusText = 'مشغول';
                                    }
                                @endphp

                                <span class="driver-status {{ $statusClass }}"></span>
                                {{ $statusText }}

                                @if($driver->is_supervisor)
                                    <br><span class="badge bg-warning">مشرف</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div>
                                <small class="text-muted">
                                    نشط: <strong>{{ $driver->current_orders_count }}</strong><br>
                                    إجمالي: <strong>{{ $driver->total_deliveries }}</strong>
                                </small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $driver->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <small class="text-muted">{{ number_format($driver->rating, 1) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.driver.details', $driver->id) }}"
                                   class="btn btn-sm btn-outline-primary" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.driver.edit', $driver->id) }}"
                                   class="btn btn-sm btn-outline-secondary" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                                        title="حذف" onclick="deleteDriver({{ $driver->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-users fa-3x mb-3"></i>
                                <p>لا توجد سواقين</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($drivers->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $drivers->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف هذا السواق؟</p>
                <p class="text-danger"><small>هذا الإجراء لا يمكن التراجع عنه.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<script>
    function deleteDriver(driverId) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `/dashboard/driver-management/drivers/${driverId}`;

        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }

    function exportDrivers() {
        const params = new URLSearchParams(window.location.search);
        window.open(`{{ route('admin.driver.drivers') }}?${params.toString()}&export=1`, '_blank');
    }
</script>
@endsection
