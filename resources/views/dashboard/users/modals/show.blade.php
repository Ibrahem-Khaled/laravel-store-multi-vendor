<div class="modal fade" id="showUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user mr-2"></i>
                    تفاصيل المستخدم
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('img/default-avatar.png') }}"
                             class="rounded-circle shadow" 
                             style="width: 120px; height: 120px; object-fit: cover; max-width: 120px; max-height: 120px;"
                             alt="{{ $user->name }}"
                             onerror="this.onerror=null; this.src='{{ asset('img/default-avatar.png') }}';">
                        <h5 class="mt-3 mb-0">{{ $user->name }}</h5>
                        <p class="text-muted mb-0">
                            <i class="fas fa-at"></i> {{ $user->username }}
                        </p>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">البريد الإلكتروني</label>
                                <div class="font-weight-bold">
                                    <i class="fas fa-envelope text-primary mr-2"></i>
                                    {{ $user->email ?? '-' }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">رقم الهاتف</label>
                                <div class="font-weight-bold">
                                    <i class="fas fa-phone text-success mr-2"></i>
                                    {{ $user->phone ?? '-' }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">الدور</label>
                                <div>
                                    @php
                                        $roleNames = [
                                            'admin' => 'مدير',
                                            'moderator' => 'مشرف',
                                            'user' => 'مستخدم',
                                            'trader' => 'متداول',
                                        ];
                                    @endphp
                                    <span class="badge badge-info">
                                        {{ $roleNames[$user->role] ?? $user->role }}
                                    </span>
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span class="badge badge-success">{{ $role->display_name }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">الحالة</label>
                                <div>
                                    @php
                                        $statusClasses = [
                                            'pending' => 'warning',
                                            'active' => 'success',
                                            'inactive' => 'secondary',
                                            'banned' => 'danger'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'قيد الاعتماد',
                                            'active' => 'نشط',
                                            'inactive' => 'غير نشط',
                                            'banned' => 'محظور'
                                        ];
                                    @endphp
                                    <span class="badge badge-{{ $statusClasses[$user->status] ?? 'light' }}">
                                        {{ $statusLabels[$user->status] ?? $user->status }}
                                    </span>
                                    @if($user->is_verified)
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle"></i> موثق
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">البلد</label>
                                <div class="font-weight-bold">
                                    <i class="fas fa-flag text-info mr-2"></i>
                                    {{ $user->country ?? '-' }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">العنوان</label>
                                <div class="font-weight-bold">
                                    <i class="fas fa-map-marker-alt text-danger mr-2"></i>
                                    {{ $user->address ?? '-' }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">الرصيد (Coins)</label>
                                <div class="font-weight-bold">
                                    <i class="fas fa-coins text-warning mr-2"></i>
                                    {{ number_format($user->coins ?? 0) }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">UUID</label>
                                <div class="font-weight-bold small">
                                    <i class="fas fa-fingerprint text-secondary mr-2"></i>
                                    {{ $user->uuid }}
                                </div>
                            </div>
                            @if($user->bio)
                                <div class="col-12 mb-3">
                                    <label class="text-muted small">نبذة</label>
                                    <div class="font-weight-bold">
                                        {{ $user->bio }}
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">تاريخ الإنشاء</label>
                                <div class="font-weight-bold">
                                    <i class="fas fa-calendar text-primary mr-2"></i>
                                    {{ $user->created_at->format('Y-m-d H:i') }}
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">آخر تحديث</label>
                                <div class="font-weight-bold">
                                    <i class="fas fa-clock text-secondary mr-2"></i>
                                    {{ $user->updated_at->format('Y-m-d H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                @if (!in_array($user->status, ['active', 'banned']))
                    <form action="{{ route('users.approve', $user) }}" method="POST" class="mr-auto d-inline">
                        @csrf
                        <button class="btn btn-success">
                            <i class="fas fa-check"></i> تفعيل
                        </button>
                    </form>
                @endif
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> إغلاق
                </button>
            </div>
        </div>
    </div>
</div>
