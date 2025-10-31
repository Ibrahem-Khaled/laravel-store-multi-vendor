@extends('layouts.app')

@section('title', 'إدارة الأدوار والصلاحيات - لوحة التحكم')

@push('styles')
    <style>
        .permission-badge {
            font-size: 0.75rem;
            margin: 2px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid" dir="rtl">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-user-shield text-primary"></i>
                    إدارة الأدوار والصلاحيات
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active" aria-current="page">الأدوار والصلاحيات</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- بطاقة قائمة الأدوار --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list"></i> قائمة الأدوار
                </h6>
                <a href="{{ route('roles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> إضافة دور جديد
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>الاسم</th>
                                <th>الاسم المعروض</th>
                                <th>الوصف</th>
                                <th>عدد المستخدمين</th>
                                <th>عدد الصلاحيات</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $role)
                                <tr>
                                    <td>
                                        <strong>{{ $role->name }}</strong>
                                    </td>
                                    <td>{{ $role->display_name }}</td>
                                    <td>
                                        <small class="text-muted">{{ Str::limit($role->description ?? '-', 50) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $role->users_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-success">{{ $role->permissions_count }}</span>
                                    </td>
                                    <td>
                                        @if($role->is_active)
                                            <span class="badge badge-success">نشط</span>
                                        @else
                                            <span class="badge badge-secondary">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('roles.edit', $role) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($role->name !== 'admin')
                                            <form action="{{ route('roles.destroy', $role) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف هذا الدور؟')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary" 
                                                    disabled 
                                                    title="لا يمكن حذف دور المدير">
                                                <i class="fas fa-lock"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                            <p class="text-muted">لا توجد أدوار</p>
                                            <a href="{{ route('roles.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> إضافة دور جديد
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

