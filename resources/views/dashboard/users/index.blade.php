@extends('layouts.app')

@push('styles')
    <style>
        /* تحسينات شكلية بسيطة */
        .btn-circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            line-height: 1;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .badge[class*="badge-"] {
            font-weight: 600;
        }

        .nav-tabs .badge {
            font-size: 0.75rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid" dir="rtl">

        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة المستخدمين</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active" aria-current="page">المستخدمين</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات المستخدمين --}}
        <div class="row mb-4">

                <x-stat-card icon="fas fa-users" title="إجمالي المستخدمين" :value="$usersCount" color="primary" />

                <x-stat-card icon="fas fa-user-check" title="المستخدمون النشطون" :value="$activeUsersCount" color="success" />

                <x-stat-card icon="fas fa-user-shield" title="عدد المشرفين" :value="$adminsCount" color="info" />

                <x-stat-card icon="fas fa-user-tag" title="عدد الأدوار" :value="count($roles)" color="warning" />
        </div>

        {{-- بطاقة قائمة المستخدمين --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">قائمة المستخدمين</h6>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">
                    <i class="fas fa-plus"></i> إضافة مستخدم
                </button>
            </div>

            <div class="card-body">
                {{-- تبويب الأدوار مع العدّاد --}}
                @php
                    $roleNames = [
                        'admin' => 'مدير',
                        'moderator' => 'مشرف',
                        'user' => 'مستخدم',
                        'trader' => 'متداول',
                    ];
                @endphp

                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ $selectedRole === 'all' ? 'active' : '' }}" href="{{ route('users.index') }}">
                            الكل
                            <span class="badge badge-pill badge-secondary ml-1">{{ $usersCount }}</span>
                        </a>
                    </li>
                    @foreach ($roles as $role)
                        <li class="nav-item">
                            <a class="nav-link {{ $selectedRole === $role ? 'active' : '' }}"
                                href="{{ route('users.index', ['role' => $role]) }}">
                                {{ $roleNames[$role] ?? $role }}
                                <span class="badge badge-pill badge-light border ml-1">{{ $roleCounts[$role] ?? 0 }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                {{-- نموذج البحث --}}
                <form action="{{ route('users.index') }}" method="GET" class="mb-4">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="ابحث بالاسم أو البريد أو الهاتف..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> بحث</button>
                        </div>
                    </div>
                </form>

                {{-- جدول المستخدمين --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>الاسم</th>
                                <th>الدور</th>
                                <th>البريد الإلكتروني</th>
                                <th>الهاتف</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('img/default-avatar.png') }}"
                                                alt="{{ $user->name }}" class="rounded-circle mr-2" width="40"
                                                height="40">
                                            <div class="d-flex flex-column">
                                                <strong>{{ $user->name }}</strong>
                                                <small class="text-muted">{{ '@' . $user->username }} ·
                                                    {{ $user->uuid }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $roleNames[$user->role] ?? $user->role }}</span>
                                    </td>
                                    <td>{{ $user->email ?? '-' }}</td>
                                    <td>{{ $user->phone ?? '-' }}</td>
                                    <td>
                                        @php $statusMap = ['pending'=>'warning','active'=>'success','inactive'=>'secondary','banned'=>'danger']; @endphp
                                        <span
                                            class="badge badge-{{ $statusMap[$user->status] ?? 'light' }}">{{ $user->status }}</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                            data-target="#showUserModal{{ $user->id }}" title="عرض"
                                            data-toggle="tooltip">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <button type="button" class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                            data-target="#editUserModal{{ $user->id }}" title="تعديل"
                                            data-toggle="tooltip">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deleteUserModal{{ $user->id }}" title="حذف"
                                            data-toggle="tooltip">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        {{-- زرّ تفعيل واضح في الصف (لو Pending أو Inactive) --}}
                                        @if (in_array($user->status, ['pending', 'inactive']))
                                            <form action="{{ route('users.approve', $user) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-success" title="تفعيل" data-toggle="tooltip">
                                                    <i class="fas fa-check"></i> تفعيل
                                                </button>
                                            </form>
                                        @endif

                                        {{-- مودالات لكل مستخدم --}}
                                        @include('dashboard.users.modals.show', ['user' => $user])
                                        @include('dashboard.users.modals.edit', ['user' => $user])
                                        @include('dashboard.users.modals.delete', ['user' => $user])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">لا يوجد مستخدمون</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">{{ $users->links() }}</div>
            </div>
        </div>
    </div>

    {{-- مودال إضافة مستخدم (ثابت) --}}
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
        $('[data-toggle="tooltip"]').tooltip();
    </script>
@endpush
