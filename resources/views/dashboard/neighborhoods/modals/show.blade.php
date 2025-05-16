<div class="modal fade" id="showNeighborhoodModal{{ $neighborhood->id }}" tabindex="-1" role="dialog"
    aria-labelledby="showNeighborhoodModalLabel{{ $neighborhood->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showNeighborhoodModalLabel{{ $neighborhood->id }}">تفاصيل الحي</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>اسم الحي:</strong></p>
                        <p>{{ $neighborhood->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>المدينة:</strong></p>
                        <p>{{ $neighborhood->city->name }}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p><strong>الحالة:</strong></p>
                        <span class="badge badge-{{ $neighborhood->active ? 'success' : 'danger' }}">
                            {{ $neighborhood->status }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <p><strong>تاريخ الإنشاء:</strong></p>
                        <p>{{ $neighborhood->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <p><strong>آخر تحديث:</strong></p>
                        <p>{{ $neighborhood->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
