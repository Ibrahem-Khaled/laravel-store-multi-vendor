<div class="modal fade" id="showRequestModal{{ $request->id }}" tabindex="-1" role="dialog"
    aria-labelledby="showRequestModalLabel{{ $request->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showRequestModalLabel{{ $request->id }}">تفاصيل طلب:
                    {{ $request->user->name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>المعلومات الشخصية</h5>
                        <hr>
                        <p><strong>الاسم الكامل:</strong> {{ $request->full_name ?? '-' }}</p>
                        <p><strong>رقم الهوية الوطنية:</strong> {{ $request->national_id_number ?? '-' }}</p>
                        <p><strong>صورة الهوية:</strong>
                            @if ($request->national_id_image_path)
                                <a href="{{ asset('storage/' . $request->national_id_image_path) }}" target="_blank">عرض
                                    الصورة</a>
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5>معلومات النشاط التجاري</h5>
                        <hr>
                        <p><strong>اسم المتجر:</strong> {{ $request->store_name ?? '-' }}</p>
                        <p><strong>رقم السجل التجاري:</strong> {{ $request->commercial_registration_number ?? '-' }}</p>
                        <p><strong>صورة السجل التجاري:</strong>
                            @if ($request->commercial_registration_image_path)
                                <a href="{{ asset('storage/' . $request->commercial_registration_image_path) }}"
                                    target="_blank">عرض المستند</a>
                            @else
                                -
                            @endif
                        </p>
                        <p><strong>اسم البنك:</strong> {{ $request->bank_name ?? '-' }}</p>
                        <p><strong>رقم الحساب البنكي (IBAN):</strong> {{ $request->bank_account_number ?? '-' }}</p>
                    </div>
                </div>
                <hr>
                <h5>معلومات إضافية</h5>
                <p><strong>سبب الطلب:</strong></p>
                <p class="text-muted">{{ $request->reason ?? 'لم يتم تقديم سبب.' }}</p>

                @if ($request->admin_notes)
                    <hr>
                    <h5>ملاحظات المدير</h5>
                    <p class="text-muted">{{ $request->admin_notes }}</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
