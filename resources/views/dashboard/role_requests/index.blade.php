@extends('layouts.app') {{-- تأكد من أن هذا هو ملف الـ layout الصحيح لديك --}}

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة طلبات تغيير الأدوار</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">لوحة التحكم</a></li>
                        <li class="breadcrumb-item active" aria-current="page">طلبات تغيير الأدوار</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- لعرض رسائل النجاح أو الخطأ --}}
        @include('components.alerts')

        {{-- إحصائيات الطلبات --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">إجمالي الطلبات</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-list-alt fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">الطلبات المعلقة</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-clock fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">الطلبات المقبولة
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['approved'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">الطلبات المرفوضة</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['rejected'] }}</div>
                            </div>
                            <div class="col-auto"><i class="fas fa-times-circle fa-2x text-gray-300"></i></div>
                        </div>
                    </div>
                </div>
            </div>
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
                        <a class="nav-link {{ $selectedStatus === 'pending' ? 'active' : '' }}"
                            href="{{ route('role-requests.index', ['status' => 'pending']) }}">
                            المعلقة <span class="badge badge-warning">{{ $stats['pending'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $selectedStatus === 'approved' ? 'active' : '' }}"
                            href="{{ route('role-requests.index', ['status' => 'approved']) }}">
                            المقبولة <span class="badge badge-success">{{ $stats['approved'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $selectedStatus === 'rejected' ? 'active' : '' }}"
                            href="{{ route('role-requests.index', ['status' => 'rejected']) }}">
                            المرفوضة <span class="badge badge-danger">{{ $stats['rejected'] }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $selectedStatus === 'all' ? 'active' : '' }}"
                            href="{{ route('role-requests.index', ['status' => 'all']) }}">الكل</a>
                    </li>
                </ul>

                {{-- نموذج البحث --}}
                <form action="{{ route('role-requests.index') }}" method="GET" class="mb-4">
                    <input type="hidden" name="status" value="{{ $selectedStatus }}">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                            placeholder="ابحث باسم المستخدم أو بريده الإلكتروني..." value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> بحث
                            </button>
                        </div>
                    </div>
                </form>

                {{-- جدول الطلبات --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>المستخدم</th>
                                <th>الدور المطلوب</th>
                                <th>الحالة</th>
                                <th>تاريخ الطلب</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $request)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $request->user->avatar ? asset('storage/' . $request->user->avatar) : 'https://via.placeholder.com/40' }}"
                                                alt="{{ $request->user->name }}" class="rounded-circle mr-2" width="40"
                                                height="40">
                                            <div>
                                                <strong>{{ $request->user->name }}</strong>
                                                <br>
                                                <small>{{ $request->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="badge badge-info">{{ $roleNames[$request->requested_role] ?? $request->requested_role }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusBadge = [
                                                'pending' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                            ];
                                        @endphp
                                        <span class="badge badge-{{ $statusBadge[$request->status] ?? 'secondary' }}">
                                            {{ $statusNames[$request->status] ?? $request->status }}
                                        </span>
                                    </td>
                                    <td>{{ $request?->created_at?->format('Y-m-d H:i') }}</td>
                                    <td>
                                        {{-- زر عرض التفاصيل --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                            data-target="#showRequestModal{{ $request->id }}" title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        @if ($request->status === 'pending')
                                            {{-- زر الموافقة --}}
                                            <button type="button" class="btn btn-sm btn-circle btn-success"
                                                data-toggle="modal" data-target="#approveRequestModal{{ $request->id }}"
                                                title="موافقة">
                                                <i class="fas fa-check"></i>
                                            </button>

                                            {{-- زر الرفض --}}
                                            <button type="button" class="btn btn-sm btn-circle btn-warning"
                                                data-toggle="modal" data-target="#rejectRequestModal{{ $request->id }}"
                                                title="رفض">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif

                                        {{-- زر الحذف --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-danger"
                                            data-toggle="modal" data-target="#deleteRequestModal{{ $request->id }}"
                                            title="حذف السجل">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">لا توجد طلبات تطابق هذا الفلتر.</td>
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

    {{-- =================================================================
                                    MODALS
    ================================================================= --}}
    @foreach ($requests as $request)
        <div class="modal fade" id="showRequestModal{{ $request->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">تفاصيل طلب: {{ $request->user->name }}</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>الدور المطلوب:</strong>
                            {{ $roleNames[$request->requested_role] ?? $request->requested_role }}</p>
                        <p><strong>سبب الطلب:</strong></p>
                        <p class="text-muted" style="white-space: pre-wrap;">{{ $request->reason }}</p>
                        <hr>
                        <p><strong>الحالة:</strong> {{ $statusNames[$request->status] ?? $request->status }}</p>
                        @if ($request->reviewed_by)
                            <p><strong>تمت المراجعة بواسطة:</strong> {{ $request->reviewer->name ?? 'مستخدم محذوف' }}</p>
                            <p><strong>ملاحظات المدير:</strong></p>
                            <p class="text-muted" style="white-space: pre-wrap;">{{ $request->admin_notes }}</p>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="approveRequestModal{{ $request->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('role-requests.approve', $request) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">تأكيد الموافقة</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <p>هل أنت متأكد من الموافقة على هذا الطلب؟</p>
                            <p>سيتم تغيير دور المستخدم <strong>{{ $request->user->name }}</strong> إلى
                                <strong>{{ $roleNames[$request->requested_role] ?? $request->requested_role }}</strong>.
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-success">نعم، موافقة</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="rejectRequestModal{{ $request->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('role-requests.reject', $request) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">رفض طلب: {{ $request->user->name }}</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="admin_notes_{{ $request->id }}">سبب الرفض (إجباري):</label>
                                <textarea name="admin_notes" id="admin_notes_{{ $request->id }}" class="form-control" rows="4" required
                                    minlength="10"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-danger">تأكيد الرفض</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteRequestModal{{ $request->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('role-requests.destroy', $request) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title">تأكيد الحذف</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <p>هل أنت متأكد من حذف سجل هذا الطلب نهائياً؟</p>
                            <p class="text-danger">لا يمكن التراجع عن هذا الإجراء.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-danger">نعم، حذف</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('scripts')
    <script>
        // تفعيل التولتيب الافتراضي للأزرار الصغيرة
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endpush
