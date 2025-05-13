<div class="modal fade" id="deleteProductModal{{ $product->id }}" tabindex="-1" role="dialog"
    aria-labelledby="deleteProductModalLabel{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel{{ $product->id }}">تأكيد الحذف</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>هل أنت متأكد من رغبتك في حذف المنتج <strong>{{ $product->name }}</strong>؟</p>
                <p class="text-danger">هذا الإجراء سيحذف جميع الصور المرتبطة ولا يمكن التراجع عنه.</p>

                @if ($product->images->count() > 0)
                    <div class="alert alert-warning">
                        <h6>الصور المرتبطة:</h6>
                        <div class="d-flex flex-wrap">
                            @foreach ($product->images as $image)
                                <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $product->name }}"
                                    class="img-thumbnail m-1" width="80">
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>
