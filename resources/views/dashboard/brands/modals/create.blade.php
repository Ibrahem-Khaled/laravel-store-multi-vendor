<div class="modal fade" id="createBrandModal" tabindex="-1" role="dialog" aria-labelledby="createBrandModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createBrandModalLabel">إضافة علامة تجارية جديدة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_id">المستخدم *</label>
                                <select class="form-control" id="user_id" name="user_id" required>
                                    <option value="">اختر المستخدم</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">اسم العلامة التجارية *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">وصف العلامة التجارية (اختياري)</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">شعار العلامة التجارية *</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="image" name="image"
                                        required>
                                    <label class="custom-file-label" for="image">اختر صورة</label>
                                </div>
                                <small class="form-text text-muted">الحجم الأقصى: 5MB - الصيغ المسموحة:
                                    jpeg,png,jpg,gif</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="link">رابط الموقع (اختياري)</label>
                                <input type="url" class="form-control" id="link" name="link"
                                    placeholder="https://example.com">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="order">ترتيب العرض</label>
                                <input type="number" class="form-control" id="order" name="order" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="latitude">خط العرض (اختياري)</label>
                                <input type="number" class="form-control" id="latitude" name="latitude"
                                    step="0.000001" min="-90" max="90">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="longitude">خط الطول (اختياري)</label>
                                <input type="number" class="form-control" id="longitude" name="longitude"
                                    step="0.000001" min="-180" max="180">
                            </div>
                        </div>
                    </div>

                    {{-- <div class="form-group">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" checked>
                            <label class="custom-control-label" for="is_active">الحالة (نشط/غير نشط)</label>
                        </div>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
