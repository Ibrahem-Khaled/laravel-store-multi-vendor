<div class="modal fade" id="editBrandModal{{ $brand->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editBrandModalLabel{{ $brand->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editBrandModalLabel{{ $brand->id }}">تعديل العلامة التجارية</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_id">المستخدم *</label>
                                <select class="form-control" id="user_id" name="user_id" required>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ $brand->user_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">اسم العلامة التجارية *</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $brand->name }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">وصف العلامة التجارية (اختياري)</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ $brand->description }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">شعار العلامة التجارية</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image" name="image">
                                    <label class="custom-file-label" for="image">اختر صورة</label>
                                </div>
                                <small class="form-text text-muted">اتركه فارغاً إذا لم ترغب في تغيير الصورة</small>
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}"
                                        class="img-thumbnail" width="150">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="link">رابط الموقع (اختياري)</label>
                                <input type="url" class="form-control" id="link" name="link"
                                    placeholder="https://example.com" value="{{ $brand->link }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="order">ترتيب العرض</label>
                                <input type="number" class="form-control" id="order" name="order"
                                    value="{{ $brand->order }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="latitude">خط العرض (اختياري)</label>
                                <input type="number" class="form-control" id="latitude" name="latitude"
                                    step="0.000001" min="-90" max="90" value="{{ $brand->latitude }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="longitude">خط الطول (اختياري)</label>
                                <input type="number" class="form-control" id="longitude" name="longitude"
                                    step="0.000001" min="-180" max="180" value="{{ $brand->longitude }}">
                            </div>
                        </div>
                    </div>

                    {{-- <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                {{ $brand->is_active ? 'checked' : '' }}>
                            <label class="custom-control-label" for="is_active">الحالة (نشط/غير نشط)</label>
                        </div>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>
