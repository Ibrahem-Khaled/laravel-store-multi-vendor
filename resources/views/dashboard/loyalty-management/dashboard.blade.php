@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/loyalty-management.css') }}">
    <style>
        /* تحسينات شكلية لنظام نقاط الولاء */
        .loyalty-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .loyalty-card h5 {
            color: white;
            font-weight: 600;
        }

        .points-badge {
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            padding: 8px 16px;
            font-weight: bold;
            font-size: 1.1em;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-right: 4px solid #667eea;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card .icon {
            font-size: 2.5em;
            color: #667eea;
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 2em;
            font-weight: bold;
            color: #333;
        }

        .stat-card .label {
            color: #666;
            font-size: 0.9em;
        }

        .transaction-item {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border-right: 3px solid #667eea;
        }

        .transaction-type {
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
        }

        .transaction-earned {
            background: #d4edda;
            color: #155724;
        }

        .transaction-used {
            background: #f8d7da;
            color: #721c24;
        }

        .transaction-expired {
            background: #fff3cd;
            color: #856404;
        }

        .transaction-refunded {
            background: #d1ecf1;
            color: #0c5460;
        }

        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .top-user-item {
            display: flex;
            align-items: center;
            padding: 10px;
            background: white;
            border-radius: 8px;
            margin-bottom: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .top-user-item .rank {
            background: #667eea;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-left: 10px;
        }

        .top-user-item .user-info {
            flex: 1;
        }

        .top-user-item .points {
            font-weight: bold;
            color: #667eea;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid" dir="rtl">

        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة نقاط الولاء</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active" aria-current="page">نقاط الولاء</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- بطاقة نظرة عامة --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="loyalty-card">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5><i class="fas fa-star"></i> نظام نقاط الولاء</h5>
                            <p class="mb-0">إدارة شاملة لنظام نقاط الولاء وتتبع جميع المعاملات</p>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="points-badge">
                                <i class="fas fa-coins"></i> {{ number_format($stats['active_points']) }} نقطة نشطة
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- إحصائيات عامة --}}
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="number">{{ number_format($stats['total_users_with_points']) }}</div>
                    <div class="label">المستخدمون مع النقاط</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="icon">
                        <i class="fas fa-gift"></i>
                    </div>
                    <div class="number">{{ number_format($stats['total_points_distributed']) }}</div>
                    <div class="label">إجمالي النقاط الموزعة</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="number">{{ number_format($stats['total_points_used']) }}</div>
                    <div class="label">النقاط المستخدمة</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="number">{{ number_format($stats['total_points_expired']) }}</div>
                    <div class="label">النقاط المنتهية الصلاحية</div>
                </div>
            </div>
        </div>

        {{-- إحصائيات التكلفة --}}
        <div class="row mb-4">
            <div class="col-lg-6 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="number">{{ number_format($stats['total_platform_contribution'], 2) }} ريال</div>
                    <div class="label">مساهمة المنصة</div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div class="number">{{ number_format($stats['total_customer_contribution'], 2) }} ريال</div>
                    <div class="label">مساهمة العملاء</div>
                </div>
            </div>
        </div>

        {{-- إحصائيات المعاملات --}}
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="number">{{ number_format($transactionStats['total_transactions']) }}</div>
                    <div class="label">إجمالي المعاملات</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="number">{{ number_format($transactionStats['earned_transactions']) }}</div>
                    <div class="label">معاملات الكسب</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="icon">
                        <i class="fas fa-minus-circle"></i>
                    </div>
                    <div class="number">{{ number_format($transactionStats['used_transactions']) }}</div>
                    <div class="label">معاملات الاستخدام</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="number">{{ number_format($transactionStats['expired_transactions']) }}</div>
                    <div class="label">معاملات الانتهاء</div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- أفضل المستخدمين --}}
            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <h6 class="mb-3"><i class="fas fa-trophy"></i> أفضل المستخدمين بالنقاط</h6>
                    @forelse($topUsers as $index => $loyaltyPoint)
                        <div class="top-user-item">
                            <div class="rank">{{ $index + 1 }}</div>
                            <div class="user-info">
                                <div class="font-weight-bold">{{ $loyaltyPoint->user->name }}</div>
                                <small class="text-muted">{{ $loyaltyPoint->user->email ?? $loyaltyPoint->user->phone }}</small>
                            </div>
                            <div class="points">{{ number_format($loyaltyPoint->total_points) }} نقطة</div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <p>لا يوجد مستخدمون مع نقاط ولاء</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- المعاملات الأخيرة --}}
            <div class="col-lg-6 mb-4">
                <div class="chart-container">
                    <h6 class="mb-3"><i class="fas fa-history"></i> المعاملات الأخيرة</h6>
                    @forelse($recentTransactions as $transaction)
                        <div class="transaction-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="font-weight-bold">{{ $transaction->user->name }}</div>
                                    <small class="text-muted">{{ $transaction->description }}</small>
                                    <div class="mt-1">
                                        <small class="text-muted">
                                            <i class="fas fa-clock"></i> {{ $transaction->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="transaction-type transaction-{{ $transaction->type }}">
                                        {{ ucfirst($transaction->type) }}
                                    </div>
                                    <div class="font-weight-bold mt-1">
                                        {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }} نقطة
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-exchange-alt fa-3x mb-3"></i>
                            <p>لا توجد معاملات حديثة</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- إحصائيات شهرية --}}
        @if($monthlyStats->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="chart-container">
                    <h6 class="mb-3"><i class="fas fa-chart-line"></i> الإحصائيات الشهرية (آخر 12 شهر)</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>الشهر</th>
                                    <th>عدد المعاملات</th>
                                    <th>النقاط المكتسبة</th>
                                    <th>النقاط المستخدمة</th>
                                    <th>المبلغ المكتسب</th>
                                    <th>المبلغ المستخدم</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyStats as $stat)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $stat->month)->format('Y-m') }}</td>
                                        <td>{{ number_format($stat->transactions_count) }}</td>
                                        <td class="text-success">{{ number_format($stat->points_earned) }}</td>
                                        <td class="text-danger">{{ number_format($stat->points_used) }}</td>
                                        <td class="text-success">{{ number_format($stat->amount_earned, 2) }} ريال</td>
                                        <td class="text-danger">{{ number_format($stat->amount_used, 2) }} ريال</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- أزرار التنقل السريع --}}
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h6 class="mb-3">إجراءات سريعة</h6>
                        <div class="btn-group" role="group">
                            <a href="{{ route('loyalty-management.users') }}" class="btn btn-primary">
                                <i class="fas fa-users"></i> إدارة المستخدمين
                            </a>
                            <a href="{{ route('loyalty-management.transactions') }}" class="btn btn-info">
                                <i class="fas fa-exchange-alt"></i> المعاملات
                            </a>
                            <button class="btn btn-success" data-toggle="modal" data-target="#addPointsModal">
                                <i class="fas fa-plus"></i> إضافة نقاط
                            </button>
                            <a href="{{ route('loyalty-management.export') }}" class="btn btn-warning">
                                <i class="fas fa-download"></i> تصدير تقرير
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal إضافة نقاط --}}
    <div class="modal fade" id="addPointsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">إضافة نقاط ولاء</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('loyalty-management.add-points') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="user_id">المستخدم</label>
                            <select name="user_id" id="user_id" class="form-control" required>
                                <option value="">اختر المستخدم</option>
                                @foreach(\App\Models\User::where('status', 'active')->get() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email ?? $user->phone }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="points">عدد النقاط</label>
                            <input type="number" name="points" id="points" class="form-control" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="platform_contribution">مساهمة المنصة (ريال)</label>
                            <input type="number" name="platform_contribution" id="platform_contribution" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_contribution">مساهمة العميل (ريال)</label>
                            <input type="number" name="customer_contribution" id="customer_contribution" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="description">وصف المعاملة</label>
                            <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">إضافة النقاط</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/loyalty-management.js') }}"></script>
    <script>
        // تمرير البيانات للـ JavaScript
        window.monthlyStats = @json($monthlyStats);
        window.pointsDistribution = {
            available: {{ $stats['active_points'] }},
            used: {{ $stats['total_points_used'] }},
            expired: {{ $stats['total_points_expired'] }}
        };
    </script>
@endpush
