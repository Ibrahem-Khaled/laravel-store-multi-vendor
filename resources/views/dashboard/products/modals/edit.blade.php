<div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1" role="dialog"
    aria-labelledby="editProductModalLabel{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel{{ $product->id }}">تعديل المنتج</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sub_category_id">القسم الفرعي *</label>
                                <select class="form-control" id="sub_category_id" name="sub_category_id" required>
                                    @foreach ($subCategories as $subCategory)
                                        <option value="{{ $subCategory->id }}"
                                            {{ $product->sub_category_id == $subCategory->id ? 'selected' : '' }}>
                                            {{ $subCategory->name }} ({{ $subCategory->category->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">اسم المنتج *</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="{{ $product->name }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="city">المدينة *</label>
                                <input type="text" class="form-control" id="city" name="city"
                                    value="{{ $product->city }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="neighborhood">الحي *</label>
                                <input type="text" class="form-control" id="neighborhood" name="neighborhood"
                                    value="{{ $product->neighborhood }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="price">السعر (ر.س) *</label>
                                <input type="number" class="form-control" id="price" name="price" step="0.01"
                                    min="0" value="{{ $product->price }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="discount_percent">نسبة الخصم %</label>
                                <input type="number" class="form-control" id="discount_percent" name="discount_percent"
                                    min="0" max="100" value="{{ $product->discount_percent }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="price_after_discount">السعر بعد الخصم (ر.س)</label>
                                <input type="text" class="form-control" id="price_after_discount"
                                    value="{{ number_format($product->price_after_discount, 2) }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="video_url">رابط الفيديو (اختياري)</label>
                                <input type="url" class="form-control" id="video_url" name="video_url"
                                    value="{{ $product->video_url }}" placeholder="https://youtube.com/...">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="latitude">خط العرض (اختياري)</label>
                                <input type="number" class="form-control" id="latitude" name="latitude"
                                    step="0.000001" min="-90" max="90" value="{{ $product->latitude }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="longitude">خط الطول (اختياري)</label>
                                <input type="number" class="form-control" id="longitude" name="longitude"
                                    step="0.000001" min="-180" max="180"
                                    value="{{ $product->longitude }}">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">وصف المنتج (اختياري)</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ $product->description }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>الصور الحالية</label>
                        <div class="d-flex flex-wrap">
                            @foreach ($product->images as $image)
                                <div class="position-relative m-2">
                                    <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $product->name }}"
                                        class="img-thumbnail" width="100">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                        onclick="event.preventDefault(); document.getElementById('delete-image-{{ $image->id }}').submit();">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <form id="delete-image-{{ $image->id }}"
                                        action="{{ route('products.destroy-image', $image->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="images">إضافة صور جديدة (اختياري)</label>
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
                    <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
                </div>
            </form>
        </div>
    </div>
</div>
