@extends('layouts.app')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
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

        .ticket-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 8px 8px 0 0;
        }

        .attachment-preview {
            max-width: 300px;
            border-radius: 8px;
            border: 2px solid #e3e6f0;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .response-form {
            background: #f8f9fc;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 2rem;
        }

        .star-rating {
            font-size: 1.5rem;
            color: #ffc107;
            cursor: pointer;
        }

        .star-rating .far {
            color: #ddd;
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
                            تفاصيل التذكرة #{{ $ticket->ticket_number }}
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
                                <li class="breadcrumb-item">
                                    <a href="{{ route('tickets.index') }}">التذاكر</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">تفاصيل التذكرة</li>
                            </ol>
                        </nav>
                    </div>
                    <a href="{{ route('tickets.index') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-right"></i> العودة
                    </a>
                </div>
            </div>
        </div>

        @include('components.alerts')

        <div class="row">
            {{-- المحتوى الرئيسي --}}
            <div class="col-lg-8 mb-4">
                {{-- رأس التذكرة --}}
                <div class="card shadow mb-4">
                    <div class="ticket-header">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h2 class="mb-2">{{ $ticket->subject }}</h2>
                                <div class="d-flex align-items-center flex-wrap">
                                    <span class="badge badge-light mr-2 mb-2">
                                        <i class="fas fa-tag"></i> {{ $ticket->category->name }}
                                    </span>
                                    <span class="badge badge-light mr-2 mb-2">
                                        <i class="fas fa-flag"></i> {{ $ticket->priority_label }}
                                    </span>
                                    <span class="badge badge-light mb-2">
                                        <i class="fas fa-info-circle"></i> {{ $ticket->status_label }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="badge badge-light badge-lg p-2">
                                    #{{ $ticket->ticket_number }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- معلومات المستخدم --}}
                        <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                            <img src="{{ $ticket->user->avatar ? asset('storage/' . $ticket->user->avatar) : asset('img/default-avatar.png') }}"
                                 alt="{{ $ticket->user->name }}" 
                                 class="rounded-circle mr-3"
                                 style="width: 60px; height: 60px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h5 class="mb-1">{{ $ticket->user->name }}</h5>
                                <p class="text-muted mb-0 small">
                                    <i class="fas fa-envelope"></i> {{ $ticket->user->email }}
                                    <span class="mr-2 ml-2">|</span>
                                    <i class="fas fa-clock"></i> {{ $ticket->created_at->format('Y-m-d H:i:s') }}
                                </p>
                            </div>
                        </div>

                        {{-- رسالة المستخدم --}}
                        <div class="chat-message message-user">
                            <div class="mb-2">
                                <strong><i class="fas fa-user"></i> {{ $ticket->user->name }}</strong>
                                <small class="float-left">{{ $ticket->created_at->format('Y-m-d H:i') }}</small>
                            </div>
                            <div>{!! nl2br(e($ticket->message)) !!}</div>
                            
                            @if($ticket->attachment)
                                <div class="mt-3">
                                    <a href="{{ route('tickets.download', $ticket) }}" 
                                       class="btn btn-sm btn-light">
                                        <i class="fas fa-paperclip"></i> تحميل المرفق
                                    </a>
                                </div>
                            @endif
                        </div>

                        {{-- رد الدعم الفني --}}
                        @if($ticket->hasResponse())
                            <div class="chat-message message-support">
                                <div class="mb-2">
                                    <strong><i class="fas fa-headset"></i> فريق الدعم الفني</strong>
                                    @if($ticket->responder)
                                        <small class="text-muted"> - {{ $ticket->responder->name }}</small>
                                    @endif
                                    <small class="float-left">{{ $ticket->responded_at->format('Y-m-d H:i') }}</small>
                                </div>
                                <div>{!! nl2br(e($ticket->response)) !!}</div>
                            </div>
                        @else
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i> لم يتم الرد على هذه التذكرة بعد
                            </div>
                        @endif

                        {{-- التقييم --}}
                        @if($ticket->hasResponse() && $ticket->rating)
                            <div class="card mt-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-star"></i> تقييم الخدمة</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $ticket->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    @if($ticket->feedback)
                                        <p class="mb-0"><strong>التعليق:</strong> {{ $ticket->feedback }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- نموذج الرد --}}
                @if(!$ticket->isClosed())
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-reply"></i> الرد على التذكرة</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('tickets.respond', $ticket) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="response">الرد <span class="text-danger">*</span></label>
                                    <textarea name="response" id="response" rows="6" class="form-control" required>{{ old('response', $ticket->response) }}</textarea>
                                    @error('response')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="status">تحديث الحالة</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                                        <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>تم الحل</option>
                                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>إغلاق التذكرة</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane"></i> إرسال الرد
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>

            {{-- اللوحة الجانبية --}}
            <div class="col-lg-4 mb-4">
                {{-- معلومات التذكرة --}}
                <div class="card shadow mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> معلومات التذكرة</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>الحالة:</strong>
                            <span class="badge badge-{{ $ticket->status_color }} float-left">
                                {{ $ticket->status_label }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <strong>الأولوية:</strong>
                            <span class="badge badge-{{ $ticket->priority_color }} float-left">
                                {{ $ticket->priority_label }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <strong>الفئة:</strong>
                            <span class="badge badge-info float-left">
                                {{ $ticket->category->name }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <strong>تاريخ الإنشاء:</strong>
                            <div class="text-muted">{{ $ticket->created_at->format('Y-m-d H:i:s') }}</div>
                        </div>

                        @if($ticket->responded_at)
                            <div class="mb-3">
                                <strong>تاريخ الرد:</strong>
                                <div class="text-muted">{{ $ticket->responded_at->format('Y-m-d H:i:s') }}</div>
                            </div>
                        @endif

                        @if($ticket->closed_at)
                            <div class="mb-3">
                                <strong>تاريخ الإغلاق:</strong>
                                <div class="text-muted">{{ $ticket->closed_at->format('Y-m-d H:i:s') }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- تحديث الحالة والأولوية --}}
                @if(!$ticket->isClosed())
                    <div class="card shadow mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fas fa-cog"></i> إعدادات سريعة</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('tickets.update-status', $ticket) }}" method="POST" class="mb-3">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="status_change">تحديث الحالة</label>
                                    <select name="status" id="status_change" class="form-control">
                                        <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>مفتوحة</option>
                                        <option value="in_progress" {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>قيد المعالجة</option>
                                        <option value="resolved" {{ $ticket->status === 'resolved' ? 'selected' : '' }}>تم الحل</option>
                                        <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>إغلاق</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary btn-block">
                                    تحديث الحالة
                                </button>
                            </form>

                            <form action="{{ route('tickets.update-priority', $ticket) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="priority_change">تحديث الأولوية</label>
                                    <select name="priority" id="priority_change" class="form-control">
                                        <option value="low" {{ $ticket->priority === 'low' ? 'selected' : '' }}>منخفضة</option>
                                        <option value="medium" {{ $ticket->priority === 'medium' ? 'selected' : '' }}>متوسطة</option>
                                        <option value="high" {{ $ticket->priority === 'high' ? 'selected' : '' }}>عالية</option>
                                        <option value="urgent" {{ $ticket->priority === 'urgent' ? 'selected' : '' }}>عاجلة</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-sm btn-warning btn-block">
                                    تحديث الأولوية
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- حذف التذكرة --}}
                <div class="card shadow">
                    <div class="card-header bg-danger text-white">
                        <h6 class="mb-0"><i class="fas fa-trash"></i> منطقة الخطر</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">يمكنك حذف هذه التذكرة إذا لزم الأمر.</p>
                        <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" 
                              onsubmit="return confirm('هل أنت متأكد من حذف هذه التذكرة؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-trash"></i> حذف التذكرة
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        // محرر النصوص للرد
        var quill = new Quill('#response', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    ['link'],
                    ['clean']
                ]
            }
        });

        var textarea = document.querySelector('#response');
        quill.on('text-change', function() {
            textarea.value = quill.root.innerHTML;
        });
    </script>
@endpush

