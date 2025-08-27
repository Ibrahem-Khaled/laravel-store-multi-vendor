<div class="modal fade" id="approveRequestModal{{ $request->id }}" tabindex="-1" role="dialog"
    aria-labelledby="approveModalLabel{{ $request->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel{{ $request->id }}">تأكيد الموافقة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('role-requests.update', $request) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="approved">
                <div class="modal-body">
                    <p>هل أنت متأكد من الموافقة على طلب <strong>{{ $request->user->name }}</strong> ليصبح
                        <strong>{{ $request->requested_role }}</strong>؟</p>
                    <div class="form-group">
                        <label for="admin_notes_approve_{{ $request->id }}">ملاحظات (اختياري):</label>
                        <textarea class="form-control" name="admin_notes" id="admin_notes_approve_{{ $request->id }}" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">تأكيد الموافقة</button>
                </div>
            </form>
        </div>
    </div>
</div>
