<div class="modal fade" id="editNotificationModal{{ $notification->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editNotificationModalLabel{{ $notification->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editNotificationModalLabel{{ $notification->id }}">تعديل الإشعار</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('notifications.update', $notification->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user_id{{ $notification->id }}">المستخدم</label>
                        <select class="form-control" id="user_id{{ $notification->id }}" name="user_id">
                            <option value="">إرسال لجميع المستخدمين</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ $notification->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title{{ $notification->id }}">عنوان الإشعار</label>
                        <input type="text" class="form-control" id="title{{ $notification->id }}" name="title"
                            value="{{ $notification->title }}" required>
                    </div>
                    <div class="form-group">
                        <label for="body{{ $notification->id }}">محتوى الإشعار</label>
                        <textarea class="form-control" id="body{{ $notification->id }}" name="body" rows="5" required>{{ $notification->body }}</textarea>
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
