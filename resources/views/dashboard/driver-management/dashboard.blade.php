@extends('layouts.app')

@push('styles')
<style>
    .stat-card {
        border-left: 4px solid;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.3;
        transition: all 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        opacity: 0.5;
        transform: scale(1.1);
    }

    .driver-card {
        border-radius: 12px;
        transition: all 0.3s ease;
        border: 1px solid #e3e6f0;
    }

    .driver-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 0.4em 0.8em;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    .chart-container {
        position: relative;
        height: 350px;
    }

    .top-driver-badge {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .gradient-success {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .gradient-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .gradient-warning {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
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
                        <i class="fas fa-truck mr-2 text-primary"></i>
                        لوحة تحكم السواقين
                    </h1>
                    <nav aria-label="breadcrumb" class="mt-2">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">
                                    <i class="fas fa-home"></i> لوحة التحكم
                                </a>
                            </li>
                            <li class="breadcrumb-item active">إدارة السواقين</li>
                        </ol>
                    </nav>
                </div>
                <div class="btn-group">
                    <a href="{{ route('admin.driver.drivers') }}" class="btn btn-primary">
                        <i class="fas fa-users"></i> إدارة السواقين
                    </a>
                    <a href="{{ route('admin.driver.orders') }}" class="btn btn-outline-primary">
                        <i class="fas fa-shopping-cart"></i> الطلبات
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('components.alerts')

    {{-- Main Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي السواقين
                            </div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $stats['total_drivers'] }}</div>
                            <div class="mt-2">
                                <small class="text-success">
                                    <i class="fas fa-arrow-up"></i> {{ $stats['active_drivers'] }} نشط
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users stat-icon text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                السواقين المتاحين
                            </div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $stats['available_drivers'] }}</div>
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i> جاهزون للعمل
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle stat-icon text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-info shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                إجمالي الطلبات
                            </div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $stats['total_orders'] }}</div>
                            <div class="mt-2">
                                <small class="text-info">
                                    <i class="fas fa-calendar-day"></i> {{ $stats['today_orders'] }} اليوم
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart stat-icon text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                الطلبات المكتملة
                            </div>
                            <div class="h3 mb-0 font-weight-bold text-gray-800">{{ $stats['completed_orders'] }}</div>
                            <div class="mt-2">
                                <small class="text-success">
                                    <i class="fas fa-percentage"></i> 
                                    {{ $stats['total_orders'] > 0 ? round(($stats['completed_orders'] / $stats['total_orders']) * 100, 1) : 0 }}%
                                </small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double stat-icon text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Secondary Statistics --}}
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card border-left-danger shadow h-100">
                <div class="card-body text-center">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">طلبات معلقة</div>
                    <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_orders'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body text-center">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">قيد التنفيذ</div>
                    <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $stats['in_progress_orders'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card border-left-danger shadow h-100">
                <div class="card-body text-center">
                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">ملغية</div>
                    <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $stats['cancelled_orders'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card border-left-info shadow h-100">
                <div class="card-body text-center">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">طلبات اليوم</div>
                    <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $stats['today_orders'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card border-left-success shadow h-100">
                <div class="card-body text-center">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">طلبات الشهر</div>
                    <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $stats['monthly_orders'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6 mb-3">
            <div class="card border-left-secondary shadow h-100">
                <div class="card-body text-center">
                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">مشرفين</div>
                    <div class="h4 mb-0 font-weight-bold text-gray-800">{{ $stats['supervisor_drivers'] }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts and Top Drivers --}}
    <div class="row mb-4">
        {{-- Monthly Orders Chart --}}
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line mr-2"></i>
                        إحصائيات الطلبات الشهرية
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthlyOrdersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Drivers --}}
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-trophy mr-2"></i>
                        أفضل السواقين
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($topDrivers as $index => $driver)
                        <div class="driver-card p-3 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="top-driver-badge 
                                        {{ $index === 0 ? 'gradient-warning' : ($index === 1 ? 'gradient-info' : ($index === 2 ? 'gradient-success' : 'bg-secondary')) }} 
                                        text-white">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 font-weight-bold">{{ $driver->user->name }}</h6>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= round($driver->rating) ? 'text-warning' : 'text-muted' }}" style="font-size: 0.8rem;"></i>
                                        @endfor
                                        <span class="text-muted small">{{ number_format($driver->rating, 1) }}</span>
                                    </div>
                                    <small class="text-muted">
                                        <i class="fas fa-shipping-fast"></i> {{ $driver->total_deliveries }} توصيلة
                                    </small>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge badge-success">{{ $driver->current_orders_count }} نشط</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <p>لا توجد بيانات</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Orders and Drivers Needing Attention --}}
    <div class="row">
        {{-- Recent Orders --}}
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list mr-2"></i>
                        الطلبات الأخيرة
                    </h6>
                    <a href="{{ route('admin.driver.orders') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye"></i> عرض الكل
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
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
                                @forelse($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <strong class="text-primary">#{{ $order->order->id }}</strong>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="font-weight-bold">{{ $order->order->user->name }}</div>
                                                <small class="text-muted">{{ $order->order->user->email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="font-weight-bold">{{ $order->driver->user->name }}</div>
                                                <small class="text-muted">{{ $order->driver->vehicle_type }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $statusConfig = [
                                                    'assigned' => ['class' => 'warning', 'label' => 'مُسند', 'icon' => 'clock'],
                                                    'accepted' => ['class' => 'info', 'label' => 'مقبول', 'icon' => 'check'],
                                                    'picked_up' => ['class' => 'primary', 'label' => 'تم الاستلام', 'icon' => 'box'],
                                                    'delivered' => ['class' => 'success', 'label' => 'تم التسليم', 'icon' => 'check-circle'],
                                                    'cancelled' => ['class' => 'danger', 'label' => 'ملغي', 'icon' => 'times-circle']
                                                ];
                                                $currentStatus = $statusConfig[$order->status] ?? ['class' => 'secondary', 'label' => $order->status, 'icon' => 'question'];
                                            @endphp
                                            <span class="status-badge badge-{{ $currentStatus['class'] }}">
                                                <span class="status-dot bg-{{ $currentStatus['class'] }}"></span>
                                                <i class="fas fa-{{ $currentStatus['icon'] }}"></i>
                                                {{ $currentStatus['label'] }}
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
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                            <p class="text-muted">لا توجد طلبات</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Drivers Needing Attention --}}
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        سواقين يحتاجون انتباه
                    </h6>
                </div>
                <div class="card-body">
                    @forelse($driversNeedingAttention as $driver)
                        <div class="driver-card p-3 mb-3 bg-light">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 font-weight-bold">{{ $driver->user->name }}</h6>
                                    <small class="text-muted d-block">
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
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p>جميع السواقين يعملون بشكل طبيعي</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Orders Chart
    const monthlyCtx = document.getElementById('monthlyOrdersChart');
    if (monthlyCtx) {
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
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'الطلبات المكتملة',
                        data: monthlyData.map(item => item.completed_count),
                        borderColor: '#1cc88a',
                        backgroundColor: 'rgba(28, 200, 138, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'الطلبات الملغية',
                        data: monthlyData.map(item => item.cancelled_count),
                        borderColor: '#e74a3b',
                        backgroundColor: 'rgba(231, 74, 59, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    }
</script>
@endpush
