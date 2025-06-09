<div class="modal fade" id="showReviewModal{{ $review->id }}" tabindex="-1" role="dialog"
    aria-labelledby="showReviewModalLabel{{ $review->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showReviewModalLabel{{ $review->id }}">تفاصيل التقييم</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">المنتج:</div>
                    <div class="col-md-8">{{ $review->product->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">المستخدم:</div>
                    <div class="col-md-8">{{ $review->user->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">التقييم:</div>
                    <div class="col-md-8">
                        <div class="rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <i
                                    class="fas fa-star {{ $i <= $review->rate ? 'text-warning' : 'text-secondary' }}"></i>
                            @endfor
                            <span class="badge badge-light">{{ $review->rate }}/5</span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">التعليق:</div>
                    <div class="col-md-8">{{ $review->comment ?? 'لا يوجد تعليق' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">الحالة:</div>
                    <div class="col-md-8">
                        @if ($review->is_approved)
                            <span class="badge badge-success">معتمد</span>
                        @else
                            <span class="badge badge-warning">معلق</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">تاريخ الإضافة:</div>
                    <div class="col-md-8">{{ $review->created_at->format('Y-m-d H:i') }}</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
