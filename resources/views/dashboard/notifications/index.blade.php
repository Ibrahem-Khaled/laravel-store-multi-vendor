@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        {{-- عنوان الصفحة ومسار التنقل --}}
        <div class="row">
            <div class="col-12">
                <h1 class="h3 mb-0 text-gray-800">إدارة الإشعارات</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">لوحة التحكم</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">الإشعارات</li>
                    </ol>
                </nav>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات الإشعارات --}}
        <div class="row mb-4">
            {{-- إجمالي الإشعارات --}}
            <x-stat-card icon="fas fa-bell" title="إجمالي الإشعارات" :value="$totalNotifications" color="primary" />
            {{-- الإشعارات المقروءة --}}
            <x-stat-card icon="fas fa-check-circle" title="المقروءة" :value="$readNotifications" color="success" />
            {{-- الإشعارات غير المقروءة --}}
            <x-stat-card icon="fas fa-exclamation-circle" title="غير المقروءة" :value="$unreadNotifications" color="warning" />
            {{-- الإشعارات الحديثة --}}
            <x-stat-card icon="fas fa-clock" title="الإشعارات الحديثة" :value="$latestNotifications" color="info" />
        </div>

        {{-- بطاقة قائمة الإشعارات --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">قائمة الإشعارات</h6>
                <div>
                    <button class="btn btn-success mr-2" onclick="document.getElementById('markAllAsReadForm').submit()">
                        <i class="fas fa-check-double"></i> تعليم الكل كمقروء
                    </button>
                    <form id="markAllAsReadForm" action="{{ route('notifications.mark-all-read') }}" method="POST"
                        class="d-none">
                        @csrf
                    </form>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#createNotificationModal">
                        <i class="fas fa-plus"></i> إرسال إشعار
                    </button>
                </div>
            </div>
            <div class="card-body">
                {{-- تبويب حالة الإشعارات --}}
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link {{ !request('status') ? 'active' : '' }}"
                            href="{{ route('notifications.index') }}">الكل</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'read' ? 'active' : '' }}"
                            href="{{ route('notifications.index', ['status' => 'read']) }}">المقروءة</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request('status') === 'unread' ? 'active' : '' }}"
                            href="{{ route('notifications.index', ['status' => 'unread']) }}">غير المقروءة</a>
                    </li>
                </ul>

                {{-- فلترة الإشعارات --}}
                <form action="{{ route('notifications.index') }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <select name="user_id" class="form-control">
                                <option value="">كل المستخدمين</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control"
                                placeholder="ابحث في عنوان أو محتوى الإشعار..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary btn-block" type="submit">
                                <i class="fas fa-filter"></i> تصفية
                            </button>
                        </div>
                    </div>
                </form>

                {{-- جدول الإشعارات --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>المستخدم</th>
                                <th>العنوان</th>
                                <th>المحتوى</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notifications as $notification)
                                <tr class="{{ $notification->is_read ? '' : 'font-weight-bold' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($notification->user)
                                            {{ $notification->user->name }}
                                        @else
                                            <span class="text-muted">كل المستخدمين</span>
                                        @endif
                                    </td>
                                    <td>{{ Str::limit($notification->title, 30) }}</td>
                                    <td>{{ Str::limit($notification->body, 50) }}</td>
                                    <td>
                                        @if ($notification->is_read)
                                            <span class="badge badge-success">مقروء</span>
                                        @else
                                            <span class="badge badge-warning">غير مقروء</span>
                                        @endif
                                    </td>
                                    <td>{{ $notification->created_at->diffForHumans() }}</td>
                                    <td>
                                        {{-- زر عرض --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-info" data-toggle="modal"
                                            data-target="#showNotificationModal{{ $notification->id }}" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        {{-- زر تعديل --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-primary" data-toggle="modal"
                                            data-target="#editNotificationModal{{ $notification->id }}" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- زر حذف --}}
                                        <button type="button" class="btn btn-sm btn-circle btn-danger" data-toggle="modal"
                                            data-target="#deleteNotificationModal{{ $notification->id }}" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        {{-- زر تعليم كمقروء/غير مقروء --}}
                                        @if ($notification->is_read)
                                            <form action="{{ route('notifications.mark-as-unread', $notification->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-circle btn-warning"
                                                    title="تعليم كغير مقروء">
                                                    <i class="fas fa-envelope"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('notifications.mark-as-read', $notification->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-circle btn-success"
                                                    title="تعليم كمقروء">
                                                    <i class="fas fa-envelope-open"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- تضمين المودالات لكل إشعار --}}
                                        @include('dashboard.notifications.modals.show', [
                                            'notification' => $notification,
                                        ])
                                        @include('dashboard.notifications.modals.edit', [
                                            'notification' => $notification,
                                        ])
                                        @include('dashboard.notifications.modals.delete', [
                                            'notification' => $notification,
                                        ])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">لا يوجد إشعارات</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center">
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- مودال إرسال إشعار (ثابت) --}}
    @include('dashboard.notifications.modals.create')
@endsection

@push('scripts')
    <script>
        // تفعيل التولتيب الافتراضي
        $('[data-toggle="tooltip"]').tooltip();
    </script>
@endpush
