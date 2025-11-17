@extends('dashboard.driver-management.layout')

@section('title', 'إدارة الطلبات')
@section('page-title', 'إدارة طلبات السواقين')

@section('content')
<!-- Filters -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i>
            فلترة الطلبات
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.driver.orders') }}">
            <div class="row g-3">
                <div class="col-md-2">
                    <label for="search" class="form-label">البحث</label>
                    <input type="text" class="form-control" id="search" name="search"
                           value="{{ request('search') }}" placeholder="رقم الطلب، اسم العميل">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">الحالة</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">جميع الحالات</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ __('driver.status.' . $status) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="driver_id" class="form-label">السواق</label>
                    <select class="form-select" id="driver_id" name="driver_id">
                        <option value="">جميع السواقين</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                                {{ $driver->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="assignment_type" class="form-label">نوع التخصيص</label>
                    <select class="form-select" id="assignment_type" name="assignment_type">
                        <option value="">جميع الأنواع</option>
                        <option value="auto" {{ request('assignment_type') == 'auto' ? 'selected' : '' }}>تلقائي</option>
                        <option value="manual" {{ request('assignment_type') == 'manual' ? 'selected' : '' }}>يدوي</option>
                    </select>
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
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="row g-3 mt-2">
                <div class="col-md-3">
                    <label for="date_from" class="form-label">من تاريخ</label>
                    <input type="date" class="form-control" id="date_from" name="date_from"
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">إلى تاريخ</label>
                    <input type="date" class="form-control" id="date_to" name="date_to"
                           value="{{ request('date_to') }}">
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="fas fa-shopping-cart me-2"></i>
            الطلبات ({{ $orders->total() }})
        </h5>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="exportOrders()">
                <i class="fas fa-download me-1"></i>
                تصدير
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>العميل</th>
                        <th>السواق</th>
                        <th>العنوان</th>
                        <th>الحالة</th>
                        <th>نوع التخصيص</th>
                        <th>تاريخ التخصيص</th>
                        <th>رسوم التوصيل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <strong>#{{ $order->order->id }}</strong>
                            <br>
                            <small class="text-muted">إجمالي: {{ number_format($order->order->grand_total, 2) }} ريال</small>
                        </td>
                        <td>
                            <div>
                                <div class="fw-bold">{{ $order->order->user->name }}</div>
                                <small class="text-muted">{{ $order->order->user->email }}</small>
                                <br>
                                <small class="text-muted">{{ $order->order->user->phone }}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <div class="fw-bold">{{ $order->driver->user->name }}</div>
                                <small class="text-muted">{{ $order->driver->vehicle_type }}</small>
                                <br>
                                <small class="text-muted">{{ $order->driver->vehicle_plate_number }}</small>
                            </div>
                        </td>
                        <td>
                            @if($order->order->userAddress)
                                <div>
                                    <div class="fw-bold">{{ $order->order->userAddress->city ?? 'غير محدد' }}</div>
                                    <small class="text-muted">{{ $order->order->userAddress->neighborhood ?? 'غير محدد' }}</small>
                                    <br>
                                    <small class="text-muted">{{ Str::limit($order->order->userAddress->address ?? '', 30) }}</small>
                                </div>
                            @else
                                <span class="text-muted">لا يوجد عنوان</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'assigned' => 'warning',
                                    'accepted' => 'info',
                                    'picked_up' => 'primary',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                {{ __('driver.status.' . $order->status) }}
                            </span>

                            @if($order->status === 'delivered')
                                @php
                                    $confirmationStatus = $order->getConfirmationStatus();
                                @endphp
                                @if($confirmationStatus['is_fully_confirmed'])
                                    <br><span class="badge bg-success">مؤكد</span>
                                @else
                                    <br><span class="badge bg-warning">في انتظار التأكيد</span>
                                @endif
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $order->assignment_type === 'auto' ? 'primary' : 'secondary' }}">
                                {{ $order->assignment_type === 'auto' ? 'تلقائي' : 'يدوي' }}
                            </span>
                        </td>
                        <td>
                            <small>{{ $order->assigned_at->format('Y-m-d H:i') }}</small>
                            @if($order->assignedBy)
                                <br>
                                <small class="text-muted">بواسطة: {{ $order->assignedBy->name }}</small>
                            @endif
                        </td>
                        <td>
                            <strong>{{ number_format($order->delivery_fee, 2) }} ريال</strong>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.driver.order.details', $order->id) }}"
                                   class="btn btn-sm btn-outline-primary" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($order->status === 'assigned')
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-warning dropdown-toggle"
                                            data-bs-toggle="dropdown" title="إعادة تخصيص">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @foreach($drivers->where('id', '!=', $order->driver_id)->take(5) as $driver)
                                        <li>
                                            <form method="POST" action="{{ route('admin.driver.order.reassign', $order->id) }}"
                                                  style="display: inline;">
                                                @csrf
                                                <input type="hidden" name="new_driver_id" value="{{ $driver->id }}">
                                                <button type="submit" class="dropdown-item"
                                                        onclick="return confirm('هل أنت متأكد من إعادة التخصيص؟')">
                                                    {{ $driver->user->name }}
                                                </button>
                                            </form>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                @if($order->status === 'delivered' && !$order->getConfirmationStatus()['admin_confirmed'])
                                <button type="button" class="btn btn-sm btn-outline-success"
                                        onclick="confirmDelivery({{ $order->id }})" title="تأكيد التسليم">
                                    <i class="fas fa-check"></i>
                                </button>
                                @endif

                                @if(in_array($order->status, ['assigned', 'accepted']))
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="cancelOrder({{ $order->id }})" title="إلغاء الطلب">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                <p>لا توجد طلبات</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Confirm Delivery Modal -->
<div class="modal fade" id="confirmDeliveryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد التسليم</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="confirmDeliveryForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="notes" class="form-label">ملاحظات (اختياري)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"
                                  placeholder="أي ملاحظات إضافية حول التسليم..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">تأكيد التسليم</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إلغاء الطلب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="cancelOrderForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reason" class="form-label">سبب الإلغاء <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="reason" name="reason" rows="3"
                                  placeholder="يرجى توضيح سبب إلغاء الطلب..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">إلغاء الطلب</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmDelivery(orderId) {
        const form = document.getElementById('confirmDeliveryForm');
        form.action = `/admin/driver-orders/${orderId}/confirm`;

        const modal = new bootstrap.Modal(document.getElementById('confirmDeliveryModal'));
        modal.show();
    }

    function cancelOrder(orderId) {
        const form = document.getElementById('cancelOrderForm');
        form.action = `/admin/driver-orders/${orderId}/cancel`;

        const modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
        modal.show();
    }

    function exportOrders() {
        const params = new URLSearchParams(window.location.search);
        params.set('export', '1');
        window.open(`{{ route('admin.driver.orders') }}?${params.toString()}`, '_blank');
    }
</script>
@endsection
