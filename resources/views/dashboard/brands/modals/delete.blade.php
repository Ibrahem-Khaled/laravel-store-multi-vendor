<div class="modal fade" id="deleteBrandModal{{ $brand->id }}" tabindex="-1" role="dialog"
    aria-labelledby="deleteBrandModalLabel{{ $brand->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteBrandModalLabel{{ $brand->id }}">تأكيد الحذف</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من رغبتك في حذف العلامة التجارية <strong>{{ $brand->name }}</strong>؟</p>
                <p class="text-danger">هذا الإجراء لا يمكن التراجع عنه.</p>
                <div class="text-center">
                    <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}" class="img-thumbnail"
                        width="200">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <form action="{{ route('brands.destroy', $brand->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>
