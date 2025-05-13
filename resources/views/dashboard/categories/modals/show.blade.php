<div class="modal fade" id="showCategoryModal{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="showCategoryModalLabel{{ $category->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showCategoryModalLabel{{ $category->id }}">تفاصيل التصنيف</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" 
                                 alt="{{ $category->name }}" 
                                 class="img-fluid rounded mb-3">
                        @else
                            <div class="no-image bg-light d-flex align-items-center justify-content-center" 
                                 style="width:100%; height:200px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <h3>{{ $category->name }}</h3>
                        <hr>
                        <h5>الوصف:</h5>
                        <p>{{ $category->description ?? 'لا يوجد وصف' }}</p>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>تاريخ الإنشاء:</strong> {{ $category->created_at->format('Y-m-d') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>آخر تحديث:</strong> {{ $category->updated_at->format('Y-m-d') }}</p>
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