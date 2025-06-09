<div class="modal fade" id="showFeatureModal{{ $feature->id }}" tabindex="-1" role="dialog"
    aria-labelledby="showFeatureModalLabel{{ $feature->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showFeatureModalLabel{{ $feature->id }}">تفاصيل الميزة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">اسم الميزة:</div>
                    <div class="col-md-8">{{ $feature->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">التصنيف:</div>
                    <div class="col-md-8">
                        @if ($feature->category)
                            <span class="badge badge-primary">{{ $feature->category->name }}</span>
                        @else
                            <span class="badge badge-secondary">بدون تصنيف</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">تاريخ الإضافة:</div>
                    <div class="col-md-8">{{ $feature->created_at->format('Y-m-d H:i') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">آخر تحديث:</div>
                    <div class="col-md-8">{{ $feature->updated_at->format('Y-m-d H:i') }}</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
