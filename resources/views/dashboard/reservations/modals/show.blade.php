<div class="modal fade" id="showReservationModal{{ $reservation->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تفاصيل الحجز #{{ $reservation->id }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <img src="{{ $reservation->user->avatar ? asset('storage/' . $reservation->user->avatar) : asset('img/default-avatar.png') }}"
                            class="rounded-circle" width="120" height="120">
                        <h5 class="mt-2">{{ $reservation->user->name }}</h5>
                        <p>{{ $reservation->user->phone }}</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="{{ $reservation->product->image ? asset('storage/' . $reservation->product->image) : asset('img/default-product.png') }}"
                            class="rounded" width="120" height="120">
                        <h5 class="mt-2">{{ $reservation->product->name }}</h5>
                        <p>{{ number_format($reservation->product->price, 2) }}   د</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5>معلومات الحجز</h5>
                                <p class="mb-1">
                                    <strong>النوع:</strong>
                                    <span class="badge badge-{{ $reservation->type === 'daily' ? 'info' : 'warning' }}">
                                        {{ $reservation->type === 'daily' ? 'يومي' : 'ساعي' }}
                                    </span>
                                </p>
                                <p class="mb-1">
                                    <strong>الحالة:</strong>
                                    <span
                                        class="badge badge-{{ $reservation->status === 'active' ? 'success' : ($reservation->status === 'returned' ? 'primary' : 'warning') }}">
                                        {{ $reservation->status === 'active' ? 'نشط' : ($reservation->status === 'returned' ? 'تم الإرجاع' : 'إرجاع جزئي') }}
                                    </span>
                                </p>
                                <p class="mb-1">
                                    <strong>المدة:</strong>
                                    @php
                                        $diff = $reservation->end_time->diff($reservation->start_time);
                                        if ($reservation->type === 'hourly') {
                                            echo $diff->h . ' ساعات ' . $diff->i . ' دقائق';
                                        } else {
                                            echo $diff->days . ' أيام ' . $diff->h . ' ساعات';
                                        }
                                    @endphp
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h6>تفاصيل الحجز</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>تاريخ البدء:</strong> {{ $reservation->start_time->format('Y-m-d H:i') }}
                                </p>
                                <p><strong>تاريخ الانتهاء:</strong> {{ $reservation->end_time->format('Y-m-d H:i') }}
                                </p>
                                <p><strong>تاريخ الإنشاء:</strong> {{ $reservation->created_at->format('Y-m-d H:i') }}
                                </p>
                                <p><strong>آخر تحديث:</strong> {{ $reservation->updated_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <h6>المعلومات المالية</h6>
                            </div>
                            <div class="card-body">
                                <p><strong>السعر الإجمالي:</strong> {{ number_format($reservation->total_price, 2) }}
                                      د</p>
                                <p><strong>طريقة الدفع:</strong> نقدي</p>
                                <p><strong>حالة الدفع:</strong> مدفوع</p>
                                <p><strong>المبلغ المسترجع:</strong>
                                    {{ $reservation->status === 'returned'
                                        ? number_format($reservation->total_price, 2)
                                        : ($reservation->status === 'partial_refund'
                                            ? number_format($reservation->total_price * 0.5, 2)
                                            : '0.00') }}
                                      د
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
