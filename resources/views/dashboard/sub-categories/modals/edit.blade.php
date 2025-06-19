<div class="modal fade" id="editSubCategoryModal{{ $subCategory->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editSubCategoryModalLabel{{ $subCategory->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSubCategoryModalLabel{{ $subCategory->id }}">تعديل القسم الفرعي</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('sub-categories.update', $subCategory->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_id">القسم الرئيسي</label>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $subCategory->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">اسم القسم الفرعي</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $subCategory->name }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">صورة القسم الفرعي</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image" name="image">
                                    <label class="custom-file-label" for="image">اختر صورة</label>
                                </div>
                                @if ($subCategory->image)
                                    <small class="form-text text-muted">
                                        الصورة الحالية: <a href="{{ asset('storage/' . $subCategory->image) }}"
                                            target="_blank">عرض</a>
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="type">نوع الحجز</label>
                        <select class="form-control" id="type" name="type">
                            <option value="daily" {{ $subCategory->type == 'daily' ? 'selected' : '' }}>يومي</option>
                            <option value="periods" {{ $subCategory->type == 'periods' ? 'selected' : '' }}>مدة
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">الوصف</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ $subCategory->description }}</textarea>
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
