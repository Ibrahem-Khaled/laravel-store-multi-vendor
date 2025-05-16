<div class="modal fade" id="showBrandModal{{ $brand->id }}" tabindex="-1" role="dialog"
    aria-labelledby="showBrandModalLabel{{ $brand->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showBrandModalLabel{{ $brand->id }}">تفاصيل العلامة التجارية</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="{{ asset('storage/' . $brand->image) }}" alt="{{ $brand->name }}"
                            class="img-fluid rounded mb-3">
                    </div>
                    <div class="col-md-8">
                        <h3>{{ $brand->name }}</h3>
                        <p class="text-muted">المستخدم: {{ $brand->user->name }}</p>
                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <h5>الرابط:</h5>
                                <p>
                                    @if ($brand->link)
                                        <a href="{{ $brand->link }}" target="_blank">{{ $brand->link }}</a>
                                    @else
                                        <span class="text-muted">لا يوجد</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h5>الترتيب:</h5>
                                <p>{{ $brand->order }}</p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h5>الحالة:</h5>
                                <p>
                                    <span class="badge badge-{{ $brand->is_active ? 'success' : 'secondary' }}">
                                        {{ $brand->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h5>الموقع الجغرافي:</h5>
                                <p>
                                    @if ($brand->latitude && $brand->longitude)
                                        <a href="https://www.google.com/maps?q={{ $brand->latitude }},{{ $brand->longitude }}"
                                            target="_blank">
                                            عرض على الخريطة
                                        </a>
                                    @else
                                        <span class="text-muted">غير محدد</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <h5>الوصف:</h5>
                        <p>{{ $brand->description ?? 'لا يوجد وصف' }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
