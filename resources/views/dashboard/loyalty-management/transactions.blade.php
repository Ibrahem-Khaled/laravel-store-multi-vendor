@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/loyalty-management.css') }}">
    <style>
        .transaction-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-right: 4px solid #667eea;
            transition: transform 0.3s ease;
        }

        .transaction-card:hover {
            transform: translateY(-2px);
        }

        .transaction-type {
            font-weight: bold;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85em;
            text-transform: uppercase;
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

        .transaction-source {
            background: #e9ecef;
            color: #495057;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
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

        .user-info {
            display: flex;
            align-items: center;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 10px;
        }

        .points-display {
            font-size: 1.2em;
            font-weight: bold;
            color: #667eea;
        }

        .amount-display {
            font-size: 0.9em;
            color: #666;
        }

        .metadata-display {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 10px;
            margin-top: 10px;
            font-size: 0.85em;
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
                <h1 class="h3 mb-0 text-gray-800">معاملات نقاط الولاء</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('loyalty-management.dashboard') }}">نقاط الولاء</a></li>
                        <li class="breadcrumb-item active" aria-current="page">المعاملات</li>
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
                        <div class="number">{{ $transactions->total() }}</div>
                        <div class="label">إجمالي المعاملات</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="number">{{ $transactions->where('type', 'earned')->sum('points') }}</div>
                        <div class="label">النقاط المكتسبة</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="number">{{ $transactions->where('type', 'used')->sum('points') }}</div>
                        <div class="label">النقاط المستخدمة</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-item">
                        <div class="number">{{ number_format($transactions->sum('amount'), 2) }} ريال</div>
                        <div class="label">إجمالي المبلغ</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- فلاتر البحث --}}
        <div class="filter-card">
            <form action="{{ route('loyalty-management.transactions') }}" method="GET">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <label for="search">البحث</label>
                        <input type="text" name="search" id="search" class="form-control"
                               placeholder="ابحث بالوصف أو اسم المستخدم أو رقم الطلب..."
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label for="type">نوع المعاملة</label>
                        <select name="type" id="type" class="form-control">
                            <option value="">جميع الأنواع</option>
                            <option value="earned" {{ request('type') == 'earned' ? 'selected' : '' }}>مكتسبة</option>
                            <option value="used" {{ request('type') == 'used' ? 'selected' : '' }}>مستخدمة</option>
                            <option value="expired" {{ request('type') == 'expired' ? 'selected' : '' }}>منتهية</option>
                            <option value="refunded" {{ request('type') == 'refunded' ? 'selected' : '' }}>مستردة</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label for="source">مصدر المعاملة</label>
                        <select name="source" id="source" class="form-control">
                            <option value="">جميع المصادر</option>
                            <option value="order" {{ request('source') == 'order' ? 'selected' : '' }}>طلب</option>
                            <option value="manual" {{ request('source') == 'manual' ? 'selected' : '' }}>يدوي</option>
                            <option value="refund" {{ request('source') == 'refund' ? 'selected' : '' }}>استرداد</option>
                            <option value="expiry" {{ request('source') == 'expiry' ? 'selected' : '' }}>انتهاء صلاحية</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label for="date_from">من تاريخ</label>
                        <input type="date" name="date_from" id="date_from" class="form-control"
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-lg-2 col-md-6 mb-3">
                        <label for="date_to">إلى تاريخ</label>
                        <input type="date" name="date_to" id="date_to" class="form-control"
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-lg-1 col-md-6 mb-3">
                        <label for="sort">ترتيب</label>
                        <select name="sort" id="sort" class="form-control">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>التاريخ</option>
                            <option value="points" {{ request('sort') == 'points' ? 'selected' : '' }}>النقاط</option>
                            <option value="amount" {{ request('sort') == 'amount' ? 'selected' : '' }}>المبلغ</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-1 col-md-6 mb-3">
                        <label for="direction">الاتجاه</label>
                        <select name="direction" id="direction" class="form-control">
                            <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>تنازلي</option>
                            <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>تصاعدي</option>
                        </select>
                    </div>
                    <div class="col-11">
                        <label>&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> بحث
                            </button>
                            <a href="{{ route('loyalty-management.transactions') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> مسح الفلاتر
                            </a>
                            <a href="{{ route('loyalty-management.export', request()->query()) }}" class="btn btn-success">
                                <i class="fas fa-download"></i> تصدير تقرير
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- قائمة المعاملات --}}
        <div class="row">
            @forelse($transactions as $transaction)
                <div class="col-12 mb-3">
                    <div class="transaction-card">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <div class="user-info">
                                    <img src="{{ $transaction->user->avatar ? asset('storage/' . $transaction->user->avatar) : asset('img/default-avatar.png') }}"
                                         alt="{{ $transaction->user->name }}" class="user-avatar">
                                    <div>
                                        <div class="font-weight-bold">{{ $transaction->user->name }}</div>
                                        <small class="text-muted">{{ $transaction->user->email ?? $transaction->user->phone }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="font-weight-bold mb-1">{{ $transaction->description }}</div>
                                <div class="transaction-source">{{ ucfirst($transaction->source) }}</div>
                                @if($transaction->order)
                                    <small class="text-muted">الطلب: {{ $transaction->order->order_number }}</small>
                                @endif
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="transaction-type transaction-{{ $transaction->type }}">
                                    {{ ucfirst($transaction->type) }}
                                </div>
                                <div class="points-display mt-1">
                                    {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }} نقطة
                                </div>
                                @if($transaction->amount)
                                    <div class="amount-display">{{ number_format($transaction->amount, 2) }} ريال</div>
                                @endif
                            </div>
                            <div class="col-md-2">
                                <div class="text-muted">
                                    <i class="fas fa-clock"></i> {{ $transaction->created_at->format('Y-m-d H:i') }}
                                </div>
                                <small class="text-muted">{{ $transaction->created_at->diffForHumans() }}</small>
                                @if($transaction->expires_at)
                                    <div class="text-warning mt-1">
                                        <small><i class="fas fa-calendar-times"></i> ينتهي: {{ $transaction->expires_at->format('Y-m-d') }}</small>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-2">
                                @if($transaction->processedBy)
                                    <div class="text-muted">
                                        <i class="fas fa-user-cog"></i> {{ $transaction->processedBy->name }}
                                    </div>
                                @endif
                                @if($transaction->metadata)
                                    <button class="btn btn-sm btn-outline-info" type="button" data-toggle="collapse"
                                            data-target="#metadata-{{ $transaction->id }}">
                                        <i class="fas fa-info-circle"></i> تفاصيل
                                    </button>
                                @endif
                            </div>
                            <div class="col-md-1">
                                @if($transaction->source === 'manual')
                                    <form action="{{ route('loyalty-management.delete-transaction', $transaction->id) }}"
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('هل أنت متأكد من حذف هذه المعاملة؟')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        {{-- تفاصيل إضافية --}}
                        @if($transaction->metadata)
                            <div class="collapse" id="metadata-{{ $transaction->id }}">
                                <div class="metadata-display">
                                    <h6>تفاصيل إضافية:</h6>
                                    <pre class="mb-0">{{ json_encode($transaction->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-exchange-alt fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد معاملات</h5>
                        <p class="text-muted">لم يتم العثور على معاملات تطابق معايير البحث</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($transactions->hasPages())
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $transactions->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/loyalty-management.js') }}"></script>
@endpush
