@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/loyalty-management.css') }}">
    <style>
        .user-profile-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-right: 5px solid #667eea;
        }

        .user-avatar-large {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #667eea;
        }

        .points-summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
        }

        .points-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .points-item {
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
        }

        .points-item .number {
            font-size: 2em;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .points-item .label {
            font-size: 0.9em;
            opacity: 0.9;
        }

        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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

        .action-buttons {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid" dir="rtl">

        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">تفاصيل مستخدم نقاط الولاء</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loyalty-management.dashboard') }}">نقاط الولاء</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loyalty-management.users') }}">المستخدمين</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- معلومات المستخدم --}}
        <div class="user-profile-card">
            <div class="row align-items-center">
                <div class="col-md-3 text-center">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('img/default-avatar.png') }}"
                         alt="{{ $user->name }}" class="user-avatar-large mb-3">
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-0">{{ $user->email ?? $user->phone }}</p>
                    <span class="badge badge-info">{{ ucfirst($user->role) }}</span>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-id-card"></i> معلومات المستخدم</h6>
                            <ul class="list-unstyled">
                                <li><strong>الاسم:</strong> {{ $user->name }}</li>
                                <li><strong>اسم المستخدم:</strong> {{ $user->username }}</li>
                                <li><strong>البريد الإلكتروني:</strong> {{ $user->email ?? 'غير محدد' }}</li>
                                <li><strong>الهاتف:</strong> {{ $user->phone ?? 'غير محدد' }}</li>
                                <li><strong>الجنس:</strong> {{ $user->gender ?? 'غير محدد' }}</li>
                                <li><strong>الحالة:</strong>
                                    <span class="badge badge-{{ $user->status == 'active' ? 'success' : 'warning' }}">
                                        {{ $user->status }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-calendar"></i> معلومات الحساب</h6>
                            <ul class="list-unstyled">
                                <li><strong>تاريخ الإنشاء:</strong> {{ $user->created_at->format('Y-m-d H:i') }}</li>
                                <li><strong>آخر تحديث:</strong> {{ $user->updated_at->format('Y-m-d H:i') }}</li>
                                <li><strong>معرف المستخدم:</strong> {{ $user->uuid }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ملخص النقاط --}}
        <div class="points-summary">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4><i class="fas fa-star"></i> ملخص نقاط الولاء</h4>
                    <p class="mb-0">نظرة شاملة على نقاط الولاء للمستخدم</p>
                </div>
                <div class="col-md-4 text-right">
                    <div class="points-item">
                        <div class="number">{{ number_format($user->loyaltyPoints->available_points) }}</div>
                        <div class="label">نقطة متاحة</div>
                    </div>
                </div>
            </div>

            <div class="points-grid">
                <div class="points-item">
                    <div class="number">{{ number_format($user->loyaltyPoints->total_points) }}</div>
                    <div class="label">إجمالي النقاط</div>
                </div>
                <div class="points-item">
                    <div class="number">{{ number_format($user->loyaltyPoints->used_points) }}</div>
                    <div class="label">النقاط المستخدمة</div>
                </div>
                <div class="points-item">
                    <div class="number">{{ number_format($user->loyaltyPoints->expired_points) }}</div>
                    <div class="label">النقاط المنتهية</div>
                </div>
                <div class="points-item">
                    <div class="number">{{ number_format($user->loyaltyPoints->platform_contribution, 2) }}</div>
                    <div class="label">مساهمة المنصة (ريال)</div>
                </div>
                <div class="points-item">
                    <div class="number">{{ number_format($user->loyaltyPoints->customer_contribution, 2) }}</div>
                    <div class="label">مساهمة العميل (ريال)</div>
                </div>
            </div>
        </div>

        {{-- أزرار الإجراءات --}}
        <div class="action-buttons">
            <h6 class="mb-3">إجراءات سريعة</h6>
            <div class="btn-group" role="group">
                <button class="btn btn-success" onclick="addPointsToUser({{ $user->id }}, '{{ $user->name }}')">
                    <i class="fas fa-plus"></i> إضافة نقاط
                </button>
                <a href="{{ route('loyalty-management.transactions', ['search' => $user->name]) }}" class="btn btn-info">
                    <i class="fas fa-history"></i> عرض المعاملات
                </a>
                <a href="{{ route('loyalty-management.users') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right"></i> العودة للقائمة
                </a>
            </div>
        </div>

        <div class="row">
            {{-- إحصائيات المستخدم --}}
            <div class="col-lg-6 mb-4">
                <div class="stats-card">
                    <h6 class="mb-3"><i class="fas fa-chart-bar"></i> إحصائيات المستخدم</h6>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="h4 text-primary">{{ number_format($userStats['total_transactions']) }}</div>
                                <small class="text-muted">إجمالي المعاملات</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="h4 text-success">{{ number_format($userStats['earned_transactions']) }}</div>
                                <small class="text-muted">معاملات الكسب</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="h4 text-danger">{{ number_format($userStats['used_transactions']) }}</div>
                                <small class="text-muted">معاملات الاستخدام</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="h4 text-warning">{{ number_format($userStats['expired_transactions']) }}</div>
                                <small class="text-muted">معاملات الانتهاء</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="h4 text-success">{{ number_format($userStats['total_amount_earned'], 2) }} ريال</div>
                                <small class="text-muted">إجمالي المبلغ المكتسب</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-center">
                                <div class="h4 text-danger">{{ number_format($userStats['total_amount_used'], 2) }} ريال</div>
                                <small class="text-muted">إجمالي المبلغ المستخدم</small>
                            </div>
                        </div>
                    </div>
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
                                    <div class="font-weight-bold">{{ $transaction->description }}</div>
                                    <small class="text-muted">
                                        @if($transaction->order)
                                            الطلب: {{ $transaction->order->order_number }}
                                        @endif
                                        @if($transaction->processedBy)
                                            | المعالج: {{ $transaction->processedBy->name }}
                                        @endif
                                    </small>
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
                                    @if($transaction->amount)
                                        <small class="text-muted">{{ number_format($transaction->amount, 2) }} ريال</small>
                                    @endif
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

        {{-- الإحصائيات الشهرية --}}
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyStats as $stat)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $stat->month)->format('Y-m') }}</td>
                                        <td>{{ number_format($stat->transactions_count) }}</td>
                                        <td class="text-success">{{ number_format($stat->points_earned) }}</td>
                                        <td class="text-danger">{{ number_format($stat->points_used) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
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
                            <input type="hidden" name="user_id" id="modal_user_id" value="{{ $user->id }}">
                            <input type="text" id="modal_user_name" class="form-control" value="{{ $user->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="points">عدد النقاط</label>
                            <input type="number" name="points" id="modal_points" class="form-control" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="platform_contribution">مساهمة المنصة (ريال)</label>
                            <input type="number" name="platform_contribution" id="modal_platform_contribution" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_contribution">مساهمة العميل (ريال)</label>
                            <input type="number" name="customer_contribution" id="modal_customer_contribution" class="form-control" step="0.01" min="0" required>
                        </div>
                        <div class="form-group">
                            <label for="description">وصف المعاملة</label>
                            <textarea name="description" id="modal_description" class="form-control" rows="3" required></textarea>
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
@endpush
