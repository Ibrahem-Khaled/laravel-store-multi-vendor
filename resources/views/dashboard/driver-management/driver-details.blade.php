@extends('dashboard.driver-management.layout')

@section('title', 'تفاصيل السواق')
@section('page-title', 'تفاصيل السواق: ' . $driver->user->name)

@section('page-actions')
    <a href="{{ route('admin.driver.edit', $driver->id) }}" class="btn btn-warning">
        <i class="fas fa-edit me-2"></i>
        تعديل
    </a>
    <a href="{{ route('admin.driver.drivers') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-right me-2"></i>
        العودة للقائمة
    </a>
@endsection

@section('content')
<!-- Driver Info Card -->
<div class="row mb-4">
    <div class="col-xl-8 col-lg-7">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user me-2"></i>
                    معلومات السواق
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">الاسم:</td>
                                <td>{{ $driver->user->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">البريد الإلكتروني:</td>
                                <td>{{ $driver->user->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">رقم الهاتف:</td>
                                <td>{{ $driver->phone_number }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">رقم الرخصة:</td>
                                <td>{{ $driver->license_number }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">نوع المركبة:</td>
                                <td>{{ __('driver.vehicle_type.' . $driver->vehicle_type) }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">موديل المركبة:</td>
                                <td>{{ $driver->vehicle_model }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">رقم اللوحة:</td>
                                <td>{{ $driver->vehicle_plate_number }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">المدينة:</td>
                                <td>{{ $driver->city }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">الحي:</td>
                                <td>{{ $driver->neighborhood }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">تاريخ الانضمام:</td>
                                <td>{{ $driver->created_at->format('Y-m-d') }}</td>
                            </tr>
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
                    <i class="fas fa-chart-bar me-2"></i>
                    الإحصائيات
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="bg-primary text-white rounded p-3">
                            <h4 class="mb-0">{{ $stats['total_orders'] }}</h4>
                            <small>إجمالي الطلبات</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="bg-success text-white rounded p-3">
                            <h4 class="mb-0">{{ $stats['completed_orders'] }}</h4>
                            <small>مكتملة</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="bg-warning text-white rounded p-3">
                            <h4 class="mb-0">{{ $stats['active_orders'] }}</h4>
                            <small>نشطة</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="bg-danger text-white rounded p-3">
                            <h4 class="mb-0">{{ $stats['cancelled_orders'] }}</h4>
                            <small>ملغية</small>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="text-center">
                    <h5>التقييم</h5>
                    <div class="d-flex justify-content-center align-items-center mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $driver->rating ? 'text-warning' : 'text-muted' }} fa-lg"></i>
                        @endfor
                    </div>
                    <h4 class="text-primary">{{ number_format($driver->rating, 1) }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status and Performance -->
<div class="row mb-4">
    <div class="col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog me-2"></i>
                    الحالة والإعدادات
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="text-center p-3 {{ $driver->is_active ? 'bg-success' : 'bg-danger' }} text-white rounded mb-3">
                            <i class="fas fa-{{ $driver->is_active ? 'check' : 'times' }} fa-2x mb-2"></i>
                            <div>{{ $driver->is_active ? 'نشط' : 'غير نشط' }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 {{ $driver->is_available ? 'bg-success' : 'bg-warning' }} text-white rounded mb-3">
                            <i class="fas fa-{{ $driver->is_available ? 'check' : 'clock' }} fa-2x mb-2"></i>
                            <div>{{ $driver->is_available ? 'متاح' : 'مشغول' }}</div>
                        </div>
                    </div>
                </div>

                @if($driver->is_supervisor)
                <div class="alert alert-warning">
                    <i class="fas fa-crown me-2"></i>
                    هذا السواق لديه صلاحيات مشرف
                </div>
                @endif

                @if($driver->working_hours)
                <div class="mt-3">
                    <h6>ساعات العمل:</h6>
                    <div class="row">
                        @foreach($driver->working_hours as $day => $hours)
                        <div class="col-6 mb-2">
                            <small class="fw-bold">{{ __('driver.days.' . $day) }}:</small>
                            <div>{{ $hours['start'] ?? '--' }} - {{ $hours['end'] ?? '--' }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    الأداء الشهري
                </h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="monthlyPerformanceChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>
                    الطلبات الأخيرة
                </h5>
                <a href="{{ route('admin.driver.orders', ['driver_id' => $driver->id]) }}" class="btn btn-sm btn-outline-primary">
                    عرض الكل
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>العميل</th>
                                <th>العنوان</th>
                                <th>الحالة</th>
                                <th>تاريخ التخصيص</th>
                                <th>رسوم التوصيل</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <strong>#{{ $order->order->id }}</strong>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $order->order->user->name }}</div>
                                        <small class="text-muted">{{ $order->order->user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-bold">{{ $order->order->userAddress->city }}</div>
                                        <small class="text-muted">{{ $order->order->userAddress->neighborhood }}</small>
                                    </div>
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
                                </td>
                                <td>
                                    <small>{{ $order->assigned_at->format('Y-m-d H:i') }}</small>
                                </td>
                                <td>
                                    <strong>{{ number_format($order->delivery_fee, 2) }} ريال</strong>
                                </td>
                                <td>
                                    <a href="{{ route('admin.driver.order.details', $order->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
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
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Monthly Performance Chart
    const performanceCtx = document.getElementById('monthlyPerformanceChart').getContext('2d');
    const performanceData = @json($monthlyPerformance);

    new Chart(performanceCtx, {
        type: 'bar',
        data: {
            labels: performanceData.map(item => item.month_name),
            datasets: [
                {
                    label: 'الطلبات',
                    data: performanceData.map(item => item.orders_count),
                    backgroundColor: 'rgba(102, 126, 234, 0.8)',
                    borderColor: '#667eea',
                    borderWidth: 1
                },
                {
                    label: 'المكتملة',
                    data: performanceData.map(item => item.completed_count),
                    backgroundColor: 'rgba(40, 167, 69, 0.8)',
                    borderColor: '#28a745',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endsection
