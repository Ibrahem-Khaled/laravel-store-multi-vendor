@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/loyalty-management.css') }}">
    <style>
        .loyalty-user-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-right: 4px solid #667eea;
            transition: transform 0.3s ease;
        }

        .loyalty-user-card:hover {
            transform: translateY(-2px);
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .points-display {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }

        .points-breakdown {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
        }

        .points-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .points-item:last-child {
            margin-bottom: 0;
            font-weight: bold;
            border-top: 1px solid #dee2e6;
            padding-top: 5px;
        }

        .filter-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .stats-row {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .stat-item {
            text-align: center;
            padding: 15px;
        }

        .stat-item .number {
            font-size: 2em;
            font-weight: bold;
            color: #667eea;
        }

        .stat-item .label {
            color: #666;
            font-size: 0.9em;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid" dir="rtl">

        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة مستخدمي نقاط الولاء</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loyalty-management.dashboard') }}">نقاط الولاء</a></li>
                        <li class="breadcrumb-item active" aria-current="page">المستخدمين</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات سريعة --}}
        <div class="stats-row">
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="number">{{ $users->total() }}</div>
                        <div class="label">إجمالي المستخدمين</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="number">{{ $users->sum('total_points') }}</div>
                        <div class="label">إجمالي النقاط</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="number">{{ $users->sum('used_points') }}</div>
                        <div class="label">النقاط المستخدمة</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="number">{{ $users->sum('total_points') - $users->sum('used_points') - $users->sum('expired_points') }}</div>
                        <div class="label">النقاط المتاحة</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- فلاتر البحث --}}
        <div class="filter-card">
            <form action="{{ route('loyalty-management.users') }}" method="GET">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <label for="search">البحث</label>
                        <input type="text" name="search" id="search" class="form-control"
                               placeholder="ابحث بالاسم أو البريد أو الهاتف..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label for="min_points">أقل نقاط</label>
                        <input type="number" name="min_points" id="min_points" class="form-control"
                               placeholder="0" value="{{ request('min_points') }}">
                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label for="max_points">أكثر نقاط</label>
                        <input type="number" name="max_points" id="max_points" class="form-control"
                               placeholder="1000" value="{{ request('max_points') }}">
                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label for="sort">ترتيب حسب</label>
                        <select name="sort" id="sort" class="form-control">
                            <option value="total_points" {{ request('sort') == 'total_points' ? 'selected' : '' }}>إجمالي النقاط</option>
                            <option value="used_points" {{ request('sort') == 'used_points' ? 'selected' : '' }}>النقاط المستخدمة</option>
                            <option value="last_earned_at" {{ request('sort') == 'last_earned_at' ? 'selected' : '' }}>آخر كسب</option>
                            <option value="last_used_at" {{ request('sort') == 'last_used_at' ? 'selected' : '' }}>آخر استخدام</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label for="direction">الاتجاه</label>
                        <select name="direction" id="direction" class="form-control">
                            <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>تنازلي</option>
                            <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>تصاعدي</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> بحث
                        </button>
                        <a href="{{ route('loyalty-management.users') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> مسح الفلاتر
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- قائمة المستخدمين --}}
        <div class="row">
            @forelse($users as $loyaltyPoint)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="loyalty-user-card">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <img src="{{ $loyaltyPoint->user->avatar ? asset('storage/' . $loyaltyPoint->user->avatar) : asset('img/default-avatar.png') }}"
                                     alt="{{ $loyaltyPoint->user->name }}" class="user-avatar">
                            </div>
                            <div class="col-6">
                                <h6 class="mb-1">{{ $loyaltyPoint->user->name }}</h6>
                                <small class="text-muted d-block">{{ $loyaltyPoint->user->email ?? $loyaltyPoint->user->phone }}</small>
                                <span class="badge badge-info">{{ ucfirst($loyaltyPoint->user->role) }}</span>
                            </div>
                            <div class="col-3">
                                <div class="points-display">
                                    {{ number_format($loyaltyPoint->total_points) }}
                                    <small class="d-block">نقطة</small>
                                </div>
                            </div>
                        </div>

                        <div class="points-breakdown">
                            <div class="points-item">
                                <span>إجمالي النقاط:</span>
                                <span>{{ number_format($loyaltyPoint->total_points) }}</span>
                            </div>
                            <div class="points-item">
                                <span>النقاط المستخدمة:</span>
                                <span class="text-danger">{{ number_format($loyaltyPoint->used_points) }}</span>
                            </div>
                            <div class="points-item">
                                <span>النقاط المنتهية:</span>
                                <span class="text-warning">{{ number_format($loyaltyPoint->expired_points) }}</span>
                            </div>
                            <div class="points-item">
                                <span>النقاط المتاحة:</span>
                                <span class="text-success">{{ number_format($loyaltyPoint->available_points) }}</span>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-6">
                                <small class="text-muted">
                                    <i class="fas fa-building"></i> المنصة: {{ number_format($loyaltyPoint->platform_contribution, 2) }} ريال
                                </small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">
                                    <i class="fas fa-user"></i> العميل: {{ number_format($loyaltyPoint->customer_contribution, 2) }} ريال
                                </small>
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-6">
                                @if($loyaltyPoint->last_earned_at)
                                    <small class="text-muted">
                                        <i class="fas fa-plus-circle"></i> آخر كسب: {{ $loyaltyPoint->last_earned_at->diffForHumans() }}
                                    </small>
                                @endif
                            </div>
                            <div class="col-6">
                                @if($loyaltyPoint->last_used_at)
                                    <small class="text-muted">
                                        <i class="fas fa-minus-circle"></i> آخر استخدام: {{ $loyaltyPoint->last_used_at->diffForHumans() }}
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('loyalty-management.user-details', $loyaltyPoint->user->id) }}"
                                       class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye"></i> تفاصيل
                                    </a>
                                    <button class="btn btn-outline-success btn-sm"
                                            onclick="addPointsToUser({{ $loyaltyPoint->user->id }}, '{{ $loyaltyPoint->user->name }}')">
                                        <i class="fas fa-plus"></i> إضافة نقاط
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا يوجد مستخدمون مع نقاط ولاء</h5>
                        <p class="text-muted">لم يتم العثور على مستخدمين يطابقون معايير البحث</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $users->appends(request()->query())->links() }}
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
                            <input type="hidden" name="user_id" id="modal_user_id">
                            <input type="text" id="modal_user_name" class="form-control" readonly>
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
