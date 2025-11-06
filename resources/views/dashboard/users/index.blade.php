@extends('layouts.app')

@push('styles')
    <style>
        .user-avatar {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e3e6f0;
            max-width: 45px;
            max-height: 45px;
        }

        .badge-status {
            font-weight: 600;
            padding: 0.35em 0.65em;
            font-size: 0.85rem;
        }

        .table th {
            font-weight: 600;
            border-bottom: 2px solid #e3e6f0;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .stat-card {
            border-left: 4px solid;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .nav-tabs .nav-link {
            border: none;
            border-bottom: 2px solid transparent;
            color: #5a5c69;
            font-weight: 500;
            transition: all 0.3s ease;
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

        .role-badge {
            margin: 2px 4px;
            display: inline-block;
        }

        .filter-section {
            background: #f8f9fc;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid" dir="rtl">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
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

        {{-- إحصائيات المستخدمين --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    إجمالي المستخدمين
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    المستخدمون النشطون
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    قيد الاعتماد
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    عدد المشرفين
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['admins'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-shield fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- بطاقة قائمة المستخدمين --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list mr-2"></i>
                    قائمة المستخدمين
                </h6>
            </div>

            <div class="card-body">
                {{-- فلترة الأدوار --}}
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ $selectedRole === 'all' ? 'active' : '' }}"
                           href="{{ route('users.index', array_merge(request()->except('role'), ['role' => 'all'])) }}">
                            <i class="fas fa-list"></i> الكل
                            <span class="badge badge-secondary">{{ $stats['total'] }}</span>
                        </a>
                    </li>
                    @foreach ($oldRoles as $roleKey => $roleLabel)
                        <li class="nav-item">
                            <a class="nav-link {{ $selectedRole === $roleKey ? 'active' : '' }}"
                               href="{{ route('users.index', array_merge(request()->except('role'), ['role' => $roleKey])) }}">
                                {{ $roleLabel }}
                                <span class="badge badge-light border">{{ $roleCounts[$roleKey] ?? 0 }}</span>
                            </a>
                        </li>
                    @endforeach
                    @foreach ($dbRoles as $role)
                        <li class="nav-item">
                            <a class="nav-link {{ $selectedRole === $role->name ? 'active' : '' }}"
                               href="{{ route('users.index', array_merge(request()->except('role'), ['role' => $role->name])) }}">
                                {{ $role->display_name }}
                                <span class="badge badge-info border">{{ $roleCountsNew[$role->name] ?? 0 }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                {{-- نموذج البحث والفلترة --}}
                <div class="filter-section">
                    <form action="{{ route('users.index') }}" method="GET" class="form-inline">
                        <input type="hidden" name="role" value="{{ $selectedRole }}">

                        <div class="form-group flex-grow-1 mr-2 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text"
                                       name="search"
                                       class="form-control"
                                       placeholder="ابحث بالاسم، البريد، الهاتف أو اسم المستخدم..."
                                       value="{{ $search }}">
                            </div>
                        </div>

                        <div class="form-group mr-2 mb-2">
                            <select name="status" class="form-control">
                                <option value="all">جميع الحالات</option>
                                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>قيد الاعتماد</option>
                                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                <option value="banned" {{ $status === 'banned' ? 'selected' : '' }}>محظور</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mb-2">
                            <i class="fas fa-filter"></i> بحث
                        </button>

                        @if ($search || $status !== 'all')
                            <a href="{{ route('users.index', ['role' => $selectedRole]) }}" class="btn btn-secondary mb-2">
                                <i class="fas fa-times"></i> إلغاء
                            </a>
                        @endif
                    </form>
                </div>

                {{-- جدول المستخدمين --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="18%">المستخدم</th>
                                <th width="12%">الأدوار</th>
                                <th width="15%">معلومات الاتصال</th>
                                <th width="10%">الحالة</th>
                                <th width="8%">الرصيد</th>
                                <th width="8%">التوثيق</th>
                                <th width="24%">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('img/default-avatar.png') }}"
                                                 alt="{{ $user->name }}"
                                                 class="user-avatar mr-3"
                                                 style="width: 45px; height: 45px; object-fit: cover;"
                                                 onerror="this.onerror=null; this.src='{{ asset('img/default-avatar.png') }}';">
                                            <div>
                                                <strong>{{ $user->name }}</strong>
                                                <div class="small text-muted">
                                                    <i class="fas fa-at"></i> {{ $user->username }}
                                                </div>
                                                <div class="small text-muted">
                                                    <i class="fas fa-fingerprint"></i> {{ Str::limit($user->uuid, 8) }}
                                                </div>
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
                                            <div>
                                                <i class="fas fa-envelope text-primary"></i>
                                                {{ $user->email ?? '-' }}
                                            </div>
                                            <div class="mt-1">
                                                <i class="fas fa-phone text-success"></i>
                                                {{ $user->phone ?? '-' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'pending' => 'warning',
                                                'active' => 'success',
                                                'inactive' => 'secondary',
                                                'banned' => 'danger'
                                            ];
                                            $statusLabels = [
                                                'pending' => 'قيد الاعتماد',
                                                'active' => 'نشط',
                                                'inactive' => 'غير نشط',
                                                'banned' => 'محظور'
                                            ];
                                        @endphp
                                        <span class="badge badge-status badge-{{ $statusClasses[$user->status] ?? 'light' }}">
                                            {{ $statusLabels[$user->status] ?? $user->status }}
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
                                                    style="min-width: 100px; font-weight: 500;"
                                                    title="{{ $user->is_verified ? 'إلغاء التوثيق' : 'توثيق الحساب' }}"
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
                                            <button type="button"
                                                    class="btn btn-sm btn-info btn-action"
                                                    data-toggle="modal"
                                                    data-target="#showUserModal{{ $user->id }}"
                                                    title="عرض التفاصيل">
                                                <i class="fas fa-eye"></i>
                                            </button>

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

                                        {{-- مودالات لكل مستخدم --}}
                                        @include('dashboard.users.modals.show', ['user' => $user])
                                        @include('dashboard.users.modals.edit', ['user' => $user])
                                        @include('dashboard.users.modals.delete', ['user' => $user])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-user-slash fa-3x text-gray-300 mb-3"></i>
                                        <p class="text-gray-500">لا يوجد مستخدمون</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة مستخدم --}}
    @include('dashboard.users.modals.create')
@endsection

@push('scripts')
    <script>
        // عرض اسم الملف لحقل الرفع
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });

        // تفعيل الـ Tooltip
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });

        // تأكيد الحذف
        $('.delete-user-form').on('submit', function(e) {
            if (!confirm('هل أنت متأكد من حذف هذا المستخدم؟ لا يمكن التراجع عن هذا الإجراء.')) {
                e.preventDefault();
            }
        });
    </script>
@endpush
