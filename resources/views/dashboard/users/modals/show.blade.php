<div class="modal fade" id="showUserModal{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">تفاصيل المستخدم</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="d-flex align-items-center mb-3">
                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('img/default-avatar.png') }}"
                        class="rounded-circle mr-3" width="60" height="60" alt="{{ $user->name }}">
                    <div>
                        <strong>{{ $user->name }}</strong>
                        <div class="text-muted">{{ '@' . $user->username }}</div>
                        <div class="small">UUID: {{ $user->uuid }}</div>
                    </div>
                </div>

                <ul class="list-unstyled mb-0">
                    <li><i class="fas fa-envelope"></i> {{ $user->email }}</li>
                    <li><i class="fas fa-phone"></i> {{ $user->phone ?? '-' }}</li>
                    <li><i class="fas fa-user-tag"></i> {{ $roleNames[$user->role] ?? $user->role }}</li>
                    <li><i class="fas fa-flag"></i> {{ $user->country ?? '-' }}</li>
                    <li><i class="fas fa-map-marker-alt"></i> {{ $user->address ?? '-' }}</li>
                    <li><i class="fas fa-check-circle"></i> موثق: {{ $user->is_verified ? 'نعم' : 'لا' }}</li>
                    <li><i class="fas fa-coins"></i> Coins: {{ $user->coins }}</li>
                </ul>
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>

        </div>
    </div>
</div>
