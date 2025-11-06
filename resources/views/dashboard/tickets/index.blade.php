@extends('layouts.app')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ticket-card {
            border-left: 4px solid;
            transition: all 0.3s ease;
        }

        .ticket-card.pending { border-left-color: #ffc107; }
        .ticket-card.open { border-left-color: #17a2b8; }
        .ticket-card.in_progress { border-left-color: #007bff; }
        .ticket-card.resolved { border-left-color: #28a745; }
        .ticket-card.closed { border-left-color: #6c757d; }

        .priority-badge {
            font-weight: 600;
            padding: 0.35em 0.65em;
            font-size: 0.85rem;
        }

        .chat-message {
            max-width: 75%;
            margin-bottom: 1.5rem;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message-user {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 18px 18px 4px 18px;
            padding: 1rem 1.25rem;
            margin-left: auto;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .message-support {
            background: #f8f9fc;
            color: #2d3748;
            border-radius: 18px 18px 18px 4px;
            padding: 1rem 1.25rem;
            border: 1px solid #e3e6f0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .timeline-item {
            position: relative;
            padding-right: 2rem;
            padding-bottom: 2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            right: 8px;
            top: 0;
            bottom: -2rem;
            width: 2px;
            background: #e3e6f0;
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-icon {
            position: absolute;
            right: 0;
            top: 0;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #4e73df;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #e3e6f0;
        }

        .filter-section {
            background: #f8f9fc;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .attachment-preview {
            max-width: 200px;
            border-radius: 8px;
            border: 2px solid #e3e6f0;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid" dir="rtl">
        {{-- عنوان الصفحة --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">
                            <i class="fas fa-ticket-alt mr-2"></i>
                            إدارة التذاكر
                        </h1>
                        <nav aria-label="breadcrumb" class="mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}">
                                        <i class="fas fa-home"></i> لوحة التحكم
                                    </a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('help-center.index') }}">مركز المساعدة</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">التذاكر</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('help-center.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-right"></i> العودة
                    </a>
                </div>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات سريعة --}}
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    إجمالي التذاكر
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    قيد الانتظار
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    مفتوحة
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['open'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-envelope-open fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    تم الحل
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['resolved'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- بطاقة التذاكر --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-list mr-2"></i>
                    قائمة التذاكر
                </h6>
            </div>

            <div class="card-body">
                {{-- فلترة --}}
                <div class="filter-section">
                    <form action="{{ route('tickets.index') }}" method="GET" class="form-inline">
                        <div class="form-group mr-2 mb-2 flex-grow-1">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" name="search" class="form-control"
                                       placeholder="ابحث برقم التذكرة، الموضوع أو اسم المستخدم..."
                                       value="{{ $search }}">
                            </div>
                        </div>

                        <div class="form-group mr-2 mb-2">
                            <select name="status" class="form-control">
                                <option value="all">جميع الحالات</option>
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                <option value="open" {{ $status === 'open' ? 'selected' : '' }}>مفتوحة</option>
                                <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                                <option value="resolved" {{ $status === 'resolved' ? 'selected' : '' }}>تم الحل</option>
                                <option value="closed" {{ $status === 'closed' ? 'selected' : '' }}>مغلقة</option>
                            </select>
                        </div>

                        <div class="form-group mr-2 mb-2">
                            <select name="priority" class="form-control">
                                <option value="all">جميع الأولويات</option>
                                <option value="low" {{ $priority === 'low' ? 'selected' : '' }}>منخفضة</option>
                                <option value="medium" {{ $priority === 'medium' ? 'selected' : '' }}>متوسطة</option>
                                <option value="high" {{ $priority === 'high' ? 'selected' : '' }}>عالية</option>
                                <option value="urgent" {{ $priority === 'urgent' ? 'selected' : '' }}>عاجلة</option>
                            </select>
                        </div>

                        <div class="form-group mr-2 mb-2">
                            <select name="category" class="form-control">
                                <option value="all">جميع الفئات</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary mb-2">
                            <i class="fas fa-filter"></i> بحث
                        </button>

                        @if($search || $status !== 'all' || $priority !== 'all' || $category !== 'all')
                            <a href="{{ route('tickets.index') }}" class="btn btn-secondary mb-2">
                                <i class="fas fa-times"></i> إلغاء
                            </a>
                        @endif
                    </form>
                </div>

                {{-- جدول التذاكر --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th width="10%">رقم التذكرة</th>
                                <th width="15%">المستخدم</th>
                                <th width="20%">الموضوع</th>
                                <th width="10%">الفئة</th>
                                <th width="10%">الحالة</th>
                                <th width="10%">الأولوية</th>
                                <th width="15%">التاريخ</th>
                                <th width="10%">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                                <tr>
                                    <td>
                                        <strong class="text-primary">#{{ $ticket->ticket_number }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $ticket->user->avatar ? asset('storage/' . $ticket->user->avatar) : asset('img/default-avatar.png') }}"
                                                 alt="{{ $ticket->user->name }}" 
                                                 class="rounded-circle mr-2"
                                                 style="width: 32px; height: 32px; object-fit: cover;">
                                            <div>
                                                <div class="font-weight-bold">{{ $ticket->user->name }}</div>
                                                <small class="text-muted">{{ $ticket->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <strong>{{ Str::limit($ticket->subject, 50) }}</strong>
                                        @if($ticket->attachment)
                                            <br><small class="text-muted"><i class="fas fa-paperclip"></i> مرفق</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-info">
                                            <i class="{{ $ticket->category->icon ?? 'fas fa-tag' }}"></i>
                                            {{ $ticket->category->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $ticket->status_color }}">
                                            {{ $ticket->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge priority-badge badge-{{ $ticket->priority_color }}">
                                            {{ $ticket->priority_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div>{{ $ticket->created_at->format('Y-m-d') }}</div>
                                            <div class="text-muted">{{ $ticket->created_at->format('H:i') }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('tickets.show', $ticket) }}" 
                                               class="btn btn-sm btn-info btn-action" 
                                               title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                        <p class="text-gray-500">لا توجد تذاكر</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- الترقيم --}}
                <div class="d-flex justify-content-center mt-4">
                    {{ $tickets->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endpush

