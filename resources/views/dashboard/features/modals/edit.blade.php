<div class="modal fade" id="editFeatureModal{{ $feature->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editFeatureModalLabel{{ $feature->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFeatureModalLabel{{ $feature->id }}">تعديل الميزة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('features.update', $feature->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name{{ $feature->id }}">اسم الميزة</label>
                        <input type="text" class="form-control" id="name{{ $feature->id }}" name="name"
                            value="{{ $feature->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="category_id{{ $feature->id }}">التصنيف</label>
                        <select class="form-control" id="category_id{{ $feature->id }}" name="category_id">
                            <option value="">بدون تصنيف</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $feature->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
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
