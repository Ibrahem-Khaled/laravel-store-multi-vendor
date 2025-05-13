<div class="modal fade" id="restoreCategoryModal{{ $category->id }}" tabindex="-1" role="dialog"
    aria-labelledby="restoreCategoryModalLabel{{ $category->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreCategoryModalLabel{{ $category->id }}">استعادة الفئة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('categories.restore', $category->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>هل أنت متأكد من استعادة الفئة <strong>{{ $category->name }}</strong>؟</p>
                    <p class="text-muted">سيتم إعادة الفئة إلى قائمة الفئات النشطة.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-trash-restore"></i> استعادة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
