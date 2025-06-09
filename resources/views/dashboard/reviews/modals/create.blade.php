<div class="modal fade" id="createReviewModal" tabindex="-1" role="dialog" aria-labelledby="createReviewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createReviewModalLabel">إضافة تقييم جديد</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="product_id">المنتج</label>
                        <select class="form-control" id="product_id" name="product_id" required>
                            <option value="">اختر منتج...</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="user_id">المستخدم</label>
                        <select class="form-control" id="user_id" name="user_id" required>
                            <option value="">اختر مستخدم...</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="rate">التقييم</label>
                        <div class="rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="far fa-star text-warning" data-value="{{ $i }}"
                                    style="cursor: pointer; font-size: 1.5rem;"></i>
                            @endfor
                            <input type="hidden" name="rate" id="rate" value="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="comment">التعليق (اختياري)</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التقييم</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // تحديد النجوم للتقييم
            $('.rating i').click(function() {
                const value = $(this).data('value');
                $('#rate').val(value);

                // تحديث عرض النجوم
                $('.rating i').each(function(index) {
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
