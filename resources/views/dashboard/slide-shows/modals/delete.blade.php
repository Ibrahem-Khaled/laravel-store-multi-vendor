<div class="modal fade" id="deleteSlideModal{{ $slide->id }}" tabindex="-1" role="dialog"
    aria-labelledby="deleteSlideModalLabel{{ $slide->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteSlideModalLabel{{ $slide->id }}">تأكيد الحذف</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من رغبتك في حذف الشريحة <strong>{{ $slide->title ?? 'بدون عنوان' }}</strong>؟</p>
                <p class="text-danger">هذا الإجراء لا يمكن التراجع عنه.</p>
                <div class="text-center">
                    <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->title }}" class="img-thumbnail"
                        width="200">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <form action="{{ route('slide-shows.destroy', $slide->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>
