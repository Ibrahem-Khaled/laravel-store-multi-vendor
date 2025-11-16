@extends('layouts.app')

@push('styles')
<style>
    .user-avatar {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #e3e6f0;
    }

    .stat-card {
        border-left: 4px solid;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .filter-card {
        background: #f8f9fc;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .table th {
        font-weight: 600;
        border-bottom: 2px solid #e3e6f0;
        white-space: nowrap;
    }

    .table td {
        vertical-align: middle;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.3s ease;
        margin: 0 2px;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .role-badge {
        margin: 2px;
        display: inline-block;
        font-size: 0.75rem;
    }

    .nav-tabs .nav-link {
        border: none;
        border-bottom: 2px solid transparent;
        color: #5a5c69;
        font-weight: 500;
        transition: all 0.3s ease;
        padding: 0.75rem 1rem;
    }

    .nav-tabs .nav-link:hover {
        border-bottom-color: #4e73df;
        color: #4e73df;
    }

    .nav-tabs .nav-link.active {
        border-bottom-color: #4e73df;
        color: #4e73df;
        font-weight: 600;
    }

    .user-info {
        min-width: 200px;
    }

    .status-badge {
        font-weight: 600;
        padding: 0.4em 0.8em;
        font-size: 0.85rem;
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
                        <i class="fas fa-users mr-2"></i>
                        إدارة المستخدمين
                    </h1>
                    <nav aria-label="breadcrumb" class="mt-2">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}">
                                    <i class="fas fa-home"></i> لوحة التحكم
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">المستخدمين</li>
                        </ol>
                    </nav>
                </div>
                <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#createUserModal">
                    <i class="fas fa-plus"></i> إضافة مستخدم جديد
                </button>
            </div>
        </div>
    </div>

    @include('components.alerts')

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <x-stat-card title="إجمالي المستخدمين" :count="$stats['total']" icon="users" color="primary" />
        <x-stat-card title="المستخدمون النشطون" :count="$stats['active']" icon="user-check" color="success" />
        <x-stat-card title="قيد الاعتماد" :count="$stats['pending']" icon="clock" color="warning" />
        <x-stat-card title="موثقون" :count="$stats['verified']" icon="check-circle" color="info" />
    </div>

    {{-- Main Card --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list mr-2"></i>
                قائمة المستخدمين
            </h6>
            <div class="text-muted small">
                إجمالي: {{ $users->total() }} مستخدم
            </div>
        </div>

        <div class="card-body">
            {{-- Role Tabs --}}
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $filters['role'] === 'all' ? 'active' : '' }}"
                       href="{{ route('users.index', array_merge(request()->except('role'), ['role' => 'all'])) }}">
                        <i class="fas fa-list"></i> الكل
                        <span class="badge badge-secondary ml-1">{{ $stats['total'] }}</span>
                    </a>
                </li>
                @foreach ($oldRoles as $roleKey => $roleLabel)
                    <li class="nav-item">
                        <a class="nav-link {{ $filters['role'] === $roleKey ? 'active' : '' }}"
                           href="{{ route('users.index', array_merge(request()->except('role'), ['role' => $roleKey])) }}">
                            {{ $roleLabel }}
                            <span class="badge badge-light border ml-1">{{ $roleCounts['old'][$roleKey] ?? 0 }}</span>
                        </a>
                    </li>
                @endforeach
                @foreach ($dbRoles as $role)
                    <li class="nav-item">
                        <a class="nav-link {{ $filters['role'] === $role->name ? 'active' : '' }}"
                           href="{{ route('users.index', array_merge(request()->except('role'), ['role' => $role->name])) }}">
                            {{ $role->display_name }}
                            <span class="badge badge-info border ml-1">{{ $roleCounts['new'][$role->name] ?? 0 }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- Filters --}}
            <div class="filter-card">
                <form action="{{ route('users.index') }}" method="GET" class="row g-3">
                    <input type="hidden" name="role" value="{{ $filters['role'] }}">

                    <div class="col-md-4">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text"
                                   name="search"
                                   class="form-control"
                                   placeholder="ابحث بالاسم، البريد، الهاتف أو اسم المستخدم..."
                                   value="{{ $filters['search'] }}">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <select name="status" class="form-control">
                            <option value="all">جميع الحالات</option>
                            <option value="active" {{ $filters['status'] === 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="pending" {{ $filters['status'] === 'pending' ? 'selected' : '' }}>قيد الاعتماد</option>
                            <option value="inactive" {{ $filters['status'] === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            <option value="banned" {{ $filters['status'] === 'banned' ? 'selected' : '' }}>محظور</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="sort" class="form-control">
                            <option value="id" {{ $filters['sort'] === 'id' ? 'selected' : '' }}>ترتيب حسب ID</option>
                            <option value="name" {{ $filters['sort'] === 'name' ? 'selected' : '' }}>ترتيب حسب الاسم</option>
                            <option value="created_at" {{ $filters['sort'] === 'created_at' ? 'selected' : '' }}>ترتيب حسب التاريخ</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="direction" class="form-control">
                            <option value="desc" {{ $filters['direction'] === 'desc' ? 'selected' : '' }}>تنازلي</option>
                            <option value="asc" {{ $filters['direction'] === 'asc' ? 'selected' : '' }}>تصاعدي</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> بحث
                        </button>
                    </div>

                    @if ($filters['search'] || $filters['status'] !== 'all')
                        <div class="col-md-12 mt-2">
                            <a href="{{ route('users.index', ['role' => $filters['role']]) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-times"></i> إلغاء الفلاتر
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            {{-- Users Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="20%">المستخدم</th>
                            <th width="12%">الأدوار</th>
                            <th width="15%">معلومات الاتصال</th>
                            <th width="10%">الحالة</th>
                            <th width="8%">الرصيد</th>
                            <th width="10%">التوثيق</th>
                            <th width="20%">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center user-info">
                                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('img/default-avatar.png') }}"
                                             alt="{{ $user->name }}"
                                             class="user-avatar mr-3"
                                             onerror="this.onerror=null; this.src='{{ asset('img/default-avatar.png') }}';">
                                        <div>
                                            <strong class="d-block">{{ $user->name }}</strong>
                                            <small class="text-muted d-block">
                                                <i class="fas fa-at"></i> {{ $user->username }}
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-fingerprint"></i> {{ Str::limit($user->uuid, 8) }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-info role-badge">
                                        {{ $oldRoles[$user->role] ?? $user->role }}
                                    </span>
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-success role-badge">
                                                {{ $role->display_name }}
                                            </span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <div class="small">
                                        @if($user->email)
                                            <div class="mb-1">
                                                <i class="fas fa-envelope text-primary"></i>
                                                {{ Str::limit($user->email, 25) }}
                                            </div>
                                        @endif
                                        @if($user->phone)
                                            <div>
                                                <i class="fas fa-phone text-success"></i>
                                                {{ $user->phone }}
                                            </div>
                                        @endif
                                        @if(!$user->email && !$user->phone)
                                            <span class="text-muted">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $statusConfig = [
                                            'pending' => ['class' => 'warning', 'label' => 'قيد الاعتماد', 'icon' => 'clock'],
                                            'active' => ['class' => 'success', 'label' => 'نشط', 'icon' => 'check-circle'],
                                            'inactive' => ['class' => 'secondary', 'label' => 'غير نشط', 'icon' => 'ban'],
                                            'banned' => ['class' => 'danger', 'label' => 'محظور', 'icon' => 'times-circle']
                                        ];
                                        $currentStatus = $statusConfig[$user->status] ?? ['class' => 'light', 'label' => $user->status, 'icon' => 'question'];
                                    @endphp
                                    <span class="badge status-badge badge-{{ $currentStatus['class'] }}">
                                        <i class="fas fa-{{ $currentStatus['icon'] }}"></i>
                                        {{ $currentStatus['label'] }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-warning">
                                        <i class="fas fa-coins"></i> {{ number_format($user->coins ?? 0) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('users.toggle-verification', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-sm {{ $user->is_verified ? 'btn-success' : 'btn-outline-secondary' }}"
                                                style="min-width: 90px;"
                                                onclick="return confirm('{{ $user->is_verified ? 'هل أنت متأكد من إلغاء توثيق هذا الحساب؟' : 'هل أنت متأكد من توثيق هذا الحساب؟' }}')">
                                            @if($user->is_verified)
                                                <i class="fas fa-check-circle"></i> موثق
                                            @else
                                                <i class="fas fa-times-circle"></i> غير موثق
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('users.show', $user) }}" 
                                           class="btn btn-sm btn-info btn-action"
                                           title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <button type="button"
                                                class="btn btn-sm btn-primary btn-action"
                                                data-toggle="modal"
                                                data-target="#editUserModal{{ $user->id }}"
                                                title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        @if (in_array($user->status, ['pending', 'inactive']))
                                            <form action="{{ route('users.approve', $user) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من تفعيل هذا المستخدم؟')">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm btn-success btn-action"
                                                        title="تفعيل">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                        @if ($user->status === 'active')
                                            <form action="{{ route('users.deactivate', $user) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من إلغاء تفعيل هذا المستخدم؟')">
                                                @csrf
                                                <button type="submit"
                                                        class="btn btn-sm btn-warning btn-action"
                                                        title="إلغاء التفعيل">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <button type="button"
                                                class="btn btn-sm btn-danger btn-action"
                                                data-toggle="modal"
                                                data-target="#deleteUserModal{{ $user->id }}"
                                                title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                    {{-- Modals --}}
                                    @include('dashboard.users.modals.edit', ['user' => $user])
                                    @include('dashboard.users.modals.delete', ['user' => $user])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-user-slash fa-3x text-gray-300 mb-3"></i>
                                    <p class="text-gray-500 mb-0">لا يوجد مستخدمون</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($users->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Create User Modal --}}
@include('dashboard.users.modals.create')
@endsection

@push('scripts')
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
