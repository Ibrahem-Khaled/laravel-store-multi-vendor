@extends('layouts.app')

@section('content')
    <style>
        .badge {
            color: #fff;
        }
    </style>
    <div class="container-fluid p-4">
        @auth
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">مرحباً, {{ Auth::user()->name }}!</h1>
                <p class="lead">هنا ملخص نشاطك.</p>
            </div>

            {{-- ================================================================= --}}
            {{--                    لوحة تحكم الأدمن (Admin)                       --}}
            {{-- ================================================================= --}}
            @if (Auth::user()->role == 'admin')
                <div class="row">
                    {{-- إجمالي الأرباح --}}
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            إجمالي الأرباح (افتراضي)</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            ${{ number_format($data['total_profit'], 2) }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300 card-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- إجمالي المنتجات --}}
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            إجمالي المنتجات</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['total_products'] }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-box-open fa-2x text-gray-300 card-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- إجمالي التجار --}}
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">إجمالي التجار</div>
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $data['total_traders'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-store fa-2x text-gray-300 card-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- إجمالي المستخدمين --}}
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            إجمالي المستخدمين</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['total_users'] }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300 card-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    {{-- أحدث المنتجات المضافة --}}
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header">أحدث المنتجات</div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    @forelse($data['recent_products'] as $product)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>{{ $product->name }} <small class="text-muted">
                                                    ({{ $product->brand->name ?? 'N/A' }})
                                                </small></span>
                                            <span
                                                class="badge bg-primary rounded-pill">{{ $product->created_at->diffForHumans() }}</span>
                                        </li>
                                    @empty
                                        <li class="list-group-item">لا توجد منتجات بعد.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                    {{-- أحدث المستخدمين --}}
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header">أحدث المستخدمين</div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    @forelse($data['recent_users'] as $user)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>{{ $user->name }} <small class="text-muted">
                                                    ({{ $user->role }})
                                                </small></span>
                                            <span
                                                class="badge bg-secondary rounded-pill">{{ $user->created_at->diffForHumans() }}</span>
                                        </li>
                                    @empty
                                        <li class="list-group-item">لا يوجد مستخدمون جدد.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================================================================= --}}
                {{--                     لوحة تحكم التاجر (Trader)                     --}}
                {{-- ================================================================= --}}
            @elseif(Auth::user()->role == 'trader')
                <div class="row">
                    {{-- أرباحي --}}
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            أرباحي (افتراضي)</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            ${{ number_format($data['my_profit'], 2) }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300 card-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- منتجاتي --}}
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            عدد منتجاتي</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $data['my_products_count'] }}
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-box-open fa-2x text-gray-300 card-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- مراجعاتي --}}
                    <div class="col-xl-4 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">إجمالي المراجعات
                                        </div>
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                            {{ $data['my_reviews_count'] }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-star fa-2x text-gray-300 card-icon"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    {{-- أحدث منتجاتي --}}
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header">أحدث منتجاتي</div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    @forelse($data['my_recent_products'] as $product)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>{{ $product->name }}</span>
                                            <div>
                                                <span
                                                    class="badge {{ $product->is_approved ? 'bg-success' : 'bg-warning' }}">{{ $product->is_approved ? 'مقبول' : 'قيد المراجعة' }}</span>
                                                <span
                                                    class="badge bg-primary rounded-pill">{{ $product->created_at->diffForHumans() }}</span>
                                            </div>
                                        </li>
                                    @empty
                                        <li class="list-group-item">لم تقم بإضافة منتجات بعد.</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endauth
    </div>
@endsection
