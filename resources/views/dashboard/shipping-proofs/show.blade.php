@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">تفاصيل طلب الشحن</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">لوحة التحكم</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shipping-proofs.index') }}">طلبات الشحن</a></li>
                    <li class="breadcrumb-item active" aria-current="page">تفاصيل الطلب</li>
                </ol>
            </nav>
        </div>

        @include('components.alerts')

        <div class="row">
            {{-- معلومات الطلب --}}
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">معلومات الطلب</h6>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>رقم الطلب:</strong></div>
                            <div class="col-md-8">#{{ $proof->id }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>الحالة:</strong></div>
                            <div class="col-md-8">
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
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>القيمة المطلوبة:</strong></div>
                            <div class="col-md-8"><strong class="text-primary">{{ number_format($proof->amount, 2) }} جنيه</strong></div>
                        </div>
                        @if($proof->coins_added)
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>العملات المضافة:</strong></div>
                                <div class="col-md-8"><strong class="text-success">{{ number_format($proof->coins_added) }} عملة</strong></div>
                            </div>
                        @endif
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>تاريخ الإنشاء:</strong></div>
                            <div class="col-md-8">{{ $proof->created_at->format('Y-m-d H:i:s') }}</div>
                        </div>
                        @if($proof->approved_at)
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>تاريخ الموافقة:</strong></div>
                                <div class="col-md-8">{{ $proof->approved_at->format('Y-m-d H:i:s') }}</div>
                            </div>
                        @endif
                        @if($proof->rejected_at)
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>تاريخ الرفض:</strong></div>
                                <div class="col-md-8">{{ $proof->rejected_at->format('Y-m-d H:i:s') }}</div>
                            </div>
                        @endif
                        @if($proof->admin_notes)
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>ملاحظات الإدارة:</strong></div>
                                <div class="col-md-8">{{ $proof->admin_notes }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- صورة الأصل --}}
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">صورة الأصل/الإيصال</h6>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('storage/' . $proof->proof_image) }}" alt="Proof Image" class="img-fluid" style="max-height: 500px;">
                    </div>
                </div>
            </div>

            {{-- معلومات المستخدم --}}
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">معلومات المستخدم</h6>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ $proof->user->avatar ? asset('storage/' . $proof->user->avatar) : asset('assets/img/undraw_profile.svg') }}"
                             alt="{{ $proof->user->name }}" class="rounded-circle mb-3" width="100" height="100">
                        <h5>{{ $proof->user->name }}</h5>
                        <p class="text-muted">{{ $proof->user->email }}</p>
                        <p class="text-muted">{{ $proof->user->username }}</p>
                        <hr>
                        <p><strong>رصيد العملات الحالي:</strong></p>
                        <h4 class="text-success">{{ number_format($proof->user->coins) }} عملة</h4>
                    </div>
                </div>

                @if($proof->admin)
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">معلومات المراجع</h6>
                        </div>
                        <div class="card-body">
                            <p><strong>الاسم:</strong> {{ $proof->admin->name }}</p>
                            <p><strong>اسم المستخدم:</strong> {{ $proof->admin->username }}</p>
                        </div>
                    </div>
                @endif

                {{-- الإجراءات --}}
                @if($proof->status == 'pending')
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">الإجراءات</h6>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-success btn-block mb-2" data-toggle="modal" data-target="#approveModal">
                                <i class="fas fa-check"></i> الموافقة على الطلب
                            </button>
                            <button class="btn btn-danger btn-block" data-toggle="modal" data-target="#rejectModal">
                                <i class="fas fa-times"></i> رفض الطلب
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- مودال الموافقة --}}
    @if($proof->status == 'pending')
        <div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
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
        <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
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
@endsection

