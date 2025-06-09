<div class="modal fade" id="showNotificationModal{{ $notification->id }}" tabindex="-1" role="dialog"
    aria-labelledby="showNotificationModalLabel{{ $notification->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showNotificationModalLabel{{ $notification->id }}">تفاصيل الإشعار</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-3 font-weight-bold">المستخدم:</div>
                    <div class="col-md-9">
                        @if ($notification->user)
                            {{ $notification->user->name }}
                        @else
                            <span class="text-muted">كل المستخدمين</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3 font-weight-bold">العنوان:</div>
                    <div class="col-md-9">{{ $notification->title }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3 font-weight-bold">المحتوى:</div>
                    <div class="col-md-9">{{ $notification->body }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3 font-weight-bold">الحالة:</div>
                    <div class="col-md-9">
                        @if ($notification->is_read)
                            <span class="badge badge-success">مقروء</span>
                        @else
                            <span class="badge badge-warning">غير مقروء</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3 font-weight-bold">تاريخ الإرسال:</div>
                    <div class="col-md-9">{{ $notification->created_at->format('Y-m-d H:i') }}</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
