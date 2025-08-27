<div class="modal fade" id="rejectRequestModal{{ $request->id }}" tabindex="-1" role="dialog"
    aria-labelledby="rejectModalLabel{{ $request->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel{{ $request->id }}">تأكيد الرفض</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('role-requests.update', $request) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="rejected">
                <div class="modal-body">
                    <p>هل أنت متأكد من رفض طلب <strong>{{ $request->user->name }}</strong>؟</p>
                    <div class="form-group">
                        <label for="admin_notes_reject_{{ $request->id }}">سبب الرفض (مهم لإعلام المستخدم):</label>
                        <textarea class="form-control" name="admin_notes" id="admin_notes_reject_{{ $request->id }}" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">تأكيد الرفض</button>
                </div>
            </form>
        </div>
    </div>
</div>
