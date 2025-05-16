<div class="modal fade" id="createProductModal" tabindex="-1" role="dialog" aria-labelledby="createProductModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProductModalLabel">إضافة منتج جديد</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sub_category_id">القسم الفرعي *</label>
                                <select class="form-control" id="sub_category_id" name="sub_category_id" required>
                                    <option value="">اختر القسم الفرعي</option>
                                    @foreach ($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}">{{ $subCategory->name }}
                                            ({{ $subCategory->category->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">اسم المنتج *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="brand_id">البراند *</label>
                                <select class="form-control" id="brand_id" name="brand_id">
                                    <option value="">اختر البراند</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="city_id">المدينة *</label>
                                <select class="form-control" id="city_id" name="city_id" required>
                                    <option value="">اختر المدينة</option>
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="neighborhood_id">الحي *</label>
                                <select class="form-control" id="neighborhood_id" name="neighborhood_id" required>
                                    <option value="">اختر الحي</option>
                                    @foreach ($neighborhoods as $neighborhood)
                                        <option value="{{ $neighborhood->id }}">{{ $neighborhood->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="price">السعر *</label>
                                <input type="number" class="form-control" id="price" name="price" step="0.01"
                                    min="0" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="discount_percent">نسبة الخصم %</label>
                                <input readonly type="number" class="form-control" id="discount_percent"
                                    name="discount_percent" min="0" max="100" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="price_after_discount">السعر بعد الخصم </label>
                                <input type="text" class="form-control" id="price_after_discount" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="video_url">رابط الفيديو (اختياري)</label>
                                <input type="url" class="form-control" id="video_url" name="video_url"
                                    placeholder="https://youtube.com/...">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="latitude">خط العرض (اختياري)</label>
                                <input type="number" class="form-control" id="latitude" name="latitude"
                                    step="0.000001" min="-90" max="90">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="longitude">خط الطول (اختياري)</label>
                                <input type="number" class="form-control" id="longitude" name="longitude"
                                    step="0.000001" min="-180" max="180">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">وصف المنتج (اختياري)</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="images">صور المنتج (يمكن اختيار أكثر من صورة)</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="images" name="images[]" multiple>
                            <label class="custom-file-label" for="images">اختر الصور</label>
                        </div>
                        <small class="form-text text-muted">الحجم الأقصى للصورة: 5MB - الصيغ المسموحة:
                            jpeg,png,jpg,gif</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
