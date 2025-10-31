@extends('layouts.app')

@section('title', 'سجل التدقيق - لوحة التحكم')

@push('styles')
    <style>
        .audit-badge {
            font-size: 0.85rem;
            padding: 0.35rem 0.65rem;
        }

        .audit-action-created {
            background-color: #28a745;
            color: white;
        }

        .audit-action-updated {
            background-color: #ffc107;
            color: #212529;
        }

        .audit-action-deleted {
            background-color: #dc3545;
            color: white;
        }

        .audit-action-restored {
            background-color: #17a2b8;
            color: white;
        }

        .filter-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.35rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid" dir="rtl">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-clipboard-list text-primary"></i>
                    سجل التدقيق
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active" aria-current="page">سجل التدقيق</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    إجمالي السجلات</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    اليوم</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['today']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    هذا الأسبوع</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['this_week']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    هذا الشهر</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['this_month']) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- بطاقة الفلاتر --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-filter"></i> فلاتر البحث
                </h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('audit-logs.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>البحث</label>
                                <input type="text" name="search" class="form-control" placeholder="ابحث في الوصف..." value="{{ request('search') }}">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>المستخدم</label>
                                <select name="user_id" class="form-control">
                                    <option value="">كل المستخدمين</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>العملية</label>
                                <select name="action" class="form-control">
                                    <option value="">كل العمليات</option>
                                    @foreach($actions as $key => $label)
                                        <option value="{{ $key }}" {{ request('action') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>النموذج</label>
                                <select name="auditable_type" class="form-control">
                                    <option value="">كل النماذج</option>
                                    @foreach($auditableTypes as $type => $name)
                                        <option value="{{ $type }}" {{ request('auditable_type') == $type ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label>من تاريخ</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div北欧>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label>إلى تاريخ</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary form-control">
                                    <i class="fas fa-search"></i> بحث
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- بطاقة قائمة السجلات --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list"></i> قائمة سجلات التدقيق
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>المستخدم</th>
                                <th>العملية</th>
                                <th>النموذج</th>
                                <th>الوصف</th>
                                <th>عنوان IP</th>
                                <th>التاريخ والوقت</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($auditLogs as $log)
                                <tr>
                                    <td>{{ $log->id }}</td>
                                    <td>
                                        @if($log->user)
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-circle text-primary mr-2"></i>
                                                <div>
                                                    <strong>{{ $log->user->name }}</strong><br>
                                                    <small class="text-muted">{{ $log->user->email }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">
                                                <i class="fas fa-robot"></i> نظام
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $actionClasses = [
                                                'created' => 'audit-action-created',
                                                'updated' => 'audit-action-updated',
                                                'deleted' => 'audit-action-deleted',
                                                'restored' => 'audit-action-restored',
                                            ];
                                            $class = $actionClasses[$log->action] ?? 'badge-secondary';
                                        @endphp
                                        <span class="badge audit-badge {{ $class }}">
                                            {{ $log->getActionLabel() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $log->getModelLabel() }}
                                        </span>
                                    </td>
                                    <td>
                                        <small>{{ Str::limit($log->description, 50) }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $log->ip_address ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <small>
                                            {{ $log->created_at->format('Y-m-d') }}<br>
                                            {{ $log->created_at->format('H:i:s') }}
                                        </small>
                                    </td>
                                    <td>
                                        <a href="{{ route('audit-logs.show', $log) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="py-4">
                                            <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                            <p class="text-muted">لا توجد سجلات تدقيق</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                @if($auditLogs->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $auditLogs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

