@extends('layouts.app')

@push('styles')
<style>
    .user-profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 10px;
        margin-bottom: 2rem;
    }

    .user-avatar-large {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .info-card {
        border-left: 4px solid;
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .stat-badge {
        font-size: 1.1rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-user mr-2"></i>
                        تفاصيل المستخدم
                    </h1>
                    <nav aria-label="breadcrumb" class="mt-2">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('users.index') }}">المستخدمين</a>
                            </li>
                            <li class="breadcrumb-item active">{{ $user->name }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right"></i> رجوع
                    </a>
                    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> تعديل
                    </a>
                </div>
            </div>
        </div>
    </div>

    @include('components.alerts')

    {{-- Profile Header --}}
    <div class="user-profile-header">
        <div class="row align-items-center">
            <div class="col-auto">
                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('img/default-avatar.png') }}"
                     alt="{{ $user->name }}"
                     class="user-avatar-large"
                     onerror="this.onerror=null; this.src='{{ asset('img/default-avatar.png') }}';">
            </div>
            <div class="col">
                <h2 class="mb-2">{{ $user->name }}</h2>
                <div class="d-flex flex-wrap gap-3">
                    <div>
                        <i class="fas fa-at"></i> {{ $user->username }}
                    </div>
                    @if($user->email)
                        <div>
                            <i class="fas fa-envelope"></i> {{ $user->email }}
                        </div>
                    @endif
                    @if($user->phone)
                        <div>
                            <i class="fas fa-phone"></i> {{ $user->phone }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-auto text-right">
                @php
                    $statusConfig = [
                        'pending' => ['class' => 'warning', 'label' => 'قيد الاعتماد'],
                        'active' => ['class' => 'success', 'label' => 'نشط'],
                        'inactive' => ['class' => 'secondary', 'label' => 'غير نشط'],
                        'banned' => ['class' => 'danger', 'label' => 'محظور']
                    ];
                    $currentStatus = $statusConfig[$user->status] ?? ['class' => 'light', 'label' => $user->status];
                @endphp
                <span class="badge stat-badge badge-{{ $currentStatus['class'] }}">
                    {{ $currentStatus['label'] }}
                </span>
                @if($user->is_verified)
                    <span class="badge stat-badge badge-success mt-2 d-block">
                        <i class="fas fa-check-circle"></i> موثق
                    </span>
                @endif
            </div>
        </div>
    </div>

    {{-- Statistics --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card info-card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        الرصيد (Coins)
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ number_format($user->coins ?? 0) }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card info-card border-left-info shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                        المنتجات
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $userStats['total_products'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card info-card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        العلامات التجارية
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $userStats['total_brands'] }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card info-card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        تاريخ التسجيل
                    </div>
                    <div class="h6 mb-0 font-weight-bold text-gray-800">
                        {{ $user->created_at->format('Y-m-d') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- User Information --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle mr-2"></i>
                        المعلومات الشخصية
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">الاسم الكامل:</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>اسم المستخدم:</th>
                            <td>{{ $user->username }}</td>
                        </tr>
                        <tr>
                            <th>UUID:</th>
                            <td><code>{{ $user->uuid }}</code></td>
                        </tr>
                        @if($user->email)
                            <tr>
                                <th>البريد الإلكتروني:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                        @endif
                        @if($user->phone)
                            <tr>
                                <th>رقم الهاتف:</th>
                                <td>{{ $user->phone }}</td>
                            </tr>
                        @endif
                        @if($user->gender)
                            <tr>
                                <th>الجنس:</th>
                                <td>{{ $user->gender === 'male' ? 'ذكر' : 'أنثى' }}</td>
                            </tr>
                        @endif
                        @if($user->birth_date)
                            <tr>
                                <th>تاريخ الميلاد:</th>
                                <td>{{ $user->birth_date->format('Y-m-d') }}</td>
                            </tr>
                        @endif
                        @if($user->address)
                            <tr>
                                <th>العنوان:</th>
                                <td>{{ $user->address }}</td>
                            </tr>
                        @endif
                        @if($user->country)
                            <tr>
                                <th>الدولة:</th>
                                <td>{{ $user->country }}</td>
                            </tr>
                        @endif
                        @if($user->bio)
                            <tr>
                                <th>النبذة:</th>
                                <td>{{ $user->bio }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        {{-- Roles & Status --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-shield mr-2"></i>
                        الأدوار والصلاحيات
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>الدور الأساسي:</strong>
                        <span class="badge badge-info ml-2">
                            {{ $user->role === 'admin' ? 'مدير' : ($user->role === 'moderator' ? 'مشرف' : ($user->role === 'trader' ? 'تاجر' : 'مستخدم')) }}
                        </span>
                    </div>
                    @if($user->roles->count() > 0)
                        <div class="mb-3">
                            <strong>الأدوار الإضافية:</strong>
                            <div class="mt-2">
                                @foreach($user->roles as $role)
                                    <span class="badge badge-success mr-2 mb-2">
                                        {{ $role->display_name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <hr>
                    <div class="mb-2">
                        <strong>الحالة:</strong>
                        <span class="badge badge-{{ $currentStatus['class'] }} ml-2">
                            {{ $currentStatus['label'] }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <strong>التوثيق:</strong>
                        <span class="badge badge-{{ $user->is_verified ? 'success' : 'secondary' }} ml-2">
                            {{ $user->is_verified ? 'موثق' : 'غير موثق' }}
                        </span>
                    </div>
                    <div class="mb-2">
                        <strong>تاريخ الإنشاء:</strong>
                        <span class="text-muted">{{ $user->created_at->format('Y-m-d H:i:s') }}</span>
                    </div>
                    <div>
                        <strong>آخر تحديث:</strong>
                        <span class="text-muted">{{ $user->updated_at->format('Y-m-d H:i:s') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Audit Logs --}}
    @if($user->auditLogs->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-history mr-2"></i>
                            سجل التغييرات
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>التاريخ</th>
                                        <th>الإجراء</th>
                                        <th>المستخدم</th>
                                        <th>التفاصيل</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->auditLogs as $log)
                                        <tr>
                                            <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ $log->event }}</span>
                                            </td>
                                            <td>{{ $log->user->name ?? '-' }}</td>
                                            <td>
                                                <small class="text-muted">{{ Str::limit($log->description, 50) }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

