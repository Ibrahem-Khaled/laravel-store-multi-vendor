<div class="modal fade" id="editSlideModal{{ $slide->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editSlideModalLabel{{ $slide->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSlideModalLabel{{ $slide->id }}">تعديل الشريحة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('slide-shows.update', $slide->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">العنوان (اختياري)</label>
                                <input type="text" class="form-control" id="title" name="title"
                                    value="{{ $slide->title }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="order">الترتيب</label>
                                <input type="number" class="form-control" id="order" name="order"
                                    value="{{ $slide->order }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">الوصف (اختياري)</label>
                        <textarea class="form-control" id="description" name="description" rows="2">{{ $slide->description }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">صورة الشريحة</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image" name="image">
                                    <label class="custom-file-label" for="image">اختر صورة</label>
                                </div>
                                <small class="form-text text-muted">اتركه فارغاً إذا لم ترغب في تغيير الصورة</small>
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->title }}"
                                        class="img-thumbnail" width="150">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="link">رابط (اختياري)</label>
                                <input type="url" class="form-control" id="link" name="link"
                                    placeholder="https://example.com" value="{{ $slide->link }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                {{ $slide->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">الحالة (نشط/غير نشط)</label>
                        </div>
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
