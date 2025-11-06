@extends('layouts.app')

@push('styles')
    <style>
        .help-center-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
            cursor: pointer;
        }

        .help-center-card:hover {
            border-color: #4e73df;
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .ticket-card {
            border-left: 4px solid;
            transition: all 0.3s ease;
        }

        .ticket-card:hover {
            transform: translateX(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
        }

        .message-support {
            background: #f8f9fc;
            color: #2d3748;
            border-radius: 18px 18px 18px 4px;
            padding: 1rem 1.25rem;
            border: 1px solid #e3e6f0;
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
                            <i class="fas fa-headset mr-2"></i>
                            مركز المساعدة والدعم الفني
                        </h1>
                        <nav aria-label="breadcrumb" class="mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}">
                                        <i class="fas fa-home"></i> لوحة التحكم
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">مركز المساعدة</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('tickets.index') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-ticket-alt"></i> إدارة التذاكر
                    </a>
                </div>
            </div>
        </div>

        @include('components.alerts')

        {{-- إحصائيات التذاكر --}}
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

        <div class="row">
            {{-- التذاكر الأخيرة --}}
            <div class="col-lg-8 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-list mr-2"></i>
                            التذاكر الأخيرة
                        </h6>
                        <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-primary">
                            عرض الكل
                        </a>
                    </div>
                    <div class="card-body">
                        @forelse($recentTickets as $ticket)
                            <div class="ticket-card card mb-3 {{ $ticket->status }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-2">
                                                <h6 class="mb-0 mr-2">
                                                    <a href="{{ route('tickets.show', $ticket) }}">
                                                        #{{ $ticket->ticket_number }}
                                                    </a>
                                                </h6>
                                                <span class="badge badge-{{ $ticket->status_color }} mr-2">
                                                    {{ $ticket->status_label }}
                                                </span>
                                                <span class="badge priority-badge badge-{{ $ticket->priority_color }}">
                                                    {{ $ticket->priority_label }}
                                                </span>
                                            </div>
                                            <h5 class="mb-1">{{ $ticket->subject }}</h5>
                                            <p class="text-muted mb-2 small">{{ Str::limit($ticket->message, 100) }}</p>
                                            <div class="d-flex align-items-center text-muted small">
                                                <i class="fas fa-user mr-1"></i>
                                                <span class="mr-3">{{ $ticket->user->name }}</span>
                                                <i class="fas fa-tag mr-1"></i>
                                                <span class="mr-3">{{ $ticket->category->name }}</span>
                                                <i class="fas fa-clock mr-1"></i>
                                                <span>{{ $ticket->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-gray-300 mb-3"></i>
                                <p class="text-gray-500">لا توجد تذاكر</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- إحصائيات حسب الفئة --}}
            <div class="col-lg-4 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-chart-pie mr-2"></i>
                            التذاكر حسب الفئة
                        </h6>
                    </div>
                    <div class="card-body">
                        @forelse($ticketsByCategory as $item)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="font-weight-bold">{{ $item['category'] }}</span>
                                    <span class="badge badge-primary">{{ $item['count'] }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    @php
                                        $maxCount = $ticketsByCategory->max('count');
                                        $percentage = $maxCount > 0 ? ($item['count'] / $maxCount) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-primary" role="progressbar" 
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                                @if($item['pending'] > 0)
                                    <small class="text-warning">
                                        <i class="fas fa-clock"></i> {{ $item['pending'] }} قيد الانتظار
                                    </small>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted text-center">لا توجد بيانات</p>
                        @endforelse
                    </div>
                </div>

                {{-- إحصائيات سريعة --}}
                <div class="card shadow mt-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle mr-2"></i>
                            معلومات سريعة
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>تذاكر عاجلة</span>
                                <span class="badge badge-danger">{{ $stats['urgent'] ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>تم حلها</span>
                                <span class="badge badge-success">{{ $stats['resolved'] }}</span>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between">
                                <span>مغلقة</span>
                                <span class="badge badge-secondary">{{ $stats['closed'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

