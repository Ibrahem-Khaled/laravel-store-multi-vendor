@extends('layouts.app')

@push('styles')
<style>
    .driver-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        border: 1px solid #e3e6f0;
        overflow: hidden;
    }

    .driver-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        transform: translateY(-3px);
    }

    .driver-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e3e6f0;
    }

    .status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
        margin-left: 5px;
    }

    .status-available { background-color: #1cc88a; }
    .status-busy { background-color: #f6c23e; }
    .status-offline { background-color: #858796; }
    .status-inactive { background-color: #e74a3b; }

    .filter-card {
        background: #f8f9fc;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .rating-stars {
        color: #f6c23e;
    }

    .vehicle-icon {
        font-size: 1.5rem;
    }

    .stat-badge {
        padding: 0.3em 0.8em;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .table th {
        font-weight: 600;
        border-bottom: 2px solid #e3e6f0;
        white-space: nowrap;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.3s ease;
        margin: 0 2px;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
                        <i class="fas fa-users mr-2 text-primary"></i>
                        قائمة السواقين
                    </h1>
                    <nav aria-label="breadcrumb" class="mt-2">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.driver.dashboard') }}">إدارة السواقين</a>
                            </li>
                            <li class="breadcrumb-item active">قائمة السواقين</li>
                        </ol>
                    </nav>
                </div>
                <a href="{{ route('admin.driver.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus"></i> إضافة سواق جديد
                </a>
            </div>
        </div>
    </div>

    @include('components.alerts')

    {{-- Filters --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.driver.drivers') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">
                        <i class="fas fa-search text-primary"></i> البحث
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search"
                           value="{{ $filters['search'] }}" 
                           placeholder="اسم، بريد، رخصة، لوحة">
                </div>
                <div class="col-md-2">
                    <label for="city" class="form-label">المدينة</label>
                    <select class="form-control" id="city" name="city">
                        <option value="">جميع المدن</option>
                        @foreach($filterOptions['cities'] as $city)
                            <option value="{{ $city }}" {{ $filters['city'] == $city ? 'selected' : '' }}>
                                {{ $city }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="vehicle_type" class="form-label">نوع المركبة</label>
                    <select class="form-control" id="vehicle_type" name="vehicle_type">
                        <option value="">جميع الأنواع</option>
                        @foreach($filterOptions['vehicleTypes'] as $type)
                            <option value="{{ $type }}" {{ $filters['vehicle_type'] == $type ? 'selected' : '' }}>
                                {{ $type === 'car' ? 'سيارة' : ($type === 'motorcycle' ? 'دراجة نارية' : 'دراجة هوائية') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="is_active" class="form-label">الحالة</label>
                    <select class="form-control" id="is_active" name="is_active">
                        <option value="">جميع الحالات</option>
                        <option value="1" {{ $filters['is_active'] === '1' ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ $filters['is_active'] === '0' ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="is_available" class="form-label">التوفر</label>
                    <select class="form-control" id="is_available" name="is_available">
                        <option value="">الكل</option>
                        <option value="1" {{ $filters['is_available'] === '1' ? 'selected' : '' }}>متاح</option>
                        <option value="0" {{ $filters['is_available'] === '0' ? 'selected' : '' }}>غير متاح</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i>
                        </button>
                    </div>
                </div>
            </div>
            @if(array_filter($filters))
                <div class="row mt-2">
                    <div class="col-12">
                        <a href="{{ route('admin.driver.drivers') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-times"></i> إلغاء الفلاتر
                        </a>
                    </div>
                </div>
            @endif
        </form>
    </div>

    {{-- Drivers Table --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-2"></i>
                السواقين ({{ $drivers->total() }})
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th width="20%">السواق</th>
                            <th width="15%">المركبة</th>
                            <th width="12%">الموقع</th>
                            <th width="12%">الحالة</th>
                            <th width="12%">الإحصائيات</th>
                            <th width="12%">التقييم</th>
                            <th width="17%">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($drivers as $driver)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $driver->user->avatar ? asset('storage/' . $driver->user->avatar) : asset('img/default-avatar.png') }}"
                                             alt="{{ $driver->user->name }}"
                                             class="driver-avatar mr-3"
                                             onerror="this.onerror=null; this.src='{{ asset('img/default-avatar.png') }}';">
                                        <div>
                                            <div class="font-weight-bold">{{ $driver->user->name }}</div>
                                            <small class="text-muted d-block">
                                                <i class="fas fa-envelope"></i> {{ $driver->user->email }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-phone"></i> {{ $driver->phone_number }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="font-weight-bold mb-1">
                                            <i class="fas fa-{{ $driver->vehicle_type === 'car' ? 'car' : ($driver->vehicle_type === 'motorcycle' ? 'motorcycle' : 'bicycle') }} vehicle-icon text-primary"></i>
                                            {{ $driver->vehicle_type === 'car' ? 'سيارة' : ($driver->vehicle_type === 'motorcycle' ? 'دراجة نارية' : 'دراجة هوائية') }}
                                        </div>
                                        <small class="text-muted d-block">{{ $driver->vehicle_model }}</small>
                                        <small class="text-muted">
                                            <i class="fas fa-hashtag"></i> {{ $driver->vehicle_plate_number }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="font-weight-bold">
                                            <i class="fas fa-map-marker-alt text-danger"></i> {{ $driver->city }}
                                        </div>
                                        <small class="text-muted">{{ $driver->neighborhood }}</small>
                                    </div>
                                </td>
                                <td>
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
                                    <div>
                                        <span class="status-indicator {{ $statusClass }}"></span>
                                        <span class="font-weight-bold">{{ $statusText }}</span>
                                        @if($driver->is_supervisor)
                                            <br><span class="badge badge-warning mt-1">مشرف</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="stat-badge badge-primary">
                                            <i class="fas fa-shipping-fast"></i> نشط: {{ $driver->current_orders_count }}
                                        </span>
                                        <br>
                                        <span class="stat-badge badge-info mt-1">
                                            <i class="fas fa-check-circle"></i> إجمالي: {{ $driver->total_deliveries }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="mb-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= round($driver->rating) ? 'rating-stars' : 'text-muted' }}" style="font-size: 0.9rem;"></i>
                                                @endfor
                                            </div>
                                            <small class="text-muted font-weight-bold">{{ number_format($driver->rating, 1) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.driver.details', $driver->id) }}"
                                           class="btn btn-sm btn-info btn-action"
                                           title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.driver.edit', $driver->id) }}"
                                           class="btn btn-sm btn-primary btn-action"
                                           title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger btn-action"
                                                title="حذف" 
                                                onclick="deleteDriver({{ $driver->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-users fa-4x text-gray-300 mb-3"></i>
                                    <p class="text-muted">لا توجد سواقين</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($drivers->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $drivers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من حذف هذا السواق؟</p>
                <p class="text-danger"><small>هذا الإجراء لا يمكن التراجع عنه.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
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

@push('scripts')
<script>
    function deleteDriver(driverId) {
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `/dashboard/driver-management/drivers/${driverId}`;

        $('#deleteModal').modal('show');
    }
</script>
@endpush
