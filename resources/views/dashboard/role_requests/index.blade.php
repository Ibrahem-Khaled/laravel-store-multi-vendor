@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">طلبات تغيير الأدوار</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item active" aria-current="page">طلبات تغيير الأدوار</li>
                </ol>
            </nav>
        </div>

        @include('components.alerts')

        {{-- إحصائيات الطلبات --}}
        <div class="row">

                <x-stat-card icon="fas fa-list-alt" title="إجمالي الطلبات" :value="$stats->total ?? 0" color="primary" />

                <x-stat-card icon="fas fa-hourglass-half" title="طلبات قيد المراجعة" :value="$stats->pending ?? 0" color="warning" />

                <x-stat-card icon="fas fa-check-circle" title="طلبات مقبولة" :value="$stats->approved ?? 0" color="success" />

                <x-stat-card icon="fas fa-times-circle" title="طلبات مرفوضة" :value="$stats->rejected ?? 0" color="danger" />
        </div>

        {{-- بطاقة قائمة الطلبات --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">قائمة الطلبات</h6>
            </div>
            <div class="card-body">
                {{-- تبويب الحالات --}}
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ $selectedStatus === 'all' ? 'active' : '' }}" href="{{ route('role-requests.index') }}">الكل</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $selectedStatus === 'pending' ? 'active' : '' }}" href="{{ route('role-requests.index', ['status' => 'pending']) }}">قيد المراجعة</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $selectedStatus === 'approved' ? 'active' : '' }}" href="{{ route('role-requests.index', ['status' => 'approved']) }}">مقبولة</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $selectedStatus === 'rejected' ? 'active' : '' }}" href="{{ route('role-requests.index', ['status' => 'rejected']) }}">مرفوضة</a>
                    </li>
                </ul>

                {{-- جدول الطلبات --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>المستخدم</th>
                                <th>الدور المطلوب</th>
                                <th>تاريخ الطلب</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $request)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $request->user->avatar ? asset('storage/' . $request->user->avatar) : asset('assets/img/undraw_profile.svg') }}"
                                                 alt="{{ $request->user->name }}" class="rounded-circle mr-2" width="40" height="40">
                                            <div>
                                                <div class="font-weight-bold">{{ $request->user->name }}</div>
                                                <div class="small text-gray-500">{{ $request->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ $request->requested_role }}</span>
                                    </td>
                                    <td>{{ $request?->created_at?->format('Y-m-d H:i') }}</td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                            ];
                                            $statusNames = [
                                                'pending' => 'قيد المراجعة',
                                                'approved' => 'مقبول',
                                                'rejected' => 'مرفوض',
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $statusClasses[$request->status] ?? 'secondary' }}">
                                            {{ $statusNames[$request->status] ?? $request->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-info btn-circle btn-sm" data-toggle="modal" data-target="#showRequestModal{{ $request->id }}" title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        @if($request->status == 'pending')
                                            <button class="btn btn-success btn-circle btn-sm" data-toggle="modal" data-target="#approveRequestModal{{ $request->id }}" title="موافقة على الطلب">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-danger btn-circle btn-sm" data-toggle="modal" data-target="#rejectRequestModal{{ $request->id }}" title="رفض الطلب">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                {{-- تضمين المودالات لكل طلب --}}
                                @include('dashboard.role_requests.modals.show', ['request' => $request])
                                @include('dashboard.role_requests.modals.approve', ['request' => $request])
                                @include('dashboard.role_requests.modals.reject', ['request' => $request])

                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">لا توجد طلبات حاليًا.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('[data-toggle="tooltip"]').tooltip();
    </script>
@endpush
