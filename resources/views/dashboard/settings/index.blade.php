@extends('layouts.app')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .setting-group-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .setting-group-card:hover {
            border-color: #4e73df;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }

        .setting-group-card.active {
            border-color: #4e73df;
            background: linear-gradient(135deg, rgba(78, 115, 223, 0.05) 0%, rgba(78, 115, 223, 0.1) 100%);
        }

        .setting-item {
            background: #fff;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e3e6f0;
            transition: all 0.3s ease;
        }

        .setting-item:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            border-color: #4e73df;
        }

        .setting-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .setting-description {
            color: #718096;
            font-size: 0.875rem;
            margin-bottom: 0.75rem;
        }

        .image-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 8px;
            border: 2px solid #e3e6f0;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #4e73df;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .nav-pills .nav-link {
            border-radius: 8px;
            padding: 0.75rem 1.25rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link:hover {
            background-color: rgba(78, 115, 223, 0.1);
        }

        .nav-pills .nav-link.active {
            background-color: #4e73df;
        }

        .settings-form {
            background: #f8f9fc;
            border-radius: 12px;
            padding: 2rem;
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
                            <i class="fas fa-cogs mr-2"></i>
                            إعدادات الموقع
                        </h1>
                        <nav aria-label="breadcrumb" class="mt-2">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('dashboard') }}">
                                        <i class="fas fa-home"></i> لوحة التحكم
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">الإعدادات</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        @include('components.alerts')

        <div class="row">
            {{-- القائمة الجانبية للمجموعات --}}
            <div class="col-md-3 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-list mr-2"></i>
                            مجموعات الإعدادات
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <ul class="nav nav-pills flex-column">
                            @foreach($groups as $groupKey => $groupData)
                                <li class="nav-item">
                                    <a class="nav-link {{ $group === $groupKey ? 'active' : '' }}"
                                       href="{{ route('settings.index', ['group' => $groupKey]) }}">
                                        <i class="{{ $groupData['icon'] }} mr-2"></i>
                                        {{ $groupData['name'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- محتوى الإعدادات --}}
            <div class="col-md-9">
                <div class="card shadow">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="{{ $groups[$group]['icon'] }} mr-2"></i>
                                {{ $groups[$group]['name'] }}
                            </h6>
                            <small class="text-muted">{{ $groups[$group]['description'] }}</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="group" value="{{ $group }}">

                            <div class="settings-form">
                                @forelse($settings as $setting)
                                    <div class="setting-item">
                                        <label class="setting-label">
                                            @if($setting->label)
                                                {{ $setting->label }}
                                            @else
                                                {{ ucfirst(str_replace('_', ' ', $setting->key)) }}
                                            @endif
                                        </label>

                                        @if($setting->description)
                                            <div class="setting-description">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                {{ $setting->description }}
                                            </div>
                                        @endif

                                        @if($setting->type === 'text')
                                            <input type="text" 
                                                   name="settings[{{ $setting->key }}]" 
                                                   class="form-control"
                                                   value="{{ old("settings.{$setting->key}", $setting->value) }}"
                                                   placeholder="أدخل {{ $setting->label ?? $setting->key }}">

                                        @elseif($setting->type === 'email')
                                            <input type="email" 
                                                   name="settings[{{ $setting->key }}]" 
                                                   class="form-control"
                                                   value="{{ old("settings.{$setting->key}", $setting->value) }}"
                                                   placeholder="example@domain.com">

                                        @elseif($setting->type === 'url')
                                            <input type="url" 
                                                   name="settings[{{ $setting->key }}]" 
                                                   class="form-control"
                                                   value="{{ old("settings.{$setting->key}", $setting->value) }}"
                                                   placeholder="https://">

                                        @elseif($setting->type === 'number')
                                            <input type="number" 
                                                   name="settings[{{ $setting->key }}]" 
                                                   class="form-control"
                                                   value="{{ old("settings.{$setting->key}", $setting->value) }}">

                                        @elseif($setting->type === 'textarea')
                                            <textarea name="settings[{{ $setting->key }}]" 
                                                      class="form-control" 
                                                      rows="6"
                                                      id="editor-{{ $setting->key }}">{{ old("settings.{$setting->key}", $setting->value) }}</textarea>

                                        @elseif($setting->type === 'boolean')
                                            <label class="toggle-switch">
                                                <input type="checkbox" 
                                                       name="settings[{{ $setting->key }}]" 
                                                       value="1"
                                                       {{ old("settings.{$setting->key}", $setting->value) == '1' ? 'checked' : '' }}>
                                                <span class="slider"></span>
                                            </label>

                                        @elseif($setting->type === 'image')
                                            <div class="form-group">
                                                @if($setting->value)
                                                    <div class="mb-2">
                                                        <img src="{{ asset('storage/' . $setting->value) }}" 
                                                             alt="{{ $setting->label }}" 
                                                             class="image-preview"
                                                             onerror="this.style.display='none'">
                                                    </div>
                                                @endif
                                                <div class="custom-file">
                                                    <input type="file" 
                                                           class="custom-file-input" 
                                                           id="file-{{ $setting->key }}"
                                                           name="settings[{{ $setting->key }}]"
                                                           accept="image/*">
                                                    <label class="custom-file-label" for="file-{{ $setting->key }}">
                                                        اختر ملف
                                                    </label>
                                                </div>
                                                <small class="form-text text-muted">
                                                    الحد الأقصى لحجم الملف: 2MB
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="alert alert-info text-center">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        لا توجد إعدادات في هذه المجموعة
                                    </div>
                                @endforelse

                                @if($settings->count() > 0)
                                    <div class="d-flex justify-content-between mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-save mr-2"></i>
                                            حفظ الإعدادات
                                        </button>
                                        <a href="{{ route('settings.index', ['group' => $group]) }}" class="btn btn-secondary btn-lg">
                                            <i class="fas fa-times mr-2"></i>
                                            إلغاء
                                        </a>
                                    </div>
                                @endif
                            </div>
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
        // تحميل محرر النصوص للـ textarea
        document.querySelectorAll('textarea[id^="editor-"]').forEach(function(textarea) {
            var quill = new Quill('#' + textarea.id, {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link'],
                        ['clean']
                    ]
                }
            });

            quill.on('text-change', function() {
                textarea.value = quill.root.innerHTML;
            });
        });

        // عرض اسم الملف عند اختياره
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName);
        });

        // تأكيد قبل الحفظ
        $('#settingsForm').on('submit', function(e) {
            if (!confirm('هل أنت متأكد من حفظ التغييرات؟')) {
                e.preventDefault();
                return false;
            }
        });
    </script>
@endpush

