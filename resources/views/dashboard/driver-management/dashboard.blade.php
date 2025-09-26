@extends('dashboard.driver-management.layout')

@section('page-title', 'لوحة تحكم السواقين')

@section('page-actions')
    <a href="{{ route('admin.driver.drivers') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-users fa-sm text-white-50"></i> إدارة السواقين
    </a>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            إجمالي السواقين</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_drivers'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            السواقين المتاحين</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['available_drivers'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-truck fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            إجمالي الطلبات</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_orders'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            الطلبات المكتملة</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed_orders'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional Statistics -->
<div class="row">
    <div class="col-xl-2 col-md-4 col-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">طلبات معلقة</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_orders'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">قيد التنفيذ</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['in_progress_orders'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">ملغية</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['cancelled_orders'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">طلبات اليوم</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['today_orders'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">طلبات الشهر</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['monthly_orders'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-4 col-6 mb-4">
        <div class="card border-left-secondary shadow h-100 py-2">
            <div class="card-body">
                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">مشرفين</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['supervisor_drivers'] }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <!-- Monthly Orders Chart -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">إحصائيات الطلبات الشهرية</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="monthlyOrdersChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Drivers -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">أفضل السواقين</h6>
            </div>
            <div class="card-body">
                @foreach($topDrivers as $index => $driver)
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                             style="width: 40px; height: 40px;">
                            {{ $index + 1 }}
                        </div>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">{{ $driver->user->name }}</h6>
                        <small class="text-muted">
                            <i class="fas fa-star text-warning"></i>
                            {{ number_format($driver->rating, 1) }}
                            ({{ $driver->total_deliveries }} توصيلة)
                        </small>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge bg-success">{{ $driver->current_orders_count }} نشط</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders and Drivers Needing Attention -->
<div class="row">
    <!-- Recent Orders -->
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">الطلبات الأخيرة</h6>
                <a href="{{ route('admin.driver.orders') }}" class="btn btn-sm btn-primary">
                    عرض الكل
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>العميل</th>
                                <th>السواق</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
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
                                        <div class="fw-bold">{{ $order->driver->user->name }}</div>
                                        <small class="text-muted">{{ $order->driver->vehicle_type }}</small>
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
                                    <a href="{{ route('admin.driver.order.details', $order->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Drivers Needing Attention -->
    <div class="col-xl-4 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">سواقين يحتاجون انتباه</h6>
            </div>
            <div class="card-body">
                @if($driversNeedingAttention->count() > 0)
                    @foreach($driversNeedingAttention as $driver)
                    <div class="d-flex align-items-center mb-3 p-2 bg-light rounded">
                        <div class="flex-shrink-0">
                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 35px; height: 35px;">
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">{{ $driver->user->name }}</h6>
                            <small class="text-muted">
                                @if($driver->current_orders_count > 5)
                                    <i class="fas fa-exclamation-circle text-danger"></i>
                                    عبء عمل عالي ({{ $driver->current_orders_count }} طلبات)
                                @elseif($driver->rating < 3.0)
                                    <i class="fas fa-star text-warning"></i>
                                    تقييم منخفض ({{ number_format($driver->rating, 1) }})
                                @elseif(!$driver->is_available)
                                    <i class="fas fa-times-circle text-danger"></i>
                                    غير متاح
                                @endif
                            </small>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('admin.driver.details', $driver->id) }}"
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <p>جميع السواقين يعملون بشكل طبيعي</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<script>
    // Monthly Orders Chart
    const monthlyCtx = document.getElementById('monthlyOrdersChart').getContext('2d');
    const monthlyData = @json($monthlyStats);

    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month_name),
            datasets: [
                {
                    label: 'إجمالي الطلبات',
                    data: monthlyData.map(item => item.orders_count),
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'الطلبات المكتملة',
                    data: monthlyData.map(item => item.completed_count),
                    borderColor: '#1cc88a',
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'الطلبات الملغية',
                    data: monthlyData.map(item => item.cancelled_count),
                    borderColor: '#e74a3b',
                    backgroundColor: 'rgba(231, 74, 59, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: false
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
