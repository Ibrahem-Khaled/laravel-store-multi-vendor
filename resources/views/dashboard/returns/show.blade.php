@extends('layouts.app')

@section('title', 'تفاصيل المرتجع #' . $return->id)

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">تفاصيل المرتجع #{{ $return->id }}</h1>
        <a href="{{ route('returns.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-right me-2"></i>
            العودة للقائمة
        </a>
    </div>

    @include('components.alerts')

    <div class="row">
        <!-- معلومات المرتجع -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">معلومات المرتجع</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>رقم الطلب:</strong>
                            <a href="{{ route('orders.show', $return->order_id) }}" class="text-primary">
                                #{{ $return->order_id }}
                            </a>
                        </div>
                        <div class="col-md-6">
                            <strong>النوع:</strong>
                            @php
                                $typeLabels = [
                                    'return' => 'إرجاع',
                                    'refund' => 'استرداد',
                                    'replacement' => 'استبدال'
                                ];
                            @endphp
                            <span class="badge badge-info">{{ $typeLabels[$return->type] ?? $return->type }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>الحالة:</strong>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'approved' => 'info',
                                    'rejected' => 'danger',
                                    'processing' => 'primary',
                                    'completed' => 'success',
                                    'cancelled' => 'secondary'
                                ];
                            @endphp
                            <span class="badge badge-{{ $statusColors[$return->status] ?? 'secondary' }}">
                                @if($return->status == 'pending') قيد المراجعة
                                @elseif($return->status == 'approved') موافق عليها
                                @elseif($return->status == 'rejected') مرفوضة
                                @elseif($return->status == 'processing') قيد المعالجة
                                @elseif($return->status == 'completed') مكتملة
                                @elseif($return->status == 'cancelled') ملغاة
                                @else {{ $return->status }}
                                @endif
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>تاريخ الطلب:</strong>
                            {{ $return->created_at->format('Y-m-d H:i') }}
                        </div>
                    </div>

                    @if($return->order_item_id)
                        <div class="row mb-3">
                            <div class="col-12">
                                <strong>عنصر الطلب المراد إرجاعه:</strong>
                                @if($return->orderItem)
                                    <div class="mt-2 p-3 bg-light rounded">
                                        <strong>{{ $return->orderItem->product->name }}</strong>
                                        <br>
                                        <small>الكمية: {{ $return->orderItem->quantity }} × {{ number_format($return->orderItem->unit_price, 2) }} ريال</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <strong>سبب الإرجاع:</strong>
                        <div class="mt-2 p-3 bg-light rounded">
                            {{ $return->reason }}
                        </div>
                    </div>

                    @if($return->customer_notes)
                        <div class="mb-3">
                            <strong>ملاحظات العميل:</strong>
                            <div class="mt-2 p-3 bg-light rounded">
                                {{ $return->customer_notes }}
                            </div>
                        </div>
                    @endif

                    @if($return->admin_notes)
                        <div class="mb-3">
                            <strong>ملاحظات الإدارة:</strong>
                            <div class="mt-2 p-3 bg-info text-white rounded">
                                {{ $return->admin_notes }}
                            </div>
                        </div>
                    @endif

                    @if($return->images && count($return->images) > 0)
                        <div class="mb-3">
                            <strong>صور المنتج:</strong>
                            <div class="row mt-2">
                                @foreach($return->images as $image)
                                    <div class="col-md-3 mb-2">
                                        <a href="{{ asset('storage/' . $image) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $image) }}" 
                                                 class="img-thumbnail" 
                                                 style="max-height: 150px; width: 100%; object-fit: cover;">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($return->refund_amount)
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <strong>مبلغ الاسترداد:</strong>
                                <div class="h4 text-success">{{ number_format($return->refund_amount, 2) }} ريال</div>
                            </div>
                            <div class="col-md-6">
                                <strong>طريقة الاسترداد:</strong>
                                <div>
                                    @if($return->refund_method == 'original_payment') نفس طريقة الدفع الأصلية
                                    @elseif($return->refund_method == 'wallet') المحفظة
                                    @elseif($return->refund_method == 'bank_transfer') تحويل بنكي
                                    @else {{ $return->refund_method }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($return->replacementOrder)
                        <div class="mb-3">
                            <strong>طلب الاستبدال:</strong>
                            <a href="{{ route('orders.show', $return->replacementOrder->id) }}" class="text-primary">
                                #{{ $return->replacementOrder->id }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- معلومات الطلب -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">معلومات الطلب</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>العميل:</strong>
                            <div>{{ $return->order->user->name }}</div>
                            <small class="text-muted">{{ $return->order->user->email }}</small>
                        </div>
                        <div class="col-md-6">
                            <strong>حالة الطلب:</strong>
                            <span class="badge badge-info">{{ $return->order->status }}</span>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>الإجمالي:</strong>
                            {{ number_format($return->order->grand_total, 2) }} ريال
                        </div>
                        <div class="col-md-6">
                            <strong>طريقة الدفع:</strong>
                            {{ $return->order->payment_method }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- الإجراءات -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">الإجراءات</h6>
                </div>
                <div class="card-body">
                    @if($return->status == 'pending')
                        <!-- الموافقة -->
                        <form action="{{ route('returns.approve', $return->id) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="form-group">
                                <label>ملاحظات الإدارة</label>
                                <textarea name="admin_notes" class="form-control" rows="3" 
                                          placeholder="ملاحظات اختيارية...">{{ old('admin_notes') }}</textarea>
                            </div>
                            @if($return->type != 'replacement')
                                <div class="form-group">
                                    <label>مبلغ الاسترداد (ريال)</label>
                                    <input type="number" name="refund_amount" class="form-control" 
                                           step="0.01" min="0" 
                                           value="{{ old('refund_amount', $return->refund_amount) }}">
                                </div>
                                <div class="form-group">
                                    <label>طريقة الاسترداد</label>
                                    <select name="refund_method" class="form-control">
                                        <option value="original_payment">نفس طريقة الدفع الأصلية</option>
                                        <option value="wallet">المحفظة</option>
                                        <option value="bank_transfer">تحويل بنكي</option>
                                    </select>
                                </div>
                            @endif
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check me-2"></i>
                                الموافقة
                            </button>
                        </form>

                        <!-- الرفض -->
                        <form action="{{ route('returns.reject', $return->id) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="form-group">
                                <label>سبب الرفض <span class="text-danger">*</span></label>
                                <textarea name="admin_notes" class="form-control" rows="3" required 
                                          placeholder="يرجى توضيح سبب الرفض...">{{ old('admin_notes') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-times me-2"></i>
                                رفض
                            </button>
                        </form>
                    @endif

                    @if($return->status == 'approved')
                        <!-- وضع قيد المعالجة -->
                        <form action="{{ route('returns.process', $return->id) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="form-group">
                                <label>ملاحظات</label>
                                <textarea name="admin_notes" class="form-control" rows="3" 
                                          placeholder="ملاحظات اختيارية...">{{ old('admin_notes') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-cog me-2"></i>
                                وضع قيد المعالجة
                            </button>
                        </form>
                    @endif

                    @if(in_array($return->status, ['approved', 'processing']))
                        <!-- إكمال -->
                        <form action="{{ route('returns.complete', $return->id) }}" method="POST" class="mb-3">
                            @csrf
                            <div class="form-group">
                                <label>ملاحظات</label>
                                <textarea name="admin_notes" class="form-control" rows="3" 
                                          placeholder="ملاحظات اختيارية...">{{ old('admin_notes') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-check-double me-2"></i>
                                إكمال
                            </button>
                        </form>
                    @endif

                    @if(in_array($return->status, ['pending', 'approved']))
                        <!-- إلغاء -->
                        <form action="{{ route('returns.cancel', $return->id) }}" method="POST" 
                              onsubmit="return confirm('هل أنت متأكد من إلغاء المرتجع؟')">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-block">
                                <i class="fas fa-ban me-2"></i>
                                إلغاء
                            </button>
                        </form>
                    @endif

                    <!-- معلومات المعالج -->
                    @if($return->processedBy)
                        <hr>
                        <div class="mt-3">
                            <small class="text-muted">
                                <strong>معالج الطلب:</strong> {{ $return->processedBy->name }}<br>
                                <strong>تاريخ المعالجة:</strong> {{ $return->processed_at?->format('Y-m-d H:i') }}
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

