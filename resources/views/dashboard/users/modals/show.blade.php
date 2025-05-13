<div class="modal fade" id="showUserModal{{ $user->id }}" tabindex="-1" role="dialog"
    aria-labelledby="showUserModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showUserModalLabel{{ $user->id }}">تفاصيل المستخدم</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('img/default-avatar.png') }}"
                            alt="{{ $user->name }}" class="img-fluid rounded-circle mb-3" width="150">
                        <h4>{{ $user->name }}</h4>
                        <span
                            class="badge badge-{{ $user->status == 'active' ? 'success' : ($user->status == 'inactive' ? 'secondary' : 'danger') }}">
                            {{ $user->status == 'active' ? 'نشط' : ($user->status == 'inactive' ? 'غير نشط' : 'محظور') }}
                        </span>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>اسم المستخدم:</h6>
                                <p>{{ $user->username }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>البريد الإلكتروني:</h6>
                                <p>{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>الهاتف:</h6>
                                <p>{{ $user->phone ?? 'غير محدد' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>الدور:</h6>
                                <p>
                                    @php
                                        $roleNames = [
                                            'admin' => 'مدير',
                                            'moderator' => 'مشرف',
                                            'user' => 'مستخدم',
                                            'trader' => 'تاجر',
                                        ];
                                    @endphp
                                    {{ $roleNames[$user->role] ?? $user->role }}
                                </p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>الجنس:</h6>
                                <p>{{ $user->gender == 'male' ? 'ذكر' : 'أنثى' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>تاريخ الميلاد:</h6>
                                <p>{{ $user?->birth_date ? $user?->birth_date?->format('Y-m-d') : 'غير محدد' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>العنوان:</h6>
                                <p>{{ $user->address ?? 'غير محدد' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>الدولة:</h6>
                                <p>{{ $user->country ?? 'غير محدد' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h6>النبذة:</h6>
                                <p>{{ $user->bio ?? 'لا يوجد وصف' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>تاريخ الإنشاء:</h6>
                                <p>{{ $user?->created_at?->format('Y-m-d H:i') }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>آخر تحديث:</h6>
                                <p>{{ $user?->updated_at?->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
