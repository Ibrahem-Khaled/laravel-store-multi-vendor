<div class="modal fade" id="editReviewModal{{ $review->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editReviewModalLabel{{ $review->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReviewModalLabel{{ $review->id }}">تعديل التقييم</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="product_id{{ $review->id }}">المنتج</label>
                        <input type="text" class="form-control" value="{{ $review->product->name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="user_id{{ $review->id }}">المستخدم</label>
                        <input type="text" class="form-control" value="{{ $review->user->name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="rate{{ $review->id }}">التقييم</label>
                        <div class="rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $review->rate ? 'fas' : 'far' }} fa-star text-warning star-edit"
                                    data-value="{{ $i }}" style="cursor: pointer; font-size: 1.5rem;"></i>
                            @endfor
                            <input type="hidden" name="rate" id="rate{{ $review->id }}"
                                value="{{ $review->rate }}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comment{{ $review->id }}">التعليق</label>
                        <textarea class="form-control" id="comment{{ $review->id }}" name="comment" rows="3">{{ $review->comment }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // تحديد النجوم للتقييم في التعديل
            $('.star-edit').click(function() {
                const value = $(this).data('value');
                const reviewId = "{{ $review->id }}";
                $('#rate' + reviewId).val(value);

                // تحديث عرض النجوم
                $(this).parent().find('i').each(function(index) {
                    if (index < value) {
                        $(this).removeClass('far').addClass('fas');
                    } else {
                        $(this).removeClass('fas').addClass('far');
                    }
                });
            });
        });
    </script>
@endpush
