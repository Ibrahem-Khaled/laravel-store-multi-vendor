@extends('dashboard.driver-management.layout')

@section('title', 'تفاصيل الطلب')
@section('page-title', 'تفاصيل الطلب #' . $driverOrder->order->id)

@section('page-actions')
    <a href="{{ route('admin.driver.orders') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-right me-2"></i>
        العودة للقائمة
    </a>
@endsection

@section('content')
<!-- Order Overview -->
<div class="row mb-4">
    <div class="col-xl-8 col-lg-7">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>
                    معلومات الطلب
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">رقم الطلب:</td>
                                <td>#{{ $driverOrder->order->id }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">الحالة:</td>
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
                                    <span class="badge bg-{{ $statusColors[$driverOrder->status] ?? 'secondary' }}">
                                        {{ __('driver.status.' . $driverOrder->status) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">نوع التخصيص:</td>
                                <td>
                                    <span class="badge bg-{{ $driverOrder->assignment_type === 'auto' ? 'primary' : 'secondary' }}">
                                        {{ $driverOrder->assignment_type === 'auto' ? 'تلقائي' : 'يدوي' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">تاريخ التخصيص:</td>
                                <td>{{ $driverOrder->assigned_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">رسوم التوصيل:</td>
                                <td><strong>{{ number_format($driverOrder->delivery_fee, 2) }} ريال</strong></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">إجمالي الطلب:</td>
                                <td><strong>{{ number_format($driverOrder->order->grand_total, 2) }} ريال</strong></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">طريقة الدفع:</td>
                                <td>{{ $driverOrder->order->payment_method }}</td>
                            </tr>
                            @if($driverOrder->accepted_at)
                            <tr>
                                <td class="fw-bold">تاريخ القبول:</td>
                                <td>{{ $driverOrder->accepted_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            @endif
                            @if($driverOrder->picked_up_at)
                            <tr>
                                <td class="fw-bold">تاريخ الاستلام:</td>
                                <td>{{ $driverOrder->picked_up_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            @endif
                            @if($driverOrder->delivered_at)
                            <tr>
                                <td class="fw-bold">تاريخ التسليم:</td>
                                <td>{{ $driverOrder->delivered_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-5">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog me-2"></i>
                    الإجراءات السريعة
                </h5>
            </div>
            <div class="card-body">
                @if($driverOrder->status === 'assigned')
                <div class="d-grid gap-2 mb-3">
                    <button type="button" class="btn btn-warning" onclick="reassignOrder()">
                        <i class="fas fa-exchange-alt me-2"></i>
                        إعادة تخصيص
                    </button>
                </div>
                @endif

                @if($driverOrder->status === 'delivered' && !$confirmationStatus['admin_confirmed'])
                <div class="d-grid gap-2 mb-3">
                    <button type="button" class="btn btn-success" onclick="confirmDelivery()">
                        <i class="fas fa-check me-2"></i>
                        تأكيد التسليم
                    </button>
                </div>
                @endif

                @if(in_array($driverOrder->status, ['assigned', 'accepted']))
                <div class="d-grid gap-2 mb-3">
                    <button type="button" class="btn btn-danger" onclick="cancelOrder()">
                        <i class="fas fa-times me-2"></i>
                        إلغاء الطلب
                    </button>
                </div>
                @endif

                <hr>

                <div class="text-center">
                    <h6>حالة التأكيد</h6>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="p-2 {{ $confirmationStatus['driver_confirmed'] ? 'bg-success' : 'bg-light' }} rounded">
                                <i class="fas fa-truck {{ $confirmationStatus['driver_confirmed'] ? 'text-white' : 'text-muted' }}"></i>
                                <div class="small {{ $confirmationStatus['driver_confirmed'] ? 'text-white' : 'text-muted' }}">السواق</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 {{ $confirmationStatus['customer_confirmed'] ? 'bg-success' : 'bg-light' }} rounded">
                                <i class="fas fa-user {{ $confirmationStatus['customer_confirmed'] ? 'text-white' : 'text-muted' }}"></i>
                                <div class="small {{ $confirmationStatus['customer_confirmed'] ? 'text-white' : 'text-muted' }}">العميل</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-2 {{ $confirmationStatus['admin_confirmed'] ? 'bg-success' : 'bg-light' }} rounded">
                                <i class="fas fa-user-shield {{ $confirmationStatus['admin_confirmed'] ? 'text-white' : 'text-muted' }}"></i>
                                <div class="small {{ $confirmationStatus['admin_confirmed'] ? 'text-white' : 'text-muted' }}">الإدارة</div>
                            </div>
                        </div>
                    </div>

                    @if($confirmationStatus['is_fully_confirmed'])
                    <div class="alert alert-success mt-3 mb-0">
                        <i class="fas fa-check-circle me-2"></i>
                        تم التأكيد الكامل
                    </div>
                    @else
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="fas fa-clock me-2"></i>
                        في انتظار التأكيد
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Customer and Driver Info -->
<div class="row mb-4">
    <div class="col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>
                    معلومات العميل
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">الاسم:</td>
                                <td>{{ $driverOrder->order->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">البريد الإلكتروني:</td>
                                <td>{{ $driverOrder->order->user->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">رقم الهاتف:</td>
                                <td>{{ $driverOrder->order->user->phone }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            @if($driverOrder->order->userAddress)
                                <tr>
                                    <td class="fw-bold">المدينة:</td>
                                    <td>{{ $driverOrder->order->userAddress->city ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">الحي:</td>
                                    <td>{{ $driverOrder->order->userAddress->neighborhood ?? 'غير محدد' }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">العنوان:</td>
                                    <td>{{ $driverOrder->order->userAddress->address ?? 'غير محدد' }}</td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="2" class="text-muted">لا يوجد عنوان متاح</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-truck me-2"></i>
                    معلومات السواق
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">الاسم:</td>
                                <td>{{ $driverOrder->driver->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">رقم الهاتف:</td>
                                <td>{{ $driverOrder->driver->phone_number }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">نوع المركبة:</td>
                                <td>{{ __('driver.vehicle_type.' . $driverOrder->driver->vehicle_type) }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">موديل المركبة:</td>
                                <td>{{ $driverOrder->driver->vehicle_model }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">رقم اللوحة:</td>
                                <td>{{ $driverOrder->driver->vehicle_plate_number }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">التقييم:</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $driverOrder->driver->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                        <span class="ms-2">{{ number_format($driverOrder->driver->rating, 1) }}</span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Items -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-box me-2"></i>
                    منتجات الطلب
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>الكمية</th>
                                <th>سعر الوحدة</th>
                                <th>المجموع</th>
                                <th>التاجر</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($driverOrder->order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                             alt="{{ $item->product->name }}"
                                             class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $item->product->name }}</div>
                                            <small class="text-muted">{{ $item->product->description }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 2) }} ريال</td>
                                <td><strong>{{ number_format($item->quantity * $item->unit_price, 2) }} ريال</strong></td>
                                <td>
                                    @if($item->merchant)
                                        {{ $item->merchant->name }}
                                    @else
                                        <span class="text-muted">غير محدد</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <th colspan="3">المجموع الفرعي:</th>
                                <th>{{ number_format($driverOrder->order->subtotal, 2) }} ريال</th>
                                <th></th>
                            </tr>
                            @if($driverOrder->order->shipping_total > 0)
                            <tr class="table-light">
                                <th colspan="3">رسوم الشحن:</th>
                                <th>{{ number_format($driverOrder->order->shipping_total, 2) }} ريال</th>
                                <th></th>
                            </tr>
                            @endif
                            @if($driverOrder->order->discount_total > 0)
                            <tr class="table-light">
                                <th colspan="3">الخصم:</th>
                                <th>-{{ number_format($driverOrder->order->discount_total, 2) }} ريال</th>
                                <th></th>
                            </tr>
                            @endif
                            <tr class="table-primary">
                                <th colspan="3">المجموع الكلي:</th>
                                <th>{{ number_format($driverOrder->order->grand_total, 2) }} ريال</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Timeline -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>
                    سجل الطلب
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6>تم تخصيص الطلب</h6>
                            <p class="text-muted mb-1">{{ $driverOrder->assigned_at->format('Y-m-d H:i:s') }}</p>
                            @if($driverOrder->assignedBy)
                                <small class="text-muted">بواسطة: {{ $driverOrder->assignedBy->name }}</small>
                            @endif
                        </div>
                    </div>

                    @if($driverOrder->accepted_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <h6>تم قبول الطلب</h6>
                            <p class="text-muted">{{ $driverOrder->accepted_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($driverOrder->picked_up_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6>تم استلام الطلب</h6>
                            <p class="text-muted">{{ $driverOrder->picked_up_at->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>
                    @endif

                    @if($driverOrder->delivered_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6>تم تسليم الطلب</h6>
                            <p class="text-muted">{{ $driverOrder->delivered_at->format('Y-m-d H:i:s') }}</p>
                            @if($driverOrder->delivery_notes)
                                <div class="mt-2">
                                    <strong>ملاحظات التسليم:</strong>
                                    <p class="mb-0">{{ $driverOrder->delivery_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($driverOrder->cancelled_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-danger"></div>
                        <div class="timeline-content">
                            <h6>تم إلغاء الطلب</h6>
                            <p class="text-muted">{{ $driverOrder->cancelled_at->format('Y-m-d H:i:s') }}</p>
                            @if($driverOrder->cancellation_reason)
                                <div class="mt-2">
                                    <strong>سبب الإلغاء:</strong>
                                    <p class="mb-0">{{ $driverOrder->cancellation_reason }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
@include('dashboard.driver-management.partials.order-modals')
@endsection

@section('scripts')
<script>
    function reassignOrder() {
        // Implementation for reassign order
        window.location.href = '{{ route("admin.driver.orders") }}?reassign={{ $driverOrder->id }}';
    }

    function confirmDelivery() {
        const modal = new bootstrap.Modal(document.getElementById('confirmDeliveryModal'));
        modal.show();
    }

    function cancelOrder() {
        const modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
        modal.show();
    }
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #e9ecef;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #e9ecef;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -29px;
    top: 17px;
    width: 2px;
    height: calc(100% + 15px);
    background: #e9ecef;
}
</style>
@endsection
