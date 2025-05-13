<div class="modal fade" id="showSlideModal{{ $slide->id }}" tabindex="-1" role="dialog"
    aria-labelledby="showSlideModalLabel{{ $slide->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showSlideModalLabel{{ $slide->id }}">معاينة الشريحة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img src="{{ asset('storage/' . $slide->image) }}" alt="{{ $slide->title }}"
                        class="img-fluid rounded" style="max-height: 400px;">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h5>العنوان:</h5>
                        <p>{{ $slide->title ?? 'بدون عنوان' }}</p>
                    </div>
                    <div class="col-md-6">
                        <h5>الترتيب:</h5>
                        <p>{{ $slide->order }}</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h5>الحالة:</h5>
                        <p>
                            <span class="badge badge-{{ $slide->is_active ? 'success' : 'secondary' }}">
                                {{ $slide->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5>الرابط:</h5>
                        <p>
                            @if ($slide->link)
                                <a href="{{ $slide->link }}" target="_blank">{{ $slide->link }}</a>
                            @else
                                <span class="text-muted">لا يوجد</span>
                            @endif
                        </p>
                    </div>
                </div>

                <h5>الوصف:</h5>
                <p>{{ $slide->description ?? 'لا يوجد وصف' }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
