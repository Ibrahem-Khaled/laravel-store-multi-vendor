@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">طلبات الشحن والعملات</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item active" aria-current="page">طلبات الشحن والعملات</li>
                </ol>
            </nav>
        </div>

        @include('components.alerts')

        {{-- إحصائيات الطلبات --}}
        <div class="row mb-4">
            <x-stat-card icon="fas fa-list-alt" title="إجمالي الطلبات" :value="$totalCount" color="primary" />
            <x-stat-card icon="fas fa-hourglass-half" title="طلبات معلقة" :value="$pendingCount" color="warning" />
            <x-stat-card icon="fas fa-check-circle" title="طلبات مقبولة" :value="$approvedCount" color="success" />
            <x-stat-card icon="fas fa-times-circle" title="طلبات مرفوضة" :value="$rejectedCount" color="danger" />
            <x-stat-card icon="fas fa-coins" title="إجمالي العملات المضافة" :value="number_format($totalCoinsAdded)" color="info" />
        </div>

        {{-- بطاقة قائمة الطلبات --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">قائمة طلبات الشحن</h6>
            </div>
            <div class="card-body">
                {{-- تبويب الحالات --}}
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ !$status ? 'active' : '' }}" href="{{ route('shipping-proofs.index') }}">الكل</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'pending' ? 'active' : '' }}" href="{{ route('shipping-proofs.index', ['status' => 'pending']) }}">
                            قيد المراجعة
                            @if($pendingCount > 0)
                                <span class="badge badge-danger">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'approved' ? 'active' : '' }}" href="{{ route('shipping-proofs.index', ['status' => 'approved']) }}">مقبولة</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'rejected' ? 'active' : '' }}" href="{{ route('shipping-proofs.index', ['status' => 'rejected']) }}">مرفوضة</a>
                    </li>
                </ul>

                {{-- جدول الطلبات --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>المستخدم</th>
                                <th>القيمة المطلوبة</th>
                                <th>صورة الأصل</th>
                                <th>الحالة</th>
                                <th>العملات المضافة</th>
                                <th>تاريخ الطلب</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($proofs as $proof)
                                <tr>
                                    <td>{{ $loop->iteration + ($proofs->currentPage() - 1) * $proofs->perPage() }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $proof->user->avatar ? asset('storage/' . $proof->user->avatar) : asset('assets/img/undraw_profile.svg') }}"
                                                 alt="{{ $proof->user->name }}" class="rounded-circle mr-2" width="40" height="40">
                                            <div>
                                                <div class="font-weight-bold">{{ $proof->user->name }}</div>
                                                <div class="small text-gray-500">{{ $proof->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong class="text-primary">{{ number_format($proof->amount, 2) }} جنيه</strong>
                                    </td>
                                    <td>
                                        <a href="{{ asset('storage/' . $proof->proof_image) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-image"></i> عرض الصورة
                                        </a>
                                    </td>
                                    <td>
                                        @php
                                            $statusClasses = [
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                            ];
                                            $statusNames = [
                                                'pending' => 'معلق',
                                                'approved' => 'مقبول',
                                                'rejected' => 'مرفوض',
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $statusClasses[$proof->status] ?? 'secondary' }}">
                                            {{ $statusNames[$proof->status] ?? $proof->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($proof->coins_added)
                                            <strong class="text-success">{{ number_format($proof->coins_added) }} عملة</strong>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>{{ $proof->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('shipping-proofs.show', $proof->id) }}" class="btn btn-info btn-circle btn-sm" title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($proof->status == 'pending')
                                            <button class="btn btn-success btn-circle btn-sm" data-toggle="modal" data-target="#approveModal{{ $proof->id }}" title="موافقة على الطلب">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-danger btn-circle btn-sm" data-toggle="modal" data-target="#rejectModal{{ $proof->id }}" title="رفض الطلب">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                {{-- مودال الموافقة --}}
                                @if($proof->status == 'pending')
                                    <div class="modal fade" id="approveModal{{ $proof->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('shipping-proofs.approve', $proof->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">الموافقة على طلب الشحن</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>عدد العملات المضافة *</label>
                                                            <input type="number" name="coins_amount" class="form-control" min="1" required>
                                                            <small class="form-text text-muted">القيمة المطلوبة: {{ number_format($proof->amount, 2) }} جنيه</small>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>ملاحظات الإدارة</label>
                                                            <textarea name="admin_notes" class="form-control" rows="3" maxlength="1000"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                                        <button type="submit" class="btn btn-success">موافقة</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- مودال الرفض --}}
                                    <div class="modal fade" id="rejectModal{{ $proof->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <form action="{{ route('shipping-proofs.reject', $proof->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">رفض طلب الشحن</h5>
                                                        <button type="button" class="close" data-dismiss="modal">
                                                            <span>&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>سبب الرفض *</label>
                                                            <textarea name="admin_notes" class="form-control" rows="3" maxlength="1000" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                                                        <button type="submit" class="btn btn-danger">رفض</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">لا توجد طلبات حاليًا.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $proofs->links() }}
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

