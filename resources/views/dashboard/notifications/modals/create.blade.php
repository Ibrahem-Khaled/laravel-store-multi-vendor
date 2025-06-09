<div class="modal fade" id="createNotificationModal" tabindex="-1" role="dialog"
    aria-labelledby="createNotificationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createNotificationModalLabel">إرسال إشعار جديد</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('notifications.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user_id">المستخدم (اختياري)</label>
                        <select class="form-control" id="user_id" name="user_id">
                            <option value="">إرسال لجميع المستخدمين</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">اترك الحقل فارغاً لإرسال الإشعار لجميع المستخدمين</small>
                    </div>
                    <div class="form-group">
                        <label for="title">عنوان الإشعار</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="body">محتوى الإشعار</label>
                        <textarea class="form-control" id="body" name="body" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إرسال الإشعار</button>
                </div>
            </form>
        </div>
    </div>
</div>
